<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GeneralSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'company_name' => config('app.name', 'GymControl'), 'company_document' => '', 'company_phone' => '',
            'company_email' => '', 'company_address' => '', 'timezone' => config('app.timezone', 'America/Fortaleza'),
            'currency' => 'BRL', 'date_format' => 'd/m/Y', 'brand_primary_color' => '#0d6efd',
            'brand_secondary_color' => '#6c757d', 'brand_success_color' => '#198754', 'brand_danger_color' => '#dc3545',
            'brand_warning_color' => '#ffc107', 'brand_info_color' => '#0dcaf0', 'brand_sidebar_color' => '#343a40',
            'button_primary_color' => '#0d6efd', 'button_secondary_color' => '#6c757d', 'button_success_color' => '#198754',
            'button_danger_color' => '#dc3545', 'button_warning_color' => '#ffc107', 'button_info_color' => '#0dcaf0',
            'icon_dashboard' => 'bi bi-speedometer2', 'icon_students' => 'bi bi-person-vcard',
            'icon_teachers' => 'bi bi-person-workspace', 'icon_employees' => 'bi bi-person-badge',
            'icon_units' => 'bi bi-building', 'icon_plans' => 'bi bi-card-list', 'icon_enrollments' => 'bi bi-journal-check',
            'icon_financial' => 'bi bi-cash-coin', 'icon_communication' => 'bi bi-chat-square-text',
            'icon_announcements' => 'bi bi-megaphone', 'icon_messages' => 'bi bi-chat-dots',
            'icon_reports' => 'bi bi-bar-chart-line', 'icon_attendance' => 'bi bi-calendar-check',
            'icon_exercises' => 'bi bi-activity', 'icon_workout_plans' => 'bi bi-clipboard2-pulse',
            'icon_assessments' => 'bi bi-clipboard2-data', 'icon_profile' => 'bi bi-person-circle',
            'notifications_mail_enabled' => '1', 'notifications_whatsapp_enabled' => '0', 'notifications_push_enabled' => '1',
            'brand_logo_path' => '', 'favicon_path' => '',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function values(): array
    {
        return Cache::remember('general_settings.values', now()->addMinutes(10), function (): array {
            return array_merge(static::defaults(), static::query()->pluck('value', 'key')->all());
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('general_settings.values');
    }

    public static function valueFor(string $key, mixed $default = null): mixed
    {
        return static::values()[$key] ?? $default;
    }
}
