<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Message;
use App\Conversation;
use App\MessageQueue;

class MessageController extends Controller
{
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
        $conversation = Conversation::find($conversationId);

        if ($conversation->user_a_id == $user_id) {
            $to_user_id = $conversation->user_b_id;
        } else {
            $to_user_id = $conversation->user_a_id;
        }
        //MessageQueueController::messageToQueue($message, $conversation);
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