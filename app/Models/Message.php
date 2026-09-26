<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    protected $fillable = ['parent_id', 'sender_id', 'recipient_id', 'unit_id', 'assigned_to', 'assigned_at', 'audience', 'subject', 'body', 'read_at', 'read_by'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime', 'assigned_at' => 'datetime'];
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        $role = $user->role?->value;

        return $query->where(function (Builder $scope) use ($user, $role): void {
            $scope->where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id)
                ->orWhere('audience', 'all')
                ->when($role === 'student', fn (Builder $builder) => $builder
                    ->orWhere(function (Builder $unitScope) use ($user): void {
                        $unitScope->where('audience', 'unit')->where('unit_id', $user->unit_id);
                    })
                    ->orWhereHas('parent', fn (Builder $parent) => $parent->where('sender_id', $user->id)))
                ->when($role === 'admin', fn (Builder $builder) => $builder->orWhere(function (Builder $staffScope) use ($user): void {
                    $staffScope->whereIn('audience', ['unit', 'reception'])
                        ->where(function (Builder $assignmentScope) use ($user): void {
                            $assignmentScope->where('audience', 'unit')
                                ->orWhere(function (Builder $receptionScope) use ($user): void {
                                    $receptionScope->where('audience', 'reception')
                                        ->where(function (Builder $claimScope) use ($user): void {
                                            $claimScope->whereNull('assigned_to')->orWhere('assigned_to', $user->id);
                                        });
                                });
                        });
                }))
                ->when(in_array($role, ['manager', 'financial'], true), fn (Builder $builder) => $builder->orWhere(function (Builder $unitScope) use ($user, $role): void {
                    $unitScope->whereIn('audience', ['unit', 'reception'])
                        ->whereIn('unit_id', $role === 'manager' ? $user->accessibleUnitIds() : [$user->unit_id])
                        ->where(function (Builder $assignmentScope) use ($user): void {
                            $assignmentScope->where('audience', 'unit')
                                ->orWhere(function (Builder $receptionScope) use ($user): void {
                                    $receptionScope->where('audience', 'reception')
                                        ->where(function (Builder $claimScope) use ($user): void {
                                            $claimScope->whereNull('assigned_to')->orWhere('assigned_to', $user->id);
                                        });
                                });
                        });
                }));
        })->when($role === 'student', fn (Builder $builder) => $builder->where('messages.created_at', '>=', $user->created_at));
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->oldest();
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function readBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'read_by');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(MessageRead::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
