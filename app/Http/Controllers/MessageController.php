<?php

namespace App\Http\Controllers;

use App\Events\MessageCreated;
use App\Events\MessageStatusUpdated;
use App\Http\Requests\MessageStoreRequest;
use App\Http\Resources\MessageResource;
use App\Models\Chat;
use App\Models\Message;
use App\Traits\ApiResponse;

class MessageController extends Controller
{
    use ApiResponse;

    public function store(MessageStoreRequest $request)
    {
        $validatedData = $request->validated();
        $chatId = $validatedData['chat'];
        $message = $validatedData['message'];
        $user = auth()->user();
        $chat = Chat::findOrFail($chatId);

        if (!$chat->users->contains($user)) {
            return $this->errorResponse('You must be a member of the chat to send messages', 401);
        }

        // $this->authorize('create', [$user, $chat]);

        $message = Message::create([
            'message' => $message,
            'user_id' => $user->id,
            'chat_id' => $chat->id,
        ]);
        broadcast(new MessageCreated($message, $chat))->toOthers();
        return $this->successResponse(MessageResource::make($message));
    }
    public function destroy(Message $message)
    {
         $this->messageAuthorize($message);

        $message->delete();

        return $this->customResponse([], 'Message deleted successfully');
    }

    public function restore($message)
    {
        $getMessage = Message::withTrashed()->find($message);
        $this->messageAuthorize($getMessage);

        $getMessage->restore();
        $chat = $getMessage->chat;
        broadcast(new MessageStatusUpdated($getMessage, $chat));
        return $this->successResponse(MessageResource::make($getMessage));
    }

    public function softDelete(Message $message)
    {
        $this->messageAuthorize($message);
        $chat = $message->chat;

        broadcast(new MessageStatusUpdated($message, $chat));

        return $this->successResponse(MessageResource::make($message));
        $message->softDelete();
    }
    public function messageAuthorize($message)
    {

        $userId = auth()->id();
        if ($userId != $message->user_id) {
            return $this->errorResponse('You do not have access to delete this message', 401);
        }
    }
}
