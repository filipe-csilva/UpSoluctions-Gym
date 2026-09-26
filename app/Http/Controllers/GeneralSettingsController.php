<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GeneralSettingsController extends Controller
{
    public function index(): View
    {
        return view('settings.index', ['settings' => GeneralSetting::values()]);
    }

    public function theme(): JsonResponse
    {
        $settings = GeneralSetting::values();

        return response()->json([
            'colors' => collect($settings)->filter(fn ($value, $key): bool => str_ends_with($key, '_color'))->all(),
            'icons' => collect($settings)->filter(fn ($value, $key): bool => str_starts_with($key, 'icon_'))->all(),
            'company_name' => $settings['company_name'],
            'logo_url' => $settings['brand_logo_path'] !== '' ? Storage::url($settings['brand_logo_path']) : null,
            'favicon_url' => $settings['favicon_path'] !== '' ? Storage::url($settings['favicon_path']) : asset('favicon.svg'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'company_name' => ['required', 'string', 'max:150'],
            'company_document' => ['nullable', 'string', 'max:30'],
            'company_phone' => ['nullable', 'string', 'max:30'],
            'company_email' => ['nullable', 'email:rfc', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'timezone' => ['required', 'timezone'],
            'currency' => ['sometimes', 'required', 'string', 'size:3'],
            'date_format' => ['sometimes', 'required', 'in:d/m/Y,m/d/Y,Y-m-d'],
            'brand_primary_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_secondary_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_success_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_danger_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_warning_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_info_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'brand_sidebar_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_primary_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_secondary_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_success_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_danger_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_warning_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'button_info_color' => ['sometimes', 'required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'notifications_mail_enabled' => ['nullable', 'boolean'],
            'notifications_whatsapp_enabled' => ['nullable', 'boolean'],
            'notifications_push_enabled' => ['nullable', 'boolean'],
            'logo_file' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'favicon_file' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:1024'],
        ];
        foreach (array_keys(GeneralSetting::defaults()) as $key) {
            if (str_starts_with($key, 'icon_')) {
                $rules[$key] = ['sometimes', 'required', 'regex:/^bi bi-[a-z0-9-]+$/', 'max:80'];
            }
        }
        $data = $request->validate($rules);

        foreach (['logo_file' => 'brand_logo_path', 'favicon_file' => 'favicon_path'] as $upload => $key) {
            if ($request->hasFile($upload)) {
                $oldPath = GeneralSetting::valueFor($key);
                if (is_string($oldPath) && $oldPath !== '') {
                    Storage::disk('public')->delete($oldPath);
                }
                $data[$key] = $request->file($upload)->store('settings', 'public');
            }
        }

        unset($data['logo_file'], $data['favicon_file']);
        foreach (['notifications_mail_enabled', 'notifications_whatsapp_enabled', 'notifications_push_enabled'] as $key) {
            $data[$key] = $request->boolean($key) ? '1' : '0';
        }
        foreach ($data as $key => $value) {
            GeneralSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        GeneralSetting::clearCache();

        return back()->with('success', 'Configurações gerais atualizadas com sucesso.');
    }
}
