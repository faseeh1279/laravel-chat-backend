<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation; 
use App\Services\MessageService;
use App\Http\Resources\MessageResource;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function __construct(
        private MessageService $messageService
    ) {
    }

    /**
     * Get messages for a conversation.
     */
    public function index(
        Request $request,
        Conversation $conversation
    ) {
        $messages = $this->messageService->getMessages(
            $request->user(),
            $conversation
        );

        return MessageResource::collection($messages);
    }

    /**
     * Send a message.
     */
    public function store(
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

        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'message' => $request->message,
        ]);

        $message->load('user:id,name');

        // Broadcast message in real time
        broadcast(new MessageSent($message));

        return response()->json([
            'message' => 'Message sent successfully.',
            'data' => $message,
        ], 201);
    }
}
