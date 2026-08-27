<?php

namespace App\Services;

use App\Models\User;
use App\Models\Conversation; 
use App\Models\Message; 

class ConversationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAllConversations(User $user)
    {
        return $user->conversations()
            ->with('users')
            ->latest()
            ->get();
    }
}
