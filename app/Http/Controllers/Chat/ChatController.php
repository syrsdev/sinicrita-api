<?php

namespace App\Http\Controllers\Chat;

use App\Events\SessionCreated;
use App\Http\Controllers\Controller;
use App\Models\chat_session;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function createSession(Request $request)
    {
        try {
            $credentials = $request->validate([
                'user1_id' => 'required|exists:users,id',
                'user2_id' => 'required|exists:users,id',
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
            $data = chat_session::where('user1_id', $user_id)
                ->orWhere('user2_id', $user_id)->with('user1', 'user2')->get();

            return response()->json(['status' => 'success', 'statusCode' => '200', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $e->getMessage()], 500);
        }
    }
}