<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contact extends Model
{
    public $timestamps = false;
    protected $table = "contacts";

    /**
     * Usuario propietario del contacto
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Contacto (User)
     */
    public function contact()
    {
        return $this->belongsTo('App\User', 'contact_id', 'id')->select(['users.id', 'users.name', 'users.phone', 'users.email',
        'users.created_at AS member_since']);
    }

    /**
     * La conversacion entre ambos usuarios
     */
    public function conversation()
    {
        //SELECT t1.conversation_id FROM `conversation_users` AS t1
        //LEFT JOIN conversation_users AS t2
        //ON t1.conversation_id = t2.conversation_id
        //WHERE t1.user_id = 1 AND t2.user_id = 2
        return $this->hasManyThrough(
            'App\Participant AS t1',
            'App\Participant AS t2',
            'user_id1',
            't1.conversation_id',
            'id3',
            't2.conversation_id'
        );
    }
}