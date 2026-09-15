<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class ActivityLog extends Model
{
    protected $table = 'active_log';

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return ['properties' => 'array'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function dashboardIcon(): string
    {
        return match ($this->action) {
            'login_success' => 'bi-check2-circle',
            'logout' => 'bi-box-arrow-right',
            'login_failed', 'login_inactive', 'deleted' => 'bi-exclamation-triangle-fill',
            'created' => 'bi-person-plus-fill',
            'updated' => 'bi-pencil-square',
            default => 'bi-activity',
        };
    }

    public function dashboardColor(): string
    {
        return match ($this->action) {
            'login_success', 'created' => 'success',
            'logout' => 'danger',
            'login_failed', 'login_inactive', 'deleted' => 'danger',
            'updated' => 'primary',
            default => 'warning',
        };
    }

    public function deviceType(): string
    {
        return $this->user_agent && preg_match('/Mobile|Android|iPhone|iPad/i', $this->user_agent)
            ? 'Mobile'
            : 'PC';
    }

    public function browserName(): string
    {
        return match (true) {
            (bool) preg_match('/Edg/i', (string) $this->user_agent) => 'Edge',
            (bool) preg_match('/Chrome/i', (string) $this->user_agent) => 'Chrome',
            (bool) preg_match('/Firefox/i', (string) $this->user_agent) => 'Firefox',
            (bool) preg_match('/Safari/i', (string) $this->user_agent) => 'Safari',
            default => 'Navegador não identificado',
        };
    }

    public static function record(
        string $action,
        ?Model $subject = null,
        ?string $description = null,
        array $properties = [],
    ): self {
        $request = app(Request::class);

        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'description' => $description,
            'properties' => $properties ?: null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
