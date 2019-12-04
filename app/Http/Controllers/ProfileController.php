<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use Auth;

class ProfileController extends Controller
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
        $profile = Profile::fullProfile($currentUserId);
        // Devolvemos el json con el perfil y codigo 200
        return response()->json(['profile' => $profile], 200);
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
        $profile = Profile::where(['id' => $profile_id , 'user_id' => $user_id])->first();
        $profile->alias = $request['profile']['alias'];
        $profile->info = $request['profile']['info'];
        $profile->avatar = $request['profile']['avatar'];
        $profile->save();
        
        // Devolvemos el json con el perfil y codigo 200
        return response()->json(['profile' => $profile], 200);
    }
}