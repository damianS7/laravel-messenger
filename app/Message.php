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

    // Conversacion a la que pertenece el mensaje
    public function conversation()
    {
        return $this->belongsTo('App\Conversation');
    }

    // Author del mensaje
    public function author()
    {
        return $this->belongsTo('App\User');
    }
}