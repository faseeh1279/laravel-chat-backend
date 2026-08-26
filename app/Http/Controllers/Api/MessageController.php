<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation; 

class MessageController extends Controller
{
    /**
     * Get messages for a conversation.
     */
    public function index(
        Request $request,
        Conversation $conversation
    ) {
        $user = $request->user();

        $isParticipant = $conversation->users()
            ->where('users.id', $user->id)
            ->exists();

        if (!$isParticipant) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $messages = $conversation->messages()
            ->with('user:id,name')
            ->oldest()
            ->get();

        return response()->json($messages);
    }

    /**
     * Send a message.
     */
    public function store(
        Request $request,
        Conversation $conversation
    ) {
        $user = $request->user();

        /*
         * Make sure sender belongs to conversation.
         */
        $isParticipant = $conversation->users()
            ->where('users.id', $user->id)
            ->exists();

        if (!$isParticipant) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        $message->load('user:id,name');

        return response()->json([
            'message' => 'Message sent successfully.',
            'data' => $message,
        ], 201);
    }
}
