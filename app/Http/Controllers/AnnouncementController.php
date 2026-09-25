<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $isManager = $user->role?->value === 'manager';
        $unitIds = $isManager ? $user->accessibleUnitIds() : [];
        $announcements = Announcement::query()
            ->with(['unit', 'creator'])
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')))
            ->when($isManager, fn ($query) => $query->where(function ($scope) use ($unitIds): void {
                $scope->whereIn('unit_id', $unitIds)->orWhereNull('unit_id');
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('announcements.index', compact('announcements'));
    }

    public function create(Request $request): View
    {
        return view('announcements.create', [
            'announcement' => new Announcement(['active' => true]),
            'units' => $this->availableUnits($request),
        ]);
    }

    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->ensureUnitAccess($request, $data['unit_id'] ?? null);
        $announcement = Announcement::create($data + ['created_by' => $request->user()->id]);
        ActivityLog::record('created', $announcement, 'Comunicado criado.');

        return redirect()->route('announcements.show', $announcement)->with('success', 'Comunicado criado com sucesso.');
    }

    public function show(Announcement $announcement): View
    {
        $announcement->load(['unit', 'creator']);

        return view('announcements.show', compact('announcement'));
    }

    public function edit(Request $request, Announcement $announcement): View
    {
        return view('announcements.edit', [
            'announcement' => $announcement,
            'units' => $this->availableUnits($request),
        ]);
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        $data = $request->validated();
        $this->ensureUnitAccess($request, $data['unit_id'] ?? null);
        $announcement->update($data);
        ActivityLog::record('updated', $announcement, 'Comunicado atualizado.');

        return redirect()->route('announcements.show', $announcement)->with('success', 'Comunicado atualizado com sucesso.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();
        ActivityLog::record('deleted', $announcement, 'Comunicado excluído.');

        return redirect()->route('announcements.index')->with('success', 'Comunicado excluído com sucesso.');
    }

    private function availableUnits(Request $request): Collection
    {
        $user = $request->user();

        return Unit::query()
            ->where('active', true)
            ->when($user->role?->value === 'manager', fn ($query) => $query->whereIn('id', $user->accessibleUnitIds()))
            ->orderBy('name')
            ->get();
    }

    private function ensureUnitAccess(Request $request, ?int $unitId): void
    {
        if ($request->user()->role?->value === 'manager' && $unitId !== null && ! in_array((int) $unitId, $request->user()->accessibleUnitIds(), true)) {
            abort(403);
        }
    }
}
