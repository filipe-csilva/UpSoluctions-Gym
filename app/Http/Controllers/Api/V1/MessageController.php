<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MessageResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $messages = Message::query()
            ->visibleTo($request->user())
            ->with(['sender:id,name', 'recipient:id,name', 'replies.sender:id,name'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return MessageResource::collection($messages)->response();
    }

    public function show(Request $request, Message $message): MessageResource
    {
        abort_unless(Message::query()->whereKey($message->id)->visibleTo($request->user())->exists(), 404);

        return new MessageResource($message->load(['sender:id,name', 'recipient:id,name', 'replies.sender:id,name']));
    }
}
