<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatAddUserRequest;
use App\Http\Requests\ChatAdminRequest;
use App\Http\Requests\ChatStoreRequest;
use App\Http\Requests\ChatUpdateRequest;
use App\Http\Resources\ChatMessagesResource;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChatController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $chats = $user->chats()->with(['messages' => function ($query) {
            $query->latest()->first();
        }])->get();

        $formattedChats = ChatResource::collection($chats);
        return $this->successResponse($formattedChats);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ChatStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChatStoreRequest $request)
    {
        $user = auth()->user();
        $validatedData = $request->validated();

        $chat = Chat::create([
            'name' => $validatedData['name'],
            'chat_type' => $validatedData['chat_type']
        ]);

        $chat->users()->attach($validatedData['users']);
        $chat->users()->updateExistingPivot($user->id, ['user_role' => 'admin']);
        return $this->successResponse(['chat_id' => $chat->id], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        $this->authorize('view', $chat);

        $chat->load(['messages' => function ($query) {
            $query->where('deleted_at', null)->with('user');
        }, 'usersWithRole']);

        return $this->successResponse(new ChatMessagesResource($chat));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(ChatUpdateRequest $request, Chat $chat)
    {
        $this->authorize('update', $chat);
        $chat_name = $request->validated('name');
        $chat->update([
            'name' => $chat_name,
        ]);
        return $this->customResponse([], 'Succussfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        $this->authorize('delete', $chat);

        // Detach all users from the chat
        $chat->users()->detach();

        // Delete the chat
        $chat->delete();

        return $this->successResponse(null, 'Chat deleted successfully');
    }

    /**
     * Add a user to the chat.
     *
     * @param  \App\Http\Requests\ChatAddUserRequest  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function addParticipants(ChatAddUserRequest $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $userIds = $request->input('users');

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if (!$user) {
                return $this->errorResponse("User with ID $userId not found", 404);
            }
            if ($chat->users->contains($user)) {
                return $this->errorResponse('User is already a member of the chat', 409);
            }
            $chat->users()->attach($user);
        }

        return $this->customResponse(null, 'Users added to chat successfully');
    }

    /**
     * Remove a user from the chat.
     *
     * @param  \App\Http\Requests\ChatAddUserRequest  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function removeParticipant(ChatAdminRequest $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $user = $request->validated('user');
        if (!$chat->users->contains($user)) {
            return $this->errorResponse('User is not a member of the chat', 404);
        }

        // Remove the user from the chat
        $chat->users()->detach($user);

        return $this->successResponse('User removed from chat successfully');
    }

    /**
     * Add an admin to the chat.
     *
     * @param  \App\Http\Requests\ChatAddUserRequest  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function addAdmin(ChatAdminRequest $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $user = $request->validated('user');

        // Check if the user is a member of the chat
        if (!$chat->users->contains($user)) {
            return $this->errorResponse('User is not a member of the chat', 404);
        }

        // Update the user role to admin
        $chat->users()->updateExistingPivot($user, ['user_role' => 'admin']);

        return $this->successResponse('User role updated to admin successfully');
    }

    /**
     * Remove an admin from the chat.
     *
     * @param  \App\Http\Requests\ChatAddUserRequest  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function removeAdmin(ChatAdminRequest $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $user = $request->validated('user');

        // Check if the user is a member of the chat
        if (!$chat->users->contains($user)) {
            return $this->errorResponse('User is not a member of the chat', 404);
        }

        // Update the user role to user
        $chat->users()->updateExistingPivot($user, ['user_role' => 'user']);

        return $this->successResponse( 'User role updated to user successfully');
    }
}
