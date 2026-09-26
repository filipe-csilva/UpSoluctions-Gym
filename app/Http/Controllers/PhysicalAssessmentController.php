<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhysicalAssessmentRequest;
use App\Models\ActivityLog;
use App\Models\PhysicalAssessment;
use App\Models\StudentProfile;
use App\Models\User;
use App\Notifications\PhysicalAssessmentCreated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhysicalAssessmentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $assessments = PhysicalAssessment::with(['student.user', 'teacher'])
            ->when($user->role?->value === 'teacher', fn ($query) => $query->whereHas('student.user', fn ($studentQuery) => $studentQuery->where('unit_id', $user->unit_id)))
            ->when($request->filled('student_id'), fn ($q) => $q->where('student_id', $request->integer('student_id')))
            ->latest('assessment_date')->paginate(15)->withQueryString();

        $students = StudentProfile::with('user')->when($user->role?->value === 'teacher', fn ($query) => $query->whereHas('user', fn ($studentQuery) => $studentQuery->where('unit_id', $user->unit_id)))->get();

        return view('assessments.index', ['assessments' => $assessments, 'students' => $students]);
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $students = StudentProfile::with('user')->when($user->role?->value === 'teacher', fn ($query) => $query->whereHas('user', fn ($studentQuery) => $studentQuery->where('unit_id', $user->unit_id)))->get();

        return view('assessments.create', ['students' => $students, 'teachers' => User::where('role', 'teacher')->where('active', true)->get()]);
    }

    public function history(StudentProfile $student): View
    {
        $student->load('user');
        $user = request()->user();
        abort_unless($user->role?->value !== 'teacher' || (int) $student->user?->unit_id === (int) $user->unit_id, 403);
        $assessments = PhysicalAssessment::query()->with('teacher')->where('student_id', $student->id)->latest('assessment_date')->get();

        return view('assessments.history', compact('student', 'assessments'));
    }

    public function comparison(StudentProfile $student): View
    {
        $student->load('user');
        $user = request()->user();
        abort_unless($user->role?->value !== 'teacher' || (int) $student->user?->unit_id === (int) $user->unit_id, 403);
        $assessments = PhysicalAssessment::query()->with('teacher')->whereBelongsTo($student)->latest('assessment_date')->get()->reverse()->values();

        return view('assessments.comparison', compact('student', 'assessments'));
    }

    public function myHistory(Request $request): View
    {
        abort_unless($request->user()->studentProfile !== null, 403);

        return $this->history($request->user()->studentProfile);
    }

    public function store(StorePhysicalAssessmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['bmi'])) {
            $data['bmi'] = round($data['weight'] / ($data['height'] ** 2), 2);
        }
        $assessment = PhysicalAssessment::create($data);
        ActivityLog::record('created', $assessment, 'Avaliação física criada.');

        $assessment->load('student.user');
        $assessment->student?->user?->notify(new PhysicalAssessmentCreated($assessment));

        return redirect()->route('assessments.index')->with('success', 'Avaliação física registrada com sucesso.');
    }
}
