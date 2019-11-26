<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ConversationController;
use App\Conversation;
use App\User;
use App\Message;
use App\Contact;
use App\Profile;
use Auth;

class MessengerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // User ID
        $user_id = Auth::user()->id;
        
        // Conversaciones, Mensajes y perfiles
        $contacts = Contact::select(['contact_id'])->where('user_id', $user_id)->get();
        $data['contacts'] = array();
        foreach ($contacts as $index => $contact) {
            $data['contacts'][$index]['user_id'] = $contact->contact_id;

            $conversation_id = ConversationController::getConversationId($user_id, $contact->contact_id);
            //$messages = Message::where('conversation_id', $conversation_id)->get();
            $messages = ConversationController::fetchMessages($conversation_id);
            $data['contacts'][$index]['profile'] = ProfileController::getProfile($contact->contact_id);
            $data['contacts'][$index]['conversation']['conversation_id'] = $conversation_id;
            $data['contacts'][$index]['conversation']['messages'] = $messages;
        }
        //$contacts = $contacts->groupBy('user_id');
        return response()->json($data, 200);
    }
}