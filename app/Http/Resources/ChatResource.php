<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $last_message = $this->messages->isEmpty() ? 'There are no messages yet.' : $this->messages->last()->message;

        return [
            'id' =>$this->id,
            'chat_name' => $this->name,
            'type' => $this->chat_type,
            'last_message' => $last_message
        ];
    }
}
