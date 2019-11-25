<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\User;
use Auth;

class ContactController extends Controller
{
    public function __construct()
    {
        // Se necesita esta autentificado para llevar a cabo acciones
        // de CRUD sobre las notas
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        
        // Contactos disponibles en la app (Para que el usuario agrege)
        // Importante que se muestren todos los usuarios excepto el nuestro
        // y los que ya tenemos agregados a contactos
        $contacts = User::where('id', '!=', $user_id)->orderBy('name', 'ASC')->get();

        // Obtenemos los contactos del usuario
        $user_contacts = Contact::select(['users.id', 'users.name', 'users.phone'])
            ->join('users', 'contacts.contact_id', '=', 'users.id')
            ->where('contacts.user_id', $user_id)
            ->get();

        // Devolvemos el json con las notas y codigo 200
        return response()->json(['users' => $contacts, 'user_contacts' => $user_contacts], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}