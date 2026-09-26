<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\StoreStudentAttendanceRequest;
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

    public function studentHistory(Request $request, StudentProfile $student): View
    {
        $user = $request->user();
        abort_unless($user->can('view', $student) || ($user->role?->value === 'teacher'), 403);
        $attendances = $student->load('user')->attendances()
            ->with('unit')
            ->when($request->filled('from'), fn ($query) => $query->whereDate('date', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('date', '<=', $request->date('to')))
            ->latest('date')->latest('entry_time')->paginate(20)->withQueryString();

        return view('attendances.student-history', compact('student', 'attendances'));
    }

    public function create(): View
    {
        return view('attendances.create', ['students' => StudentProfile::with('user')->whereHas('user', fn ($q) => $q->where('active', true))->get(), 'units' => Unit::where('active', true)->orderBy('name')->get()]);
    }

    public function studentCreate(): View
    {
        return view('attendances.student-create', [
            'units' => Unit::query()->where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $attendance = Attendance::create($request->validated() + ['registered_by' => $request->user()->id]);
        ActivityLog::record('created', $attendance, 'Presença registrada.');

        return redirect()->route('attendances.index')->with('success', 'Presença registrada com sucesso.');
    }

    public function studentStore(StoreStudentAttendanceRequest $request): RedirectResponse
    {
        $user = $request->user();
        $student = $user->studentProfile;

        $hasOpenAttendance = Attendance::query()
            ->where('student_id', $student->id)
            ->whereDate('date', today())
            ->whereNull('exit_time')
            ->exists();

        if ($hasOpenAttendance) {
            return redirect()->route('panel')->with('warning', 'Você já possui uma presença aberta hoje.');
        }

        $attendance = Attendance::create([
            'student_id' => $student->id,
            'unit_id' => $request->integer('unit_id'),
            'registered_by' => $user->id,
            'date' => today(),
            'entry_time' => now()->format('H:i'),
            'type' => 'regular',
        ]);
        ActivityLog::record('created', $attendance, 'Presença registrada pelo aluno.');

        return redirect()->route('panel')->with('success', 'Presença registrada com sucesso. Bom treino!');
    }
}
