<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallRingingEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sessionId;
    public $fromUserId;

    public function __construct($sessionId, $fromUserId)
    {
        $this->sessionId = $sessionId;
        $this->fromUserId = $fromUserId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("call.$this->sessionId");
    }

    public function broadcastAs()
    {
        return 'CallRinging';
    }
}