<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Models\ActivityLog;
use App\Models\Attendance;
use App\Models\StudentProfile;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $attendances = Attendance::with(['student.user', 'unit'])->when($request->filled('date'), fn ($q) => $q->whereDate('date', $request->date))->when($request->filled('search'), fn ($q) => $q->whereHas('student.user', fn ($u) => $u->where('name', 'like', '%'.$request->string('search')->toString().'%')))->latest('date')->latest('entry_time')->paginate(20)->withQueryString();

        return view('attendances.index', compact('attendances'));
    }

    public function create(): View
    {
        return view('attendances.create', ['students' => StudentProfile::with('user')->whereHas('user', fn ($q) => $q->where('active', true))->get(), 'units' => Unit::where('active', true)->orderBy('name')->get()]);
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $attendance = Attendance::create($request->validated() + ['registered_by' => $request->user()->id]);
        ActivityLog::record('created', $attendance, 'Presença registrada.');

        return redirect()->route('attendances.index')->with('success', 'Presença registrada com sucesso.');
    }
}
