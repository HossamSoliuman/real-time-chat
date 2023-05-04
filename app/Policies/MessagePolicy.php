<?php
namespace App\Policies;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function create(User $user, Chat $chat)
    {
        return $chat->users->contains($user);
    }

    public function restore(User $user, Message $message)
    {
        return $message->user_id == $user->id;
    }

    public function delete(User $user, Message $message)
    {
        return $message->user_id == $user->id;
    }

    public function softDelete(User $user, Message $message)
    {
        return $message->user_id == $user->id;
    }
}