<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MessageQueue;
use App\Conversation;
use App\User;
use App\Message;
use Auth;

class MessageQueueController extends Controller
{
    public function __construct()
    {
        // Se necesita esta autentificado para llevar a cabo acciones
        $this->middleware('auth');
    }

    public function index()
    {
        // ID del usuario logeado en la app
        $currentUserid = Auth::user()->id;
        
        // Buscamos los mensajes que el usuario aun no ha recibido
        $messages = Auth::user()->messagesInQueue()
        ->with(['conversation.participants'])
        ->get();

        // Eliminamos los mensajes de la DB
        MessageQueue::where('to_user_id', $currentUserid)->delete();

        // Enviamos los mensajes al usuario
        return response()->json(['messages' => $messages], 200);
    }
    
    // Agrega un mensaje a la cola
    public static function toQueue($message, $to_user_id)
    {
        $queueMessage = new MessageQueue;
        $queueMessage->to_user_id = $to_user_id;
        $queueMessage->message_id = $message->id;
        $queueMessage->save();
    }
}