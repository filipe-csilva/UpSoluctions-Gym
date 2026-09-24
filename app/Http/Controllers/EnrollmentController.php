<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Models\Plan;
use App\Models\StudentProfile;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $enrollments = Enrollment::with(['student.user', 'plan', 'unit'])->when($request->filled('search'), fn ($query) => $query->whereHas('student.user', fn ($q) => $q->where('name', 'like', '%'.$request->string('search')->toString().'%')))->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))->latest()->paginate(15)->withQueryString();

        return view('enrollments.index', compact('enrollments'));
    }

    public function create(): View
    {
        return view('enrollments.create', ['students' => StudentProfile::with('user')->whereHas('user', fn ($q) => $q->where('active', true))->get(), 'plans' => Plan::where('active', true)->orderBy('name')->get(), 'units' => Unit::where('active', true)->orderBy('name')->get()]);
    }

    public function store(StoreEnrollmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $enrollment = DB::transaction(function () use ($data): Enrollment {
            $enrollment = Enrollment::create($data);
            $enrollment->financialTransactions()->create(['student_id' => $data['student_id'], 'unit_id' => $data['unit_id'], 'description' => 'Mensalidade - '.$enrollment->plan->name, 'amount' => $data['price'], 'due_date' => $data['start_date'], 'status' => 'pending', 'transaction_type' => 'income']);

            return $enrollment;
        });
        ActivityLog::record('created', $enrollment, 'Matrícula criada.');

        return redirect()->route('enrollments.show', $enrollment)->with('success', 'Matrícula criada com sucesso.');
    }

    public function show(Enrollment $enrollment): View
    {
        $enrollment->load(['student.user', 'plan', 'unit', 'financialTransactions']);

        return view('enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment): View
    {
        return view('enrollments.edit', ['enrollment' => $enrollment, 'plans' => Plan::where('active', true)->orderBy('name')->get()]);
    }

    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment): RedirectResponse
    {
        $enrollment->update($request->validated());
        ActivityLog::record('updated', $enrollment, 'Matrícula atualizada.');

        return redirect()->route('enrollments.show', $enrollment)->with('success', 'Matrícula atualizada com sucesso.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->update(['status' => 'cancelled']);
        ActivityLog::record('updated', $enrollment, 'Matrícula cancelada.');

        return redirect()->route('enrollments.index')->with('success', 'Matrícula cancelada com sucesso.');
    }
}
