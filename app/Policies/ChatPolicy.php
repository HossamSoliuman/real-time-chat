<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the chat.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chat  $chat
     * @return mixed
     */
    public function view(User $user, Chat $chat)
    {
        return $chat->users->contains($user);
    }

    /**
     * Determine whether the user can update the chat.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chat  $chat
     * @return mixed
     */
    public function update(User $user, Chat $chat)
    {
        return $chat->users->contains($user) && $chat->users()->wherePivot('user_role', 'admin')->exists();
    }

    /**
     * Determine whether the user can delete the chat.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chat  $chat
     * @return mixed
     */
    public function delete(User $user, Chat $chat)
    {
        return $chat->users->contains($user) && $chat->users()->wherePivot('user_role', 'admin')->exists();
    }
}