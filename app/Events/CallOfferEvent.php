<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CallOfferEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sessionId;
    public $offer;
    public $fromUserId;

    public function __construct($sessionId, $offer, $fromUserId)
    {
        Log::info('📤 CallOfferEvent: offer diterima', [
            'sessionId' => $sessionId,
            'offer_type' => $offer['type'] ?? 'missing',
            'offer_sdp_length' => strlen($offer['sdp'] ?? ''),
            'fromUserId' => $fromUserId
        ]);
        $this->sessionId = $sessionId;
        $this->offer = $offer;
        $this->fromUserId = $fromUserId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("call.$this->sessionId");
    }

    public function broadcastAs()
    {
        return 'CallOffer';
    }
}