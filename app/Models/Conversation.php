<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; 
use Illuminate\Database\Eloquent\Relations\HasMany; 
use App\Models\User; 
use App\Models\Message; 
class Conversation extends Model
{
    protected $fillable = [ 
        'user_id', 
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'conversation_user'
        )->withTimestamps();
    }
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
