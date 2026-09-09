<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private user channel: for incoming messages & booking status updates
Broadcast::channel('kidaboard-user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Presence channel: to track who is online within a conversation
Broadcast::channel('presence-kidaboard-conv.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);
    if (! $conversation) return false;

    // Only allow participants of this conversation
    if ((int) $user->id !== (int) $conversation->student_id && (int) $user->id !== (int) $conversation->owner_id) {
        return false;
    }

    return [
        'id'   => $user->id,
        'name' => $user->name,
        'role' => $user->role,
    ];
});
