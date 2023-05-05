<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('chat.created.{id}', function ($user, $id) {
    return Chat::findOrFail($id)->users()->contains($user);
});
Broadcast::channel('chat.deleted.{id}', function ($user, $id) {
    return Chat::findOrFail($id)->users()->contains($user);
});
Broadcast::channel('chat.updated.{id}', function ($user, $id) {
    return Chat::findOrFail($id)->users()->contains($user);
});
Broadcast::channel('chat.admin.removed.{id}', function ($user, $id) {
    return Chat::findOrFail($id)->users()->contains($user);
});
Broadcast::channel('chat.admin.added.{id}', function ($user, $id) {
    return Chat::findOrFail($id)->users()->contains($user);
});
Broadcast::channel('chat.user.added.{id}', function ($user, $id) {
    return Chat::findOrFail($id)->users()->contains($user);
});
Broadcast::channel('chat.user.remove.{id}', function ($user, $id) {
    return Chat::findOrFail($id)->users()->contains($user);
});