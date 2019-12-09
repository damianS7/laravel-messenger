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
        $currentUserid = Auth::user()->id;
        $messages = Message::messagesInQueue($currentUserid)->get();
        MessageQueue::where('to_user_id', $currentUserid)->delete();
        return response()->json(['messages' => $messages], 200);
    }

    public static function messageToQueue($message, $conversation)
    {
        self::toQueue($message, $conversation->user_a_id);
        self::toQueue($message, $conversation->user_b_id);
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