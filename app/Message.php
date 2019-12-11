<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $timestamps = false;
    protected $table = "messages";

    protected $hidden = [
        'laravel_through_key'
    ];

    // Devuelve los mensajes de una conversacion anteriores a una fecha.
    public function scopeConversationMessagesBeforeDate($query, $conversationId, $date)
    {
    }

    // Devuelve un mensaje con toda la informacion relacionada
    public function scopeMessageInfo($query, $messageId)
    {
        $message = $query->select(['messages.*',
        'users.name AS author_name', 'profiles.alias AS author_alias'])
        ->join('users', 'users.id', '=', 'messages.author_id')
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->where('messages.id', $messageId)
        ->orderBy('messages.sent_at', 'ASC');
        return $message;
    }

    /**
     * Los mensajes pertenecen a una conversacion
     */
    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    /**
     * Los mensajes pertenecen a un usuario
     */
    public function author()
    {
        return $this->belongsTo('App\User');
    }
}