<?php

namespace App\Services;

use OpenAI;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    public function chat($message)
    {
        try {
            $response = $this->client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'Bạn là một trợ lý AI.'],
                    ['role' => 'user', 'content' => $message],
                ],
                'temperature' => 0.7,
                'max_tokens' => 200
            ]);
    
            return $response['choices'][0]['message']['content'] ?? 'Xin lỗi, tôi không thể trả lời ngay bây giờ.';
        } catch (\Exception $e) {
            return 'Lỗi API: ' . $e->getMessage();
        }
    }
    
}
