<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    // Informacion del usuario para la app
    public function scopeInfo($query)
    {
        return $this->select(['users.id', 'users.name', 'users.email', 'users.phone', 'users.created_at AS member_since']);
    }

    /**
     * Perfil de usuario
     */
    public function profile()
    {
        return $this->hasOne('App\Profile', 'user_id', 'id');
    }

    /**
     * Contactos del usuario
     */
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

    /**
     * Conversaciones en las que participa el usuario
     */
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
}