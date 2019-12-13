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
}