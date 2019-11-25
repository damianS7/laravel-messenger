<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use App\User;
use App\Message;
use Auth;

class ConversationController extends Controller
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
    public function index($contact_id)
    {
        // Validacion isnumeric >= 1
        // ------

        // User ID
        $user_id = Auth::user()->id;
        
        // Comparamos los ID y el menor lo pasamos como user_id_a
        if ($user_id < $contact_id) {
            $user_id_a = $user_id;
            $user_id_b = $contact_id;
        } else {
            $user_id_a = $contact_id;
            $user_id_b = $user_id;
        }
        
        $conversation = Conversation::where(
            [
                'user_a_id' => $user_id_a,
                'user_b_id' => $user_id_b
            ]
        )->first('id');
        
        // Si no existe ninguna conversacion con este id, la crearemos
        if ($conversation === null) {
            $conversation = new Conversation();
            $conversation->user_a_id = $user_id_a;
            $conversation->user_b_id = $user_id_b;
            $conversation->save();
        }
        
        $conversation_id = $conversation->id;

        $messages = Message::select(['messages.*', 'users.name'])
        ->join('users', 'messages.author_id', '=', 'users.id')
        ->where(['messages.conversation_id' => $conversation_id])
        ->orderBy('sent_at', 'ASC')
        ->get();
        return response()->json(['conversation' => $conversation_id, 'messages' => $messages], 200);
    }

    /**
     * Agrega un mensaje nuevo a una conversacion
     *
     * @return \Illuminate\Http\Response
     */
    public function appendMessage(Request $request, $conversation_id)
    {
    }

    public function fetchLastMessages(Request $request, $conversation_id, $after_date)
    {
        $messages = Message::where(['conversation_id' => $conversation_id])
        ->whereDate('sent_at', '>', $after_date)
        ->orderBy('sent_at', 'ASC')->get();
        return response()->json(['messages' => $messages], 200);
    }
}