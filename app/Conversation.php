<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public $timestamps = false;
    protected $table = "conversations";

    // Devuelve los datos de una conversacion entre dos usuarios o null
    public function scopeUsersConversation($query, $user_id_a, $user_id_b)
    {
        $conversation = $query->where(
            ['user_a_id' => min($user_id_a, $user_id_b),
            'user_b_id' => max($user_id_a, $user_id_b)]
        )->first();
        
        return $conversation;
    }

    // Devuelve las conversaciones pertenecientes a un usuario junto con sus mensajes
    public function scopeUserConversations($query, $userId)
    {
        $conversations = Conversation::where('user_a_id', $userId)
        ->orWhere('user_b_id', $userId);
        return $conversations;
    }
}