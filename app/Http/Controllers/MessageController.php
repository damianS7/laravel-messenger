<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Message;
use App\Conversation;
use App\MessageQueue;

class MessageController extends Controller
{
    public function __construct()
    {
        // Se necesita esta autentificado para llevar a cabo acciones
        $this->middleware('auth');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $conversationId)
    {
        // User ID
        $user_id = Auth::user()->id;
        
        $message = new Message;
        $message->conversation_id = $conversationId;
        $message->author_id = $user_id;
        $message->content = $request['message'];
        $message->save();
        
        $messageData = Message::messageInfo($message->id)->first();
        $conversation = Conversation::where('id', $conversationId)->with('participants')->first();
        
        if ($conversation->participants[0]->id == $user_id) {
            $to_user_id = $conversation->participants[1]->id;
        } else {
            $to_user_id = $conversation->participants[0]->id;
        }

        MessageQueueController::toQueue($message, $to_user_id);
        return response()->json($messageData, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}