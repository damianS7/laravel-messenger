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
        // ID de usuario que agregara el contacto
        $user_id = Auth::user()->id;

        // Agrega un contacto
        $contact = new Contact;
        $contact->user_id = $user_id;
        $contact->contact_id = $request['user_id'];
        $contact->save();

        // Buscamos Conversation existente con los dos IDs de usuario
        $conversation = Conversation::findConversationBetween(
            $contact->user_id,
            $contact->contact_id
        )->with(['participants', 'messages'])->first();

        // Si no la encontramos ...
        if ($conversation === null) {
            // Creamos una nueva conversacion
            $conversation = Conversation::create();
            
            // Insertamos a los usuarios participantes en la nueva conversacion
            Participant::create(
                array(
                    'conversation_id' => $conversation->id,
                    'user_id' => $contact->user_id
                )
            );

            Participant::create(
                array(
                    'conversation_id' => $conversation->id,
                    'user_id' => $contact->contact_id
                )
            );

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

        // Elimina un contacto
        $contact = Contact::where(
            [
                'user_id' => $currentUserId,
                'contact_id' => $contactId
            ]
        )->first();
        $contact->delete();

        // Elimina el contacto
        return response()->json(['contact' => $contact], 204);
    }
}