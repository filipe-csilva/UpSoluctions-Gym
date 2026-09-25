<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $role = $user->role?->value;

        $announcements = Announcement::query()
            ->with('unit')
            ->where('active', true)
            ->where(function ($query): void {
                $query->whereNull('start_at')->orWhere('start_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('end_at')->orWhere('end_at', '>=', now());
            })
            ->where(function ($query) use ($role): void {
                $query->whereNull('target_role')->orWhere('target_role', 'all')->orWhere('target_role', $role);
            })
            ->where(function ($query) use ($user): void {
                $query->whereNull('unit_id')->orWhere('unit_id', $user->unit_id);
            })
            ->latest()
            ->paginate(9);

        return view('panel', compact('announcements'));
    }
}
