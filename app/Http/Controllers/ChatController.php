<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Agents\EduHelperAgent;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->session()->has('chat_session_id')) {
            $request->session()->put('chat_session_id', Str::uuid());
        }

        return view('chat');
    }

    public function send(Request $request, EduHelperAgent $agent)
    {
        $request->validate([
            'message' => 'required|string|max:300'
        ]);

        $sessionId = $request->session()->get('chat_session_id');

        $reply = $agent->respond($request->message, $sessionId);

        return response()->json([
            'reply' => $reply
        ]);
    }
}
