<?php

namespace App\Http\Controllers\v1\Chat;

use App\Events\MessageSent;
use App\Events\SessionCreated;
use App\Http\Controllers\Controller;
use App\Models\chat_session;
use App\Models\messages;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function createSession(Request $request)
    {
        try {
            $credentials = $request->validate([
                'user1_id' => 'required|exists:users,id',
                'user2_id' => 'required|exists:users,id',
                'post_id' => 'nullable|exists:posts,id'
            ]);

            $existingSession = chat_session::where(function ($query) use ($credentials) {
                $query->where('user1_id', $credentials['user1_id'])
                    ->where('user2_id', $credentials['user2_id']);
            })->orWhere(function ($query) use ($credentials) {
                $query->where('user1_id', $credentials['user2_id'])
                    ->where('user2_id', $credentials['user1_id']);
            })->first();

            if ($credentials['user1_id'] === $credentials['user2_id']) {
                return response()->json([
                    'status' => 'error',
                    'statusCode' => 400,
                    'message' => 'Tidak bisa membuat sesi dengan diri sendiri.',
                ], 400);
            }

            if ($existingSession) {
                if ($credentials['post_id'] ?? null) {
                    $existingSession->post_id = $credentials['post_id'];
                    $existingSession->save();
                }
                event(new SessionCreated($existingSession));

                return response()->json([
                    'status' => 'success',
                    'statusCode' => 301,
                    'id' => $existingSession->id,
                ], 301);
            }
            $session = chat_session::create($credentials);
            event(new SessionCreated($session));

            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Anda sudah bisa mengirim pesan', 'data' => $session]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $e->getMessage()], 500);
        }
    }

    public function listChat($user_id)
    {
        try {
            $data = chat_session::whereNotNull('post_id')
                ->where(function ($query) use ($user_id) {
                    $query->where('user1_id', $user_id)
                        ->orWhere('user2_id', $user_id);
                })
                ->with('user1', 'user2')
                ->orderBy('updated_at', 'desc')
                ->get();

            return response()->json(['status' => 'success', 'statusCode' => '200', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $e->getMessage()], 500);
        }
    }

    public function detailChat($session_id)
    {
        try {
            $session = chat_session::whereNotNull('post_id')->where('id', $session_id)
                ->with('user1', 'user2')->first();
            $data = messages::where('session_id', $session_id)->with('user', 'post')->orderBy('created_at', 'asc')->get();

            if ($session == null) {
                return response()->json([
                    'status' => 'error',
                    'statusCode' => 404,
                    'message' => 'Tidak ditemukan.',
                ], 404);
            }
            return response()->json(['status' => 'success', 'statusCode' => '200', 'data' => ['chat' => $data, 'session' => $session]]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $e->getMessage()], 500);
        }
    }

    public function sendMessage(Request $request)
    {
        try {
            $credentials = $request->validate([
                'session_id' => 'required|exists:chat_sessions,id',
                'sender_id' => 'required|exists:users,id',
                'message' => 'required',
                'post_id' => 'nullable|exists:posts,id',
            ]);

            $session = chat_session::where('id', $credentials['session_id'])->first();

            $message = messages::create($credentials);
            $session->touch();

            broadcast(new MessageSent($message));
            broadcast(new SessionCreated($session));

            return response()->json(['status' => 'success', 'statusCode' => '200', 'data' => $message]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $e->getMessage()], 500);
        }
    }
}
