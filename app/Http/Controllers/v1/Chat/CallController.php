<?php

namespace App\Http\Controllers\v1;

use App\Events\{CallInitiated, CallRinging, CallAccepted, CallEnded, CallMissed};
use App\Http\Controllers\Controller;
use App\Models\Call;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function initiate(Request $request)
    {
        $call = Call::create([
            'chat_id' => $request->chat_id,
            'status' => 'initiated',
        ]);

        broadcast(new CallInitiated($call))->toOthers();

        return response()->json($call);
    }

    public function ringing(Request $request, $id)
    {
        $call = Call::findOrFail($id);
        $call->update(['status' => 'ringing']);

        broadcast(new CallRinging($call))->toOthers();

        return response()->json($call);
    }

    public function accept(Request $request, $id)
    {
        $call = Call::findOrFail($id);
        $call->update(['status' => 'active']);

        broadcast(new CallAccepted($call))->toOthers();

        return response()->json($call);
    }

    public function end(Request $request, $id)
    {
        $call = Call::findOrFail($id);
        $call->update(['status' => 'ended']);

        broadcast(new CallEnded($call))->toOthers();

        return response()->json($call);
    }

    public function missed(Request $request, $id)
    {
        $call = Call::findOrFail($id);
        $call->update(['status' => 'missed']);

        broadcast(new CallMissed($call))->toOthers();

        return response()->json($call);
    }
}
