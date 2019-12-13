<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Message;
use App\Conversation;
use App\MessageQueue;
use App\Http\Requests\MessageStoreRequest;

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
    public function store(MessageStoreRequest $request, $conversationId)
    {
        // ID del usuario logeado en la app
        $currentUserId = Auth::user()->id;
        
        // Creamos el mensaje usando el modelo
        $message = new Message;
        $message->conversation_id = $conversationId;
        $message->author_id = $currentUserId;
        $message->content = $request['message'];
        $message->save();
        
        // Buscamos el mensaje de nuevo para obtener toda la info
        $message = Message::where('id', $message->id)->first();
        
        // Buscamos la conversacion a la que pertenece el mensaje
        $conversation = Conversation::where('id', $conversationId)
            ->with(['participants'])->first();
        
        // Comprobamos quien es el destinatario del mensaje.
        // Para ello nos aseguramos que el ID del usuario es diferente al ID
        // del usuario logeado en la app.
        if ($conversation->participants[0]->id == $currentUserId) {
            $to_user_id = $conversation->participants[1]->id;
        } else {
            $to_user_id = $conversation->participants[0]->id;
        }

        // Enviamos el mensaje a la cola de mensajes
        MessageQueueController::toQueue($message, $to_user_id);
        return response()->json(['message' => $message], 200);
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