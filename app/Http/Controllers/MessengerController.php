<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\PeopleController;
use App\Contact;
use App\People;
use App\Profile;
use App\Message;
use App\MessageQueue;
use App\Conversation;
use App\User;
use Auth;

class MessengerController extends Controller
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
        // ID de usuario que necesita los datos
        $currentUserId = Auth::user()->id;
        
        // Userdata
        //$data['app_user'] = Profile::fullProfile($currentUserId);
        $data['app_user'] = User::info()->where('id', $currentUserId)->with('profile')->first();
        
        // Contacts
        //$data['contacts'] = Contact::userContacts($currentUserId)->get();
        $data['contacts'] = User::where('id', $currentUserId)->first()->contacts()->with('profile')->get();
        
        // People
        $data['people'] = People::people($currentUserId)->with('profile')->get();

        // Comprobamos que la cola de mensajes este limpia para que
        // no se dupliquen mensajes.
        MessageQueue::where('to_user_id', $currentUserId)->delete();

        // Conversaciones
        $data['conversations'] = User::where('id', $currentUserId)->first()->conversations()
        ->with(['participants', 'messages'])->get();

        // Para cada conversacion obtenemos sus mensajes
        return response()->json($data, 200);
    }
}