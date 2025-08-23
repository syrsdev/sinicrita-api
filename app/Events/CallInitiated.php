<?php

namespace App\Events;

use App\Models\Call;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CallInitiated implements ShouldBroadcast
{
    use SerializesModels;

    public $call;

    public function __construct(Call $call)
    {
        $this->call = $call;
    }

    public function broadcastOn()
    {
        return new Channel("chat.{$this->call->chat_id}");
    }

    public function broadcastAs()
    {
        return 'CallInitiated';
    }
}