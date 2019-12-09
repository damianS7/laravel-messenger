<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
 * Participant es el modelo usado para determinar a los usuarios que participan
 * en una conversacion determinada.
 */
class Participant extends Model
{
    public $timestamps = false;
    protected $table = "conversation_users";

    protected $fillable = [
        'conversation_id', 'user_id'
    ];

    /**
     * Participante esta asociado a una conversacion
     */
    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    /**
     * Cada Participante es un usuario
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}