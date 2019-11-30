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
        $this->middleware('auth');
    }

    public static function fetchMessages($conversation_id)
    {
        $messages = Message::select(['messages.*',
        'users.name AS author_name',
        'profiles.alias AS author_alias'])
        ->join('users', 'users.id', '=', 'messages.author_id')
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
        ->where('messages.conversation_id', $conversation_id)
        ->orderBy('messages.sent_at', 'ASC')
        ->get();
        
        return $messages;
    }

    public static function getConversation($user_id_a, $user_id_b)
    {
        $conversation = Conversation::where(['user_a_id' => min($user_id_a, $user_id_b),
            'user_b_id' => max($user_id_a, $user_id_b)])->first('id');
        
        // Si no existe ninguna conversacion con este id, la crearemos
        if ($conversation === null) {
            return null;
        }
        
        return $conversation;
    }

    public static function getConversationById($conversation_id)
    {
        $conversation = Conversation::where(['id' => $conversation_id])->first();
        
        // Si no existe ninguna conversacion con este id, la crearemos
        if ($conversation === null) {
            return null;
        }
        
        return $conversation;
    }

    public static function createConversation($user_id_a, $user_id_b)
    {
        $conversation = new Conversation();
        $conversation->user_a_id = min($user_id_a, $user_id_b);
        $conversation->user_b_id = max($user_id_a, $user_id_b);
        $conversation->save();
        return $conversation->id;
    }

    
    /**
     * Agrega un mensaje nuevo a una conversacion
     *
     * @return \Illuminate\Http\Response
     */
    public static function store($userA, $userB)
    {
        // Crea la conversacion(vacia) entre los dos usuarios
        $conversation = new Conversation;
        $conversation->user_a_id = $user_id;
        $conversation->user_b_id = $user_id;
        $conversation->save();
    }

    public static function getConversationJson($conversation_id)
    {
        // Para cada conversacion obtenemos sus mensajes
        $messages = self::fetchMessages($conversation_id);
        return array(
            'id' => $conversation_id,
            'messages' => $messages
        );
    }

    public static function getConversations()
    {
        $user_id = Auth::user()->id;
        
        // Comprobamos que la cola de mensajes este limpia para que
        // no se dupliquen mensajes.
        MessageQueue::where('to_user_id', $user_id)->delete();

        // Ids de las conversaciones del usuario
        $conversations = Conversation::where('user_a_id', $user_id)
        ->orWhere('user_b_id', $user_id)->get();

        $data = array();
        // Para cada conversacion obtenemos sus mensajes
        foreach ($conversations as $index => $conversation) {
            $messages = self::fetchMessages($conversation->id);
            $data[$index] = array(
                'id' => $conversation->id,
                'messages' => $messages
            );
        }
        return $data;
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

        
        // User ID
        $user_id = Auth::user()->id;
        
        // Comprobamos que la cola de mensajes este limpia para que
        // no se dupliquen mensajes.
        MessageQueue::where('to_user_id', $user_id)->delete();

        // Ids de las conversaciones del usuario
        $conversations = Conversation::where('user_a_id', $user_id)
        ->orWhere('user_b_id', $user_id)->get();

        $data['conversations'] = array();
        // Para cada conversacion obtenemos sus mensajes
        foreach ($conversations as $index => $conversation) {
            $messages = self::fetchMessages($conversation->id);
            $data['conversations'][$index] = array(
                'id' => $conversation->id,
                'messages' => $messages
            );
        }

        return response()->json($data, 200);
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

        $message = new Message;
        $message->conversation_id = $conversation_id;
        $message->author_id = $user_id;
        $message->content = $request['message'];
        $message->save();

        $conversation = self::getConversationById($conversation_id);

        MessageQueueController::messageToQueue($message, $conversation);
    }

    public function fetchLastMessages(Request $request)
    {
        $user_id = Auth::user()->id;
        $messages = Message::select(['messages.*', 'users.name AS author_name',
        'profiles.alias AS author_alias', 'users.phone'])
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