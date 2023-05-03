<?php
namespace Database\Factories;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChatUserFactory extends Factory
{
    protected $model = ChatUser::class;

    public function definition()
    {
        return [
            'chat_id' =>rand(1,3),
            'user_id' => rand(1,2),
            'user_role' => array_rand(['user','admin']),
        ];
    }
}
