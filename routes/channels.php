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

Broadcast::channel('call.{sessionId}', function ($user, $sessionId) {
    // Cek apakah user bagian dari sesi chat
    $session = chat_session::find($sessionId);
    if (!$session) return false;

    // Hanya user1 atau user2 yang bisa join
    return $user->id === $session->user1_id || $user->id === $session->user2_id;
});