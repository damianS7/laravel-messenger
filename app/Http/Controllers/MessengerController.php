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
        $data['app_user'] = Profile::fullProfile($currentUserId);
        // Contacts
        $data['contacts'] = Contact::userContacts($currentUserId)->get();
        // People
        $data['people'] = People::appPeople($currentUserId)->get();
        
        // Comprobamos que la cola de mensajes este limpia para que
        // no se dupliquen mensajes.
        MessageQueue::where('to_user_id', $currentUserId)->delete();

        $data['conversations'] = array();
        // Para cada conversacion obtenemos sus mensajes
        
        // Conversations del usuario
        $conversations = Conversation::userConversations($currentUserId)->get();

        // Conversaciones & Mensajes
        foreach ($conversations as $index => $conversation) {
            $messages = Message::conversationMessages($conversation->id)->get();
            $data['conversations'][$index] = array(
                'id' => $conversation->id,
                'user_a_id' => $conversation->user_a_id,
                'user_b_id' => $conversation->user_b_id,
                'messages' => $messages
            );
        }

        return response()->json($data, 200);
    }
}