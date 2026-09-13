<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    protected $fillable = ['user_id', 'active', 'is_deleted', 'cpf', 'birth_date', 'phone', 'gender', 'address', 'number', 'neighborhood', 'city', 'state', 'zip_code', 'emergency_contact', 'emergency_phone', 'notes'];

    protected function casts(): array
    {
        return ['birth_date' => 'date', 'active' => 'boolean', 'is_deleted' => 'boolean'];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('notDeleted', function (Builder $builder): void {
            $builder->where('is_deleted', false);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
