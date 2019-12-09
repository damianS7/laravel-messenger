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

    public function scopeFindConversationBetween($query, $userA_id, $userB_id)
    {
        //SELECT t1.* FROM conversations AS t1
        //LEFT JOIN conversation_users AS cu1
        //ON t1.id = cu1.conversation_id
        //LEFT JOIN conversation_users AS cu2
        //ON t1.id = cu2.conversation_id
        //WHERE cu1.user_id = 1 AND cu2.user_id = 2

        return $this->select(['t1.*'])
        ->from('conversations AS t1')
        ->leftJoin('conversation_users AS cuA', 't1.id', '=', 'cuA.conversation_id')
        ->leftJoin('conversation_users AS cuB', 't1.id', '=', 'cuB.conversation_id')
        ->where('cuA.user_id', '=', $userA_id)
        ->where('cuB.user_id', '=', $userB_id);
    }
    
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
    public function participants()
    {
        return $this->hasManyThrough(
            'App\User',
            'App\Participant',
            'conversation_id',
            'id',
            'id',
            'user_id',
        );
    }
}