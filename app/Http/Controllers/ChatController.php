<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatStoreRequest;
use App\Http\Resources\ChatMessagesResource;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     *
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(ChatStoreRequest $request)
    {
        $validatedData = $request->validated();

        $chat = Chat::create([
            'name' => $validatedData['name'],
            'chat_type' => $validatedData['chat_type']
        ]);

        $chat->users()->attach($validatedData['users']);

        return $this->successResponse(['chat_id'=>$chat->id], 'Chat created successfully', 201);
    }

    /**`
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        $authUser = auth()->user();
        if (!$chat->users->contains($authUser)) {
            return $this->errorResponse('Unauthorized', 401);
        }
    
        $chatMessages = $chat->load(['messages' => function($query) {
            $query->where('deleted_at', null)->with('user');
        }]);
        
        return $this->successResponse(new ChatMessagesResource($chatMessages));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
