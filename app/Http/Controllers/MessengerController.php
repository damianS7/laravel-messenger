<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\PeopleController;

class MessengerController extends Controller
{
    public function __construct()
    {
        // Se necesita esta autentificado para llevar a cabo acciones
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Userdata
        $data['app_user'] = ProfileController::getProfile();
        // Contacts
        $data['contacts'] = ContactController::getContacts();
        // People
        $data['people'] = PeopleController::getPeople();
        // Conversaciones & Mensajes
        $data['conversations'] = ConversationController::getConversations();

        return response()->json($data, 200);
    }
}