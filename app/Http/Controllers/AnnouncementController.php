<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\Unit;
use App\Models\User;
use App\Notifications\AnnouncementPublished;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $canManage = in_array($user->role?->value, ['admin', 'manager'], true);
        $isManager = $user->role?->value === 'manager';
        $unitIds = $isManager ? $user->accessibleUnitIds() : [];
        $announcements = Announcement::query()
            ->with(['unit', 'creator'])
            ->with(['reads' => fn ($query) => $query->where('user_id', $user->id)])
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($canManage && $request->filled('active'), fn ($query) => $query->where('active', $request->boolean('active')))
            ->when(! $canManage, function ($query) use ($user): void {
                $query->where('active', true)
                    ->where(function ($scope): void {
                        $scope->whereNull('start_at')->orWhere('start_at', '<=', now());
                    })
                    ->where(function ($scope): void {
                        $scope->whereNull('end_at')->orWhere('end_at', '>=', now());
                    })
                    ->where(function ($scope) use ($user): void {
                        $scope->whereNull('target_role')
                            ->orWhere('target_role', 'all')
                            ->orWhere('target_role', $user->role?->value);
                    })
                    ->where(function ($scope) use ($user): void {
                        $scope->whereNull('unit_id')->orWhere('unit_id', $user->unit_id);
                    });
            })
            ->when($user->role?->value === 'student', fn ($query) => $query->where(function ($scope) use ($user): void {
                $scope->where('is_default', true)->orWhere('announcements.created_at', '>=', $user->created_at);
            }))
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
        if ($announcement->active) {
            $this->notifyAudience($announcement);
        }
        ActivityLog::record('created', $announcement, 'Comunicado criado.');

        return redirect()->route('announcements.show', $announcement)->with('success', 'Comunicado criado com sucesso.');
    }

    public function show(Request $request, Announcement $announcement): View
    {
        if (! $this->canViewAnnouncement($request->user(), $announcement)) {
            abort(404);
        }

        $announcement->load(['unit', 'creator']);
        AnnouncementRead::updateOrCreate(
            ['announcement_id' => $announcement->id, 'user_id' => $request->user()->id],
            ['read_at' => now()],
        );
        $announcement->load(['reads' => fn ($query) => $query->where('user_id', $request->user()->id)]);

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

    private function notifyAudience(Announcement $announcement): void
    {
        User::query()
            ->where('active', true)
            ->when($announcement->unit_id !== null, fn ($query) => $query->where('unit_id', $announcement->unit_id))
            ->when($announcement->target_role !== null && $announcement->target_role !== 'all', fn ($query) => $query->where('role', $announcement->target_role))
            ->get()
            ->each(fn (User $user): mixed => $user->notify(new AnnouncementPublished($announcement)));
    }

    private function canViewAnnouncement(User $user, Announcement $announcement): bool
    {
        if (in_array($user->role?->value, ['admin', 'manager'], true)) {
            return true;
        }

        return $announcement->active
            && ($announcement->start_at === null || $announcement->start_at->isPast())
            && ($announcement->end_at === null || $announcement->end_at->isFuture())
            && ($announcement->target_role === null || $announcement->target_role === 'all' || $announcement->target_role === $user->role?->value)
            && ($announcement->unit_id === null || $announcement->unit_id === $user->unit_id)
            && ($user->role?->value !== 'student' || $announcement->is_default || $announcement->created_at?->gte($user->created_at));
    }
}
