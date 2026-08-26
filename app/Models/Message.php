<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use App\Models\User; 
use App\Models\Conversation; 
class Message extends Model
{
    protected $fillable = [
        'user_id',
        'conversation_id',
        'message'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversation(): BelongsTo
    { 
        return $this->belongsTo(Conversation::class); 
    }
}
