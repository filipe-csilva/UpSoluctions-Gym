<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\Unit;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = request()->user();
        $isManager = $user->role?->value === 'manager';
        $unitIds = $isManager ? $user->accessibleUnitIds() : [];

        return view('dashboard', [
            'totalStudents' => StudentProfile::query()
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                        $userQuery->whereIn('unit_id', $unitIds);
                    });
                })
                ->count(),
            'todayStudentsCount' => StudentProfile::query()
                ->whereDate('created_at', today())
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                        $userQuery->whereIn('unit_id', $unitIds);
                    });
                })
                ->count(),
            'todayStudents' => StudentProfile::query()
                ->with('user.unit')
                ->whereDate('created_at', today())
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereHas('user', function ($userQuery) use ($unitIds): void {
                        $userQuery->whereIn('unit_id', $unitIds);
                    });
                })
                ->latest()
                ->limit(6)
                ->get(),
            'totalUnits' => Unit::query()
                ->where('active', true)
                ->when($isManager, function ($query) use ($unitIds): void {
                    $query->whereIn('id', $unitIds);
                })
                ->count(),
        ]);
    }
}
