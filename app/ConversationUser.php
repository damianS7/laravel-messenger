<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversationUser extends Model
{
    public $timestamps = true;
    protected $table = "conversation_users";
    
    /**
     * Cada usuario puede estar asociado a varias conversaciones
     */
    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    /**
     * Cada usuario puede estar asociado a varias conversaciones
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
