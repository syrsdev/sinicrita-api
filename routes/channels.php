<?php

use App\Models\chat_session;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{sessionId}', function ($user, $sessionId) {
    $session = chat_session::find($sessionId);

    if (!$session) {
        return false;
    }

    return $user->id === $session->user1_id || $user->id === $session->user2_id;
});
