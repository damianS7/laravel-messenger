<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class People extends Model
{
    public $timestamps = false;
    protected $table = "users";

    // Gente disponible en la app para agregar a contactos
    public function scopeAppPeople($query, $currentUserId)
    {
        return $query->select(['users.id', 'users.name', 'users.phone', 'users.email',
        'profiles.alias', 'profiles.info', 'profiles.avatar',
        'users.created_at AS member_since'])
        //->leftJoin('contacts', 'users.id', '=', 'contacts.contact_id') // AND users.id = $currentUserId
        ->leftJoin('contacts', 'users.id', '=', DB::raw("contacts.contact_id AND contacts.user_id ='$currentUserId'"))
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->whereNull('contacts.id')
        ->where('users.id', '!=', $currentUserId)
        ->orderBy('name', 'ASC');
    }
}
