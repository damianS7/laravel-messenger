<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use App\User;
use App\Message;
use App\MessageQueue;
use Auth;

class ConversationController extends Controller
{
    public function __construct()
    {
        // Se necesita esta autentificado para llevar a cabo acciones
        // de CRUD sobre las notas
        $this->middleware('auth');
    }

    public static function fetchMessages($conversation_id)
    {
        $messages = Message::select(['messages.*', 'users.name', 'profiles.alias'])
        ->join('users', 'users.id', '=', 'messages.author_id')
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
        ->where('messages.conversation_id', $conversation_id)
        ->orderBy('messages.sent_at', 'ASC')
        ->get();
        
        return $messages;
    }

    public static function getConversationId($user_id_a, $user_id_b)
    {
        // Comparamos los ID y el menor lo pasamos como user_id_a
        if ($user_id_a < $user_id_b) {
            $lesser_id = $user_id_a;
            $bigger_id = $user_id_b;
        } else {
            $lesser_id = $user_id_b;
            $bigger_id = $user_id_a;
        }
        
        $conversation = Conversation::where(
            [
                'user_a_id' => $lesser_id,
                'user_b_id' => $bigger_id
            ]
        )->first('id');
        
        // Si no existe ninguna conversacion con este id, la crearemos
        if ($conversation === null) {
            $conversation = new Conversation();
            $conversation->user_a_id = $user_id_a;
            $conversation->user_b_id = $user_id_b;
            $conversation->save();
        }
        
        return $conversation->id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Validacion isnumeric >= 1
        // ------

        // SELECT messages.* FROM messages
        // INNER JOIN conversations ON conversations.id = messages.conversation_id
        // WHERE conversations.user_a_id = 1 OR conversations.user_b_id = 1

        // User ID
        $user_id = Auth::user()->id;
        
        $messages = Message::select(['messages.*', 'users.name'])
        ->join('users', 'messages.author_id', '=', 'users.id')
        ->join('conversations', 'conversations.id', '=', 'messages.conversation_id')
        ->where(['conversations.user_a_id' => $user_id, 'conversations.user_b_id' => $user_id,])
        ->orderBy('sent_at', 'ASC')
        ->get();
        return response()->json(['conversation' => '', 'messages' => $messages], 200);
    }

    public function createConversation()
    {
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
    }

    /**
     * Agrega un mensaje nuevo a una conversacion
     *
     * @return \Illuminate\Http\Response
     */
    public function storeMessage(Request $request, $conversation_id)
    {
        // User ID
        $user_id = Auth::user()->id;

        $message = new Message();
        $message->conversation_id = $conversation_id;
        $message->author_id = $user_id;
        $message->content = $request['message'];
        $message->save();

        MessageQueueController::messageToQueue($message);
    }

    public function fetchLastMessages(Request $request)
    {
        $user_id = Auth::user()->id;
        
        $messages = Message::select(['messages.*', 'users.name', 'profiles.alias'])
        ->join('messages_queue', 'messages.id', '=', 'messages_queue.message_id')
        ->join('users', 'users.id', '=', 'messages.author_id')
        ->join('profiles', 'users.id', '=', 'profiles.user_id')
        ->where('messages_queue.to_user_id', $user_id)
        ->orderBy('messages.sent_at', 'ASC')
        ->get();
        
        MessageQueue::where('to_user_id', $user_id)->delete();
        return response()->json(['messages' => $messages], 200);
    }
}