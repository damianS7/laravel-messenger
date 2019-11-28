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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Id del usuario logeado
        $user_id = Auth::user()->id;
        // Obtenemos toda la gente de la app que no esten en nuestros contactos
        $people = User::select(['users.id', 'users.name'])
        ->leftJoin('contacts', 'users.id', '=', 'contacts.user_id')
        ->whereNull('contacts.id')
        ->orderBy('name', 'ASC')
        ->get();
        return response()->json(['people' => $people], 200);
    }
}