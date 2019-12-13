<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'laravel_through_key'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Gente disponible en la app para agregar a contactos.
    // La consulta devuelve usuarios que NO son contactos del usuario.
    public function scopePeople($query, $currentUserId)
    {
        return $this->select(['users.id', 'users.name', 'users.phone', 'users.email',
        'profiles.alias', 'profiles.info', 'profiles.avatar',
        'users.created_at AS member_since'])
        ->leftJoin('contacts', 'users.id', '=', DB::raw("contacts.contact_id AND contacts.user_id ='$currentUserId'"))
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->whereNull('contacts.id')
        ->where('users.id', '!=', $currentUserId)
        ->orderBy('name', 'ASC');
    }

    // Informacion del usuario para la app
    public function scopeInfo($query)
    {
        return $this->select(['users.id', 'users.name', 'users.email', 'users.phone', 'users.created_at AS member_since']);
    }

    // Perfil del usuario
    public function profile()
    {
        return $this->hasOne('App\Profile', 'user_id', 'id');
    }

    // Contactos del usuario (User)
    public function contacts()
    {
        return $this->hasManyThrough(
            'App\User',
            'App\Contact',
            'user_id',
            'id',
            'id',
            'contact_id'
        )->select(['users.id', 'users.name', 'users.phone', 'users.email',
        'users.created_at AS member_since']);
    }

    // Conversaciones en las que participa el usuario
    public function conversations()
    {
        return $this->hasManyThrough(
            'App\Conversation',
            'App\Participant',
            'user_id',
            'id',
            'id',
            'conversation_id'
        );
    }

    // Mensajes destinados al usuario
    public function messagesInQueue()
    {
        return $this->hasManyThrough(
            'App\Message',
            'App\MessageQueue',
            'to_user_id',
            'id',
            'id',
            'message_id'
        );
    }
}