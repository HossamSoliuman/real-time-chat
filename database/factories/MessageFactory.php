<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'message' => fake()->sentence(),
            'user_id' => rand(1, 2),
            'chat_id' => rand(1, 3),
            'created_at' => Carbon::createFromTimestamp(mt_rand(strtotime('-1 year'), time()))->toDateTimeString()
        ];
    }
}
