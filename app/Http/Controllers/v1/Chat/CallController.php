<?php

namespace App\Http\Controllers\v1\Chat;

use App\Http\Controllers\Controller;
use App\Models\calls;
use App\Models\chat_session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CallController extends Controller
{
    // POST /call/initiate
    public function initiate(Request $request)
    {
        Log::info('📞 CallController@initiate dipanggil', $request->all());
        $data = $request->validate([
            'session_id' => 'required|integer',
            'offer'      => 'required|array',
        ]);
        Log::info('✅ Validasi berhasil', $data);
        $session = chat_session::find($data['session_id']);
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Buat record call baru
        $call = calls::create([
            'chat_id' => $data['session_id'],
            'status' => 'ongoing',
            'start_time' => now(),
        ]);

        // Broadcast offer ke penerima
        broadcast(new \App\Events\CallOfferEvent(
            sessionId: $data['session_id'],
            offer: $data['offer'],
            fromUserId: Auth::id()
        ))->toOthers();

        return response()->json(['ok' => true, 'call_id' => $call->id]);
    }

    // POST /call/{sessionId}/accept
    public function accept(Request $request, int $sessionId)
    {
        $data = $request->validate([
            'answer' => 'required|array',
        ]);

        $call = calls::where('chat_id', $sessionId)->first();
        if (!$call) {
            return response()->json(['error' => 'Call not found'], 404);
        }

        // Broadcast answer
        broadcast(new \App\Events\CallAnswerEvent(
            sessionId: $sessionId,
            answer: $data['answer'],
            fromUserId: Auth::id()
        ))->toOthers();

        return response()->json(['ok' => true]);
    }

    // POST /call/{sessionId}/candidate
    public function candidate(Request $request, int $sessionId)
    {
        $data = $request->validate([
            'candidate' => 'required|array',
        ]);

        broadcast(new \App\Events\CallCandidateEvent(
            sessionId: $sessionId,
            candidate: $data['candidate'],
            fromUserId: Auth::id()
        ))->toOthers();

        return response()->json(['ok' => true]);
    }

    // POST /call/{sessionId}/ringing
    public function ringing(Request $request, int $sessionId)
    {
        broadcast(new \App\Events\CallRingingEvent($sessionId, Auth::id()))->toOthers();
        return response()->json(['ok' => true]);
    }

    // POST /call/{sessionId}/end
    public function end(Request $request, int $sessionId)
    {
        $call = calls::where('chat_id', $sessionId)->first();
        if ($call) {
            $call->update([
                'end_time' => now(),
                'status' => 'ended'
            ]);
        }

        broadcast(new \App\Events\CallEndedEvent($sessionId, Auth::id()))->toOthers();
        return response()->json(['ok' => true]);
    }

    // POST /call/{sessionId}/missed
    public function missed(Request $request, int $sessionId)
    {
        $call = calls::where('chat_id', $sessionId)->first();
        if ($call) {
            $call->update([
                'status' => 'missed',
                'end_time' => now()
            ]);
        }

        broadcast(new \App\Events\CallMissedEvent($sessionId, Auth::id()))->toOthers();
        return response()->json(['ok' => true]);
    }
}