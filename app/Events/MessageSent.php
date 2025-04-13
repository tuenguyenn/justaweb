<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent 
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    // Constructor để gán giá trị message
    public function __construct($message)
    {
        $this->message = $message;
    }

    // Channel mà bạn muốn phát sóng trên đó
    public function broadcastOn()
    {
        return new Channel('chat-channel');
    }

    // Tên sự kiện broadcast (có thể dùng để nhận sự kiện ở phía client)
    public function broadcastAs()
    {
        return 'message.sent';
    }
}

