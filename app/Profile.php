<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public $timestamps = false;
    protected $table = "profiles";

    // Devuelve toda la informacion de un usuario
    public function scopeFullProfile($query, $userId)
    {
        return $query->select(['users.id AS user_id', 'users.name',
        'profiles.id', 'profiles.alias', 'profiles.avatar', 'profiles.info',
        'users.phone', 'users.email', 'users.created_at AS member_since'])
        ->from('users')
        ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id')
        ->where('users.id', $userId)->first();
    }
}
