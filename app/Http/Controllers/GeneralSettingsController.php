<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneralSettingsController extends Controller
{
    public function index(): View
    {
        return view('settings.index', ['settings' => GeneralSetting::query()->pluck('value', 'key')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:150'],
            'company_document' => ['nullable', 'string', 'max:30'],
            'company_phone' => ['nullable', 'string', 'max:30'],
            'company_email' => ['nullable', 'email:rfc', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'timezone' => ['required', 'timezone'],
        ]);
        foreach ($data as $key => $value) {
            GeneralSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return back()->with('success', 'Configurações gerais atualizadas com sucesso.');
    }
}
