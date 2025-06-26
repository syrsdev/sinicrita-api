<?php

use App\Models\chat_session;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{sessionId}', function ($user, $sessionId) {
    $session = chat_session::find($sessionId);

    return $session && (
        $user->id === $session->user1_id || $user->id === $session->user2_id
    );
});