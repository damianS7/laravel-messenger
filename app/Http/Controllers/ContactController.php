<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\User;
use App\Http\Requests\ContactStoreRequest;
use Auth;
use DB;

use App\Conversation;
use App\Participant;

class ContactController extends Controller
{
    public function __construct()
    {
        // Se necesita esta autentificado para usar este controlador
        $this->middleware('auth');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactStoreRequest $request)
    {
        // ID de usuario logeado en la app que agregara el contacto
        $currentUserId = Auth::user()->id;

        // Preparamos el modelo del nuevo contacto
        $contact = new Contact;
        $contact->user_id = $currentUserId;
        $contact->contact_id = $request['user_id'];
        $contact->save();

        // Buscamos Conversation existente con los dos IDs de usuario
        // Puesto que es posible que hayan sido contactos anteriormente.
        $conversation = Conversation::findConversationBetween(
            $contact->user_id,
            $contact->contact_id
        )->with(['participants', 'messages'])->first();

        // Si no existe una conversacion anterior entre estos dos usuarios
        if ($conversation === null) {
            // Creamos una nueva conversacion
            $conversation = Conversation::create();
            
            // Insertamos a los usuarios participantes en la nueva conversacion

            // Participante 1
            Participant::create(
                array(
                    'conversation_id' => $conversation->id,
                    'user_id' => $contact->user_id
                )
            );

            // Participante 2
            Participant::create(
                array(
                    'conversation_id' => $conversation->id,
                    'user_id' => $contact->contact_id
                )
            );

            // Buscamos de nuevo la conversacion
            $conversation = Conversation::where('id', $conversation->id)
            ->with(['participants', 'messages'])->first();
        }

        // Buscamos al contacto
        $userContact = User::info()->where('id', $contact->contact_id)
        ->with('profile')->first();
        
        // Devolvemos el json con los datos del nuevo contacto y la conversacion
        return response()->json(['contact' => $userContact,
            'conversation' => $conversation], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($contactId)
    {
        // Id del usuario que va a eliminar contactos de su "agenda"
        $currentUserId = Auth::user()->id;

        // Buscamos el contacto
        $contact = Contact::where(
            [
                'user_id' => $currentUserId,
                'contact_id' => $contactId
            ]
        )->first();
        
        // Y Eliminamos el contacto
        $contact->delete();

        // Elimina el contacto
        return response()->json(['contact' => $contact], 204);
    }
}