<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Contact extends Model
{
    public $timestamps = false;
    protected $table = "contacts";

    // Devuelve los contactos de un usuario
    public function scopeUserContacts($query, $user_id)
    {
        $user_contacts = $query->select(['users.id AS user_id', 'users.name',
            'users.phone','users.email', 'users.created_at AS member_since',
            'conversations.id AS conversation_id',
            'profiles.alias', 'profiles.info', 'profiles.avatar'])
            ->from('users')
            ->leftJoin('contacts', 'users.id', '=', 'contacts.contact_id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->leftJoin('conversations', 'conversations.user_a_id', '=', DB::raw('LEAST(contacts.user_id, contacts.contact_id) AND conversations.user_b_id = GREATEST(contacts.user_id, contacts.contact_id)'))
            ->where('contacts.user_id', $user_id);
        return $user_contacts;
    }

    // Devuelve la informacion de usuario de un contacto
    public function scopeContactInfo($query, $contact_id)
    {
        return $query->select(['users.id AS user_id', 'users.name',
            'users.phone','users.email', 'users.created_at AS member_since',
            'conversations.id AS conversation_id',
            'profiles.alias', 'profiles.info', 'profiles.avatar'])
            ->from('users')
            ->leftJoin('contacts', 'users.id', '=', 'contacts.contact_id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->leftJoin(
                'conversations',
                'conversations.user_a_id',
                '=',
                DB::raw('LEAST(contacts.user_id, contacts.contact_id) 
                AND conversations.user_b_id = 
                GREATEST(contacts.user_id, contacts.contact_id)')
            )
            ->where('users.id', $contact_id);
    }
}