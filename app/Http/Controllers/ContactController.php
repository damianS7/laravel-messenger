<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;
use App\User;
use Auth;
use DB;
use App\Http\Controllers\ConversationController;

class ContactController extends Controller
{
    public function __construct()
    {
        // Se necesita esta autentificado para usar este controlador
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // ID de usuario que necesita los contactos
        $user_id = Auth::user()->id;
    
        // Obtenemos los contactos del usuario junto con sus perfiles
        $user_contacts = Contact::select(['users.id AS user_id', 'users.name',
            'users.phone','users.email', 'users.created_at AS member_since',
            'conversations.id AS conversation_id',
            'profiles.alias', 'profiles.info', 'profiles.avatar'])
            ->from('users')
            ->leftJoin('contacts', 'users.id', '=', 'contacts.contact_id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->leftJoin('conversations', 'conversations.user_a_id', '=', DB::raw('LEAST(contacts.user_id, contacts.contact_id) AND conversations.user_b_id = GREATEST(contacts.user_id, contacts.contact_id)'))
            ->where('contacts.user_id', $user_id)
            ->get();

        // Devolvemos el json con las notas y codigo 200
        return response()->json(['contacts' => $user_contacts], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // ID de usuario que agregara el contacto
        $user_id = Auth::user()->id;

        // Agrega un contacto
        $contact = new Contact;
        $contact->user_id = $user_id;
        $contact->contact_id = $request['user_id'];
        $contact->save();

        $conversation = '';

        // Comprobamos si existe una conversacion previa entre estos dos usuarios
        if (ConversationController::getConversation($user_id, $contact->contact_id) === null) {
            $conversation_id = ConversationController::createConversation(
                $user_id,
                $contact->contact_id
            );
            $conversation = ConversationController::getConversationJson($conversation_id);
        }

        // Obtenemos los contactos del usuario junto con sus perfiles
        $data_contact = Contact::select(['users.id AS user_id', 'users.name',
            'users.phone','users.email', 'users.created_at AS member_since',
            'conversations.id AS conversation_id',
            'profiles.alias', 'profiles.info', 'profiles.avatar'])
            ->from('users')
            ->leftJoin('contacts', 'users.id', '=', 'contacts.contact_id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->leftJoin('conversations', 'conversations.user_a_id', '=', DB::raw('LEAST(contacts.user_id, contacts.contact_id) AND conversations.user_b_id = GREATEST(contacts.user_id, contacts.contact_id)'))
            ->where('users.id', $contact->contact_id)
            ->first();

        // Devolvemos el json con los datos del nuevo contacto
        return response()->json(['contact' => $data_contact,
            'conversation' => $conversation], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($contact_id)
    {
        // Id del usuario que va a eliminar contactos de su "agenda"
        $user_id = Auth::user()->id;

        // Elimina un contacto
        $contact = Contact::where(['user_id' => $user_id,'contact_id' => $contact_id])->first();
        $contact->delete();

        // Elimina la conversacion
        return response()->json([$contact], 204);
    }
}