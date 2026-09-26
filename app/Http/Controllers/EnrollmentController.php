<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\ActivityLog;
use App\Models\Enrollment;
use App\Models\Plan;
use App\Models\StudentProfile;
use App\Models\Unit;
use App\Services\StudentAccessService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
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
        return view('enrollments.create', ['students' => StudentProfile::with('user')->whereHas('user')->get(), 'plans' => Plan::where('active', true)->orderBy('name')->get(), 'units' => Unit::where('active', true)->orderBy('name')->get()]);
    }

    public function store(StoreEnrollmentRequest $request, StudentAccessService $studentAccessService): RedirectResponse
    {
        $data = $request->validated();
        $plan = Plan::findOrFail($data['plan_id']);
        $data['price'] = $plan->promotionalPrice();
        $data['end_date'] = Carbon::parse($data['start_date'])->addMonths($plan->duration_months)->subDay()->toDateString();
        $enrollment = DB::transaction(function () use ($data): Enrollment {
            $enrollment = Enrollment::create($data);
            $installments = max(1, (int) $enrollment->plan->installments);
            $total = round((float) $enrollment->price, 2);
            $installmentAmount = round($total / $installments, 2);
            $remaining = $total;

            for ($installment = 1; $installment <= $installments; $installment++) {
                $amount = $installment === $installments ? $remaining : $installmentAmount;
                $remaining = round($remaining - $amount, 2);
                $description = $installments > 1
                    ? 'Mensalidade - '.$enrollment->plan->name.' - Parcela '.$installment.'/'.$installments
                    : 'Mensalidade - '.$enrollment->plan->name;

                $enrollment->financialTransactions()->create([
                    'student_id' => $enrollment->student_id,
                    'unit_id' => $enrollment->unit_id,
                    'description' => $description,
                    'amount' => $amount,
                    'due_date' => $enrollment->start_date->copy()->addMonths($installment - 1),
                    'status' => 'pending',
                    'transaction_type' => 'income',
                ]);
            }

            return $enrollment;
        });
        $studentAccessService->syncStudentStatuses();
        ActivityLog::record('created', $enrollment, 'Matrícula criada.');

        return redirect()->route('enrollments.show', $enrollment)->with('success', 'Matrícula criada com sucesso.');
    }

    public function show(Enrollment $enrollment): View
    {
        Gate::forUser(request()->user())->authorize('view', $enrollment);
        $enrollment->load(['student.user', 'plan', 'unit', 'financialTransactions']);

        return view('enrollments.show', compact('enrollment'));
    }

    public function history(StudentProfile $student): View
    {
        abort_unless(request()->user()->can('view', $student), 403);
        $enrollments = Enrollment::query()
            ->with(['plan', 'unit', 'financialTransactions'])
            ->where('student_id', $student->id)
            ->latest('start_date')
            ->get();

        return view('enrollments.history', compact('student', 'enrollments'));
    }

    public function edit(Enrollment $enrollment): View
    {
        Gate::forUser(request()->user())->authorize('update', $enrollment);

        return view('enrollments.edit', ['enrollment' => $enrollment, 'plans' => Plan::where('active', true)->orderBy('name')->get()]);
    }

    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment, StudentAccessService $studentAccessService): RedirectResponse
    {
        Gate::forUser(request()->user())->authorize('update', $enrollment);
        $data = $request->validated();
        $plan = Plan::findOrFail($data['plan_id']);
        $data['price'] = $plan->promotionalPrice();
        $data['end_date'] = Carbon::parse($data['start_date'])->addMonths($plan->duration_months)->subDay()->toDateString();
        $enrollment->update($data);
        $studentAccessService->syncStudentStatuses();
        ActivityLog::record('updated', $enrollment, 'Matrícula atualizada.');

        return redirect()->route('enrollments.show', $enrollment)->with('success', 'Matrícula atualizada com sucesso.');
    }

    public function destroy(Enrollment $enrollment, StudentAccessService $studentAccessService): RedirectResponse
    {
        Gate::forUser(request()->user())->authorize('delete', $enrollment);
        $enrollment->update(['status' => 'cancelled']);
        $studentAccessService->syncStudentStatuses();
        ActivityLog::record('updated', $enrollment, 'Matrícula cancelada.');

        return redirect()->route('enrollments.index')->with('success', 'Matrícula cancelada com sucesso.');
    }
}
