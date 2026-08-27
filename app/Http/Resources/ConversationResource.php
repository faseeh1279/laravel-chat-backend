<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentUser = $request->user();

        $recipient = $this->users
            ->firstWhere('id', '!=', $currentUser->id);

        return [
            'id' => $this->id,

            'recipient' => $recipient ? [
                'id' => $recipient->id,
                'name' => $recipient->name,
            ] : null,

            'last_message' => $this->whenLoaded(
                'messages',
                function () {
                    $message = $this->messages->first();

                    return $message ? [
                        'id' => $message->id,
                        'message' => $message->message,
                        'user_id' => $message->user_id,
                        'created_at' => $message->created_at,
                    ] : null;
                }
            ),
        ];
    }
}