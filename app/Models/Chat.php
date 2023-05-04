<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'chat_type',
        
    ];
    protected $hidden=[
        'pivot'
    ];
    public function usersWithRole()
    {
        return $this->belongsToMany(User::class)->withPivot('user_role');
    }
    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function messages(){
        return $this->hasMany(Message::class);
    }
}
