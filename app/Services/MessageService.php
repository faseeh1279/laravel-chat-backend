<?php

namespace App\Services;
use App\Models\Conversation; 
use App\Models\User; 

class MessageService
{
    public function getMessages(
        User $user,
        Conversation $conversation
    ) {
        $isParticipant = $conversation->users()
            ->where('users.id', $user->id)
            ->exists();

        if (!$isParticipant) {
            abort(403, 'Unauthorized.');
        }

        return $conversation->messages()
            ->with('user:id,name')
            ->oldest()
            ->get();
    }

    public function sendMessage(
        User $user,
        Conversation $conversation,
        string $message
    ) {
        $isParticipant = $conversation->users()
            ->where('users.id', $user->id)
            ->exists();

        if (!$isParticipant) {
            abort(403, 'Unauthorized.');
        }

        $message = $conversation->messages()->create([
            'user_id' => $user->id,
            'message' => $message,
        ]);

        return $message->load('user:id,name');
    }
}
