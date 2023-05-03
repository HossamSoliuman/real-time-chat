<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    

    public function toArray($request)
    {
        $is_deleted = $this->deleted_at == null ? 0 : 1;
        $date = Carbon::parse($this->created_at)->diffForHumans();
        $created_at = Carbon::parse($this->created_at)->format('Y m d \a\t h:m A');
        return [
            'message' => $this->message,
            'date' => $date,
            'created_at' => $created_at,
            'is_deleted' => $is_deleted,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
        ];
    }
}
