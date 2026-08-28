<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User; 
use App\Models\Conversation; 

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel(
    'conversation.{conversation}', 
    function (
        User $user, 
        Conversation $conversation
    ) { 
        return $conversation->users()
            ->where('users.id', $user->id)
            ->exists(); 
    }
);