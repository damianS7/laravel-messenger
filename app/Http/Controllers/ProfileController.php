<?php

namespace App\Http\Controllers;

use App\Profile;
use Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Se necesita esta autentificado para llevar a cabo acciones
        // de CRUD sobre el perfil
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
        $profile = Profile::where('user_id', $user_id)->get();

        // Devolvemos el json con el perfil y codigo 200
        return response()->json(['profile' => $profile], 200);
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
    public function update(Request $request, $profile_id)
    {
        $user_id = Auth::user()->id;
        $profile = Profile::where(['id' => $profile_id ,'user_id' => $user_id])->first();
        $profile->name = $request['profile']['name'];
        $profile->info = $request['profile']['info'];
        $profile->save();
        
        // Devolvemos el json con el perfil y codigo 200
        return response()->json(['profile' => $profile], 200);
    }
}