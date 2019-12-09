<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversationUser extends Model
{
    public $timestamps = false;
    protected $table = "conversation_users";

    protected $fillable = [
        'conversation_id', 'user_id'
    ];

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
        return $this->hasMany('App\User');
    }
}