<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $announcements = Announcement::query()
            ->where('active', true)
            ->where(fn ($query) => $query->whereNull('start_at')->orWhere('start_at', '<=', now()))
            ->where(fn ($query) => $query->whereNull('end_at')->orWhere('end_at', '>=', now()))
            ->where(fn ($query) => $query->whereNull('target_role')->orWhere('target_role', 'all')->orWhere('target_role', $user->role?->value))
            ->where(fn ($query) => $query->whereNull('unit_id')->orWhere('unit_id', $user->unit_id))
            ->when($user->role?->value === 'student', fn ($query) => $query->where(fn ($scope) => $scope->where('is_default', true)->orWhere('announcements.created_at', '>=', $user->created_at)))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return AnnouncementResource::collection($announcements)->response();
    }

    public function show(Request $request, Announcement $announcement): AnnouncementResource
    {
        abort_unless($announcement->active, 404);
        abort_unless($announcement->unit_id === null || (int) $announcement->unit_id === (int) $request->user()->unit_id, 404);

        return new AnnouncementResource($announcement);
    }
}
