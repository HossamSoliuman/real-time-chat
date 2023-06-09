<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return 'hello';
});
Route::post('login', [AuthenticationController::class, 'login']);
Route::post('register', [AuthenticationController::class, 'register']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResources([
        'users' => UserController::class,
    ]);
    Route::apiResource('messages', MessageController::class);
    
    Route::post('/chats/{chat}/users', [ChatController::class, 'addParticipants']);
    Route::delete('/chats/{chat}/users', [ChatController::class, 'removeParticipant']);
    Route::post('/chats/{chat}/admins', [ChatController::class, 'addAdmin']);
    Route::delete('/chats/{chat}/admins', [ChatController::class, 'removeAdmin']);

    Route::put('/messages/{message}/restore', [MessageController::class, 'restore'])->name('messages.restore');
    Route::delete('/messages/{message}/delete', [MessageController::class, 'softDelete'])->name('messages.softDelete');

    //users routes
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);

});
