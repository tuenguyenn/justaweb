<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\FrontendController;
use Illuminate\Http\Request;
use App\Services\GeminiService;

class ChatbotController extends FrontendController
{
   

    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message');

        if (!$message) {
            return response()->json(['error' => 'Vui lòng nhập tin nhắn.'], 400);
        }

        $response = $this->geminiService->sendMessage($message);
        return response()->json(['response' => $response]);
    }
}
