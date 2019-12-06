<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $timestamps = false;
    protected $table = "messages";

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

    // Devuelve los mensajes de una conversacion
    public function scopeConversationMessages($query, $conversationId)
    {
        $messages = $query->select(['messages.*',
        'users.name AS author_name', 'profiles.alias AS author_alias'])
        ->join('users', 'users.id', '=', 'messages.author_id')
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
        ->where('messages.conversation_id', $conversationId)
        ->orderBy('messages.sent_at', 'ASC');
        return $messages;
    }

    // Devuelve los mensajes de la cola
    public function scopeMessagesInQueue($query, $userId)
    {
        $messages = $query->select(['messages.*', 'users.name AS author_name',
        'profiles.alias AS author_alias', 'users.phone'])
        ->join('messages_queue', 'messages.id', '=', 'messages_queue.message_id')
        ->join('users', 'users.id', '=', 'messages.author_id')
        ->join('profiles', 'users.id', '=', 'profiles.user_id')
        ->where('messages_queue.to_user_id', $userId)
        ->orderBy('messages.sent_at', 'ASC');
        return $messages;
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