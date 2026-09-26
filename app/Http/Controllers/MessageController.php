<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\StoreMessageReplyRequest;
use App\Http\Requests\StoreMessageRequest;
use App\Models\ActivityLog;
use App\Models\Message;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkoutPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = Message::query()
            ->with(['sender', 'recipient', 'unit', 'readBy'])
            ->visibleTo($request->user())
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('messages.index', compact('messages'));
    }

    public function create(Request $request): View
    {
        $user = $request->user();
        $role = $user->role?->value;

        $recipients = match ($role) {
            UserRole::TEACHER->value => User::query()->where('role', UserRole::STUDENT->value)->where('active', true)->whereHas('studentProfile')->whereHas('studentProfile.workoutPlans', fn ($query) => $query->where('teacher_id', $user->id))->orderBy('name')->get(),
            UserRole::STUDENT->value => User::query()->where('role', UserRole::TEACHER->value)->where('active', true)->whereHas('workoutPlansAsTeacher', fn ($query) => $query->where('student_id', $user->studentProfile?->id))->orderBy('name')->get(),
            default => collect(),
        };
        $units = Unit::query()
            ->where('active', true)
            ->when($role === UserRole::MANAGER->value, fn ($query) => $query->whereIn('id', $user->accessibleUnitIds()))
            ->when($role === UserRole::STUDENT->value, fn ($query) => $query->whereKey($user->unit_id))
            ->orderBy('name')
            ->get();

        return view('messages.create', compact('recipients', 'units', 'role'));
    }

    public function store(StoreMessageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();
        $role = $user->role?->value;

        if (in_array($role, [UserRole::ADMIN->value, UserRole::MANAGER->value], true)) {
            abort_unless(in_array($data['audience'], ['all', 'unit'], true), 403);
            abort_unless($data['audience'] !== 'unit' || in_array((int) ($data['unit_id'] ?? 0), $user->accessibleUnitIds(), true) || $role === UserRole::ADMIN->value, 403);
            $data['recipient_id'] = null;
        } elseif ($role === UserRole::STUDENT->value && $data['audience'] === 'unit') {
            abort_unless($user->unit_id !== null, 403);
            $data['recipient_id'] = null;
            $data['unit_id'] = $user->unit_id;
        } else {
            abort_unless($data['audience'] === 'direct' && $this->canMessageRecipient($user, (int) ($data['recipient_id'] ?? 0)), 403);
            $data['unit_id'] = null;
        }

        $message = Message::create($data + ['sender_id' => $user->id]);
        ActivityLog::record('created', $message, 'Mensagem enviada.');

        return redirect()->route('messages.show', $message)->with('success', 'Mensagem enviada com sucesso.');
    }

    public function show(Request $request, Message $message): View
    {
        abort_unless(Message::query()->whereKey($message->id)->visibleTo($request->user())->exists(), 403);
        $message = $message->parent_id ? $message->parent()->firstOrFail() : $message;
        $message->load(['sender', 'recipient', 'unit', 'readBy', 'replies.sender', 'replies.readBy']);

        Message::query()
            ->where(function ($query) use ($message): void {
                $query->whereKey($message->id)->orWhere('parent_id', $message->id);
            })
            ->where('sender_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now(), 'read_by' => $request->user()->id]);
        $message->load(['readBy', 'replies.readBy']);

        return view('messages.show', compact('message'));
    }

    public function reply(StoreMessageReplyRequest $request, Message $message): RedirectResponse
    {
        abort_unless($message->newQuery()->whereKey($message->id)->visibleTo($request->user())->exists(), 403);

        $root = $message->parent_id ? $message->parent : $message;
        $root->loadMissing(['sender', 'recipient']);
        $user = $request->user();
        $audience = $root->audience;
        $recipientId = null;
        $unitId = $root->unit_id;

        if ($audience === 'direct') {
            abort_unless(in_array($user->id, [$root->sender_id, $root->recipient_id], true), 403);
            $recipientId = $root->sender_id === $user->id ? $root->recipient_id : $root->sender_id;
            abort_unless($recipientId !== null, 403);
            $unitId = null;
        } else {
            abort_unless(in_array($user->role?->value, [UserRole::ADMIN->value, UserRole::MANAGER->value, UserRole::FINANCIAL->value, UserRole::STUDENT->value], true), 403);
            if ($user->role?->value === UserRole::STUDENT->value) {
                abort_unless((int) $user->unit_id === (int) $root->unit_id, 403);
            }
        }

        $reply = Message::create([
            'parent_id' => $root->id,
            'sender_id' => $user->id,
            'recipient_id' => $recipientId,
            'unit_id' => $unitId,
            'audience' => $audience,
            'subject' => str_starts_with($root->subject, 'Re: ') ? $root->subject : 'Re: '.$root->subject,
            'body' => $request->validated('body'),
        ]);
        ActivityLog::record('created', $reply, 'Resposta de mensagem enviada.');

        return redirect()->route('messages.show', $root)->with('success', 'Resposta enviada com sucesso.');
    }

    private function canMessageRecipient(User $sender, int $recipientId): bool
    {
        return match ($sender->role?->value) {
            UserRole::TEACHER->value => WorkoutPlan::query()->where('teacher_id', $sender->id)->whereHas('student.user', fn ($query) => $query->whereKey($recipientId))->exists(),
            UserRole::STUDENT->value => WorkoutPlan::query()->where('student_id', $sender->studentProfile?->id)->where('teacher_id', $recipientId)->exists(),
            default => false,
        };
    }
}
