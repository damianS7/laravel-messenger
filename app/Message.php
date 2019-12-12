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