<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Profile;
use App\Http\Requests\ProfileUpdateRequest;
use Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Se necesita esta autentificado para llevar a cabo acciones
        $this->middleware('auth');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileUpdateRequest $request, $profile_id)
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