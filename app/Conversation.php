<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public $timestamps = false;
    protected $table = "conversations";

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'laravel_through_key'
    ];
    
    /**
    * Mensajes de la conversation
    */
    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    /**
    * Usuarios de la conversacion
    */
    public function users()
    {
        return $this->hasManyThrough(
            'App\User',
            'App\ConversationUser',
            'conversation_id',
            'id',
            'id',
            'user_id',
        );
    }
}