<?php

namespace App\Http\Controllers\Chat;

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

            if ($credentials['user1_id']   != $credentials['user2_id']) {
                $session = chat_session::create($credentials);
            }

            return response()->json(['status' => 'success', 'statusCode' => '200', 'message' => 'Anda sudah bisa berinteraksi langsung', 'session_id' => $session->id]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'statusCode' => '500', 'message' => $e->getMessage()], 500);
        }
    }
}