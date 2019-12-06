<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public $timestamps = false;
    protected $table = "profiles";
    
    /**
     * Cada perfil esta asoaciado a un Usuario.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
