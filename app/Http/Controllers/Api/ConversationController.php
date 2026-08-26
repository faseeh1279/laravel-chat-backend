<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Conversation; 
use App\Models\Message; 


class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->with([
                'users',
                'messages' => function ($query) {
                    $query->latest()->limit(1);
                }
            ])
            ->get();

        return response()->json($conversations);
    }

    /**
     * Open a conversation with another user.
     *
     * If conversation already exists, return it.
     * Otherwise create a new conversation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $currentUser = $request->user();
        $otherUser = User::findOrFail($request->user_id);

        // Don't allow user to create a conversation with themselves.
        if ($currentUser->id === $otherUser->id) {
            return response()->json([
                'message' => 'You cannot create a conversation with yourself.'
            ], 422);
        }

        /*
         * Find a conversation where BOTH users participate.
         */
        $conversation = Conversation::whereHas('users', function ($query) use ($currentUser) {
            $query->where('users.id', $currentUser->id);
        })
        ->whereHas('users', function ($query) use ($otherUser) {
            $query->where('users.id', $otherUser->id);
        })
        ->withCount('users')
        ->having('users_count', '=', 2)
        ->first();

        /*
         * Conversation doesn't exist.
         * Create it and attach both users.
         */
        if (!$conversation) {
            $conversation = Conversation::create();

            $conversation->users()->attach([
                $currentUser->id,
                $otherUser->id,
            ]);
        }

        $conversation->load('users');

        return response()->json([
            'message' => 'Conversation opened successfully.',
            'conversation' => $conversation,
        ], 200);
    }

    /**
     * Get a single conversation.
     */
    public function show(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        /*
         * Security check:
         * Make sure the authenticated user
         * actually belongs to this conversation.
         */
        $isParticipant = $conversation->users()
            ->where('users.id', $user->id)
            ->exists();

        if (!$isParticipant) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 403);
        }

        $conversation->load('users');

        return response()->json($conversation);
    }
}
