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
        $assessments = PhysicalAssessment::with(['student.user', 'teacher'])->when($request->filled('student_id'), fn ($q) => $q->where('student_id', $request->integer('student_id')))->latest('assessment_date')->paginate(15)->withQueryString();

        return view('assessments.index', ['assessments' => $assessments, 'students' => StudentProfile::with('user')->get()]);
    }

    public function create(): View
    {
        return view('assessments.create', ['students' => StudentProfile::with('user')->get(), 'teachers' => User::where('role', 'teacher')->where('active', true)->get()]);
    }

    public function history(StudentProfile $student): View
    {
        $student->load('user');
        $assessments = PhysicalAssessment::query()->with('teacher')->where('student_id', $student->id)->latest('assessment_date')->get();

        return view('assessments.history', compact('student', 'assessments'));
    }

    public function comparison(StudentProfile $student): View
    {
        $student->load('user');
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
