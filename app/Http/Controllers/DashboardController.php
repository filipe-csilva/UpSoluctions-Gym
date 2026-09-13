<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\Unit;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'totalStudents' => StudentProfile::query()->count(),
            'totalUnits' => Unit::query()->where('active', true)->count(),
            'recentStudents' => StudentProfile::query()
                ->with('user')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
