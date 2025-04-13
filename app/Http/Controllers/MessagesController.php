<?php

namespace App\Http\Controllers;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function sendMessage(Request $request)
    {
       
        if (Auth::guard('web')->check()) {
            $sender_id = Auth::guard('web')->id();
            $receiver_id = $request->receiver_id;  // ID của khách hàng
        } elseif (Auth::guard('customer')->check()) {
            $sender_id = Auth::guard('customer')->id();
            $receiver_id = $request->receiver_id;  // ID của nhân viên
        }
        $message = $request->message;

        // Lưu tin nhắn vào bảng messages
        $newMessage = new Message();
        $newMessage->sender_id = $sender_id;
        $newMessage->receiver_id = $receiver_id;
        $newMessage->content = $message;
        $newMessage->save();
    
        // Broadcast sự kiện tin nhắn
        broadcast(new MessageSent($message));
    
        return response()->json(['message' => 'Tin nhắn đã được gửi']);
    }
    
}
