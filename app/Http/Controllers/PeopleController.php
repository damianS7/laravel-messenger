<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\People;
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
        $currentUserId = Auth::user()->id;
        // Obtenemos toda la gente de la app que no esten en nuestros contactos
        $people = People::appPeople($currentUserId)->get();
        return response()->json(['people' => $people], 200);
    }
}