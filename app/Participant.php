<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
 * Participant es el modelo usado para llamar a los usuarios que participan
 * en una conversacion determinada. Podria decirse que Particpant es realmente un
 * alias de User
 */
class Participant extends Model
{
    public $timestamps = false;
    protected $table = "conversation_users";

    protected $fillable = [
        'conversation_id', 'user_id'
    ];

    // Cada participante esta asignado a una conversacion
    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    // Participant es un alias de user
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}