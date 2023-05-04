<?php
namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use App\Models\Chat;
use App\Models\Message;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use ApiResponse;

    public function store(MessageStoreRequest $request)
    {
        $validatedData = $request->validated();
        $chat = $validatedData['chat'];
        $message = $validatedData['message'];
        $user = auth()->user();

        $this->authorize('create', [$user, Chat::findOrFail($chat)]);

        Message::create([
            'message' => $message,
            'user_id' => $user->id,
            'chat_id' => $chat,
        ]);

        return $this->customResponse([], 'Message created successfully');
    }

    public function destroy(Message $message)
    {
        $user = auth()->user();

        $this->authorize('delete', [$user, $message]);

        $message->delete();

        return $this->customResponse([], 'Message deleted successfully');
    }

    public function restore(Message $message)
    {
        $user = auth()->user();

        $this->authorize('restore', [$user, $message]);

        $message->restore();

        return $this->successResponse($message);
    }

    public function softDelete(Message $message)
    {
        $user = auth()->user();

        $this->authorize('softDelete', [$user, $message]);

        $message->delete();

        return $this->customResponse([], 'Message deleted successfully');
    }
}