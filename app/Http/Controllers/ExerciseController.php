<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Models\ActivityLog;
use App\Models\Exercise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ExerciseController extends Controller
{
    public function index(Request $request): View
    {
        $exercises = Exercise::query()->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->string('search')->toString().'%')->orWhere('muscle_group', 'like', '%'.$request->string('search')->toString().'%'))->when($request->filled('active'), fn ($q) => $q->where('active', $request->boolean('active')))->orderBy('name')->paginate(15)->withQueryString();

        return view('exercises.index', compact('exercises'));
    }

    public function create(): View
    {
        return view('exercises.create', ['exercise' => new Exercise(['active' => true])]);
    }

    public function store(StoreExerciseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image_file')) {
            $data['image_path'] = $request->file('image_file')->store('exercises', 'public');
        }
        unset($data['image_file']);
        $exercise = Exercise::create($data);
        ActivityLog::record('created', $exercise, 'Exercício criado.');

        return redirect()->route('exercises.show', $exercise)->with('success', 'Exercício cadastrado com sucesso.');
    }

    public function show(Exercise $exercise): View
    {
        return view('exercises.show', compact('exercise'));
    }

    public function edit(Exercise $exercise): View
    {
        return view('exercises.edit', compact('exercise'));
    }

    public function update(UpdateExerciseRequest $request, Exercise $exercise): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image_file')) {
            if ($exercise->image_path) {
                Storage::disk('public')->delete($exercise->image_path);
            }
            $data['image_path'] = $request->file('image_file')->store('exercises', 'public');
        }
        unset($data['image_file']);
        $exercise->update($data);
        ActivityLog::record('updated', $exercise, 'Exercício atualizado.');

        return redirect()->route('exercises.show', $exercise)->with('success', 'Exercício atualizado com sucesso.');
    }

    public function destroy(Exercise $exercise): RedirectResponse
    {
        $exercise->update(['active' => false]);
        ActivityLog::record('updated', $exercise, 'Exercício inativado.');

        return redirect()->route('exercises.index')->with('success', 'Exercício inativado com sucesso.');
    }
}
