<?php

namespace App\Http\Controllers;

use App\Models\TeacherProfile;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    /**
     * Lista os instrutores.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $teachers = TeacherProfile::query()
            ->with(['user.unit'])
            ->whereHas('user')
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = $request->string('search')->toString();
                $query->where(function ($teacherQuery) use ($search): void {
                    $teacherQuery
                        ->where('cpf', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search): void {
                            $userQuery->where(function ($userSearch) use ($search): void {
                                $userSearch->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                        });
                });
            })
            ->when($request->filled('unit_id'), function ($query) use ($request): void {
                $query->whereHas('user', function ($userQuery) use ($request): void {
                    $userQuery->where('unit_id', $request->integer('unit_id'));
                });
            })
            ->when($request->filled('active'), function ($query) use ($request): void {
                $query->whereHas('user', function ($userQuery) use ($request): void {
                    $userQuery->where('active', $request->boolean('active'));
                });
            })
            ->when($user->role?->value === 'manager', function ($query) use ($user): void {
                $query->whereHas('user', function ($userQuery) use ($user): void {
                    $userQuery->whereIn('unit_id', $user->accessibleUnitIds());
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $units = Unit::query()
            ->where('active', true)
            ->when($user->role?->value === 'manager', function ($query) use ($user): void {
                $query->whereIn('id', $user->accessibleUnitIds());
            })
            ->orderBy('name')
            ->get();

        return view('teachers.index', compact('teachers', 'units'));
    }
}
