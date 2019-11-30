<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    public function __construct()
    {
        // Se necesita esta autentificado para llevar a cabo acciones
        $this->middleware('auth');
    }

    public static function getPeople()
    {
        // Id del usuario logeado
        $user_id = Auth::user()->id;
        
        return User::select(['users.id', 'users.name', 'users.phone', 'users.email',
        'profiles.alias', 'profiles.info', 'profiles.avatar',
        'users.created_at AS member_since'])
        ->leftJoin('contacts', 'users.id', '=', 'contacts.contact_id')
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->whereNull('contacts.id')
        ->where('users.id', '!=', $user_id)
        ->orderBy('name', 'ASC')
        ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtenemos toda la gente de la app que no esten en nuestros contactos
        $people = self::getContacts();
        return response()->json(['people' => $people], 200);
    }
}