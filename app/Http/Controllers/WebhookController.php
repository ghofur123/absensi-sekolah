<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        if ($request->isMethod('get')) {
            return response()->json([
                'message' => 'Webhook is active',
                'method' => 'GET',
                'status' => true,
            ]);
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $sender  = $data['sender'] ?? null;
        $message = $data['message'] ?? '';

        $reply = match (strtolower(trim($message))) {
            'test'  => ['message' => 'working great!'],
            'image' => [
                'message' => 'image message',
                'url'     => 'https://filesamples.com/samples/image/jpg/sample_640%C3%97426.jpg',
            ],
            'audio' => [
                'message'  => 'audio message',
                'url'      => 'https://filesamples.com/samples/audio/mp3/sample3.mp3',
                'filename' => 'music.mp3',
            ],
            'video' => [
                'message' => 'video message',
                'url'     => 'https://filesamples.com/samples/video/mp4/sample_640x360.mp4',
            ],
            'file'  => [
                'message'  => 'file message',
                'url'      => 'https://filesamples.com/samples/document/docx/sample3.docx',
                'filename' => 'document.docx',
            ],
            default => $this->aiOpenRouter($message),
        };

        // Kirim ke Fonnte
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.fonnte.com/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'target'   => $sender,
                'message'  => $reply['message'] ?? '',
                'url'      => $reply['url'] ?? '',
                'filename' => $reply['filename'] ?? '',
            ],
            CURLOPT_HTTPHEADER => [
                "Authorization: " . env('FONNTE_TOKEN')
            ],
        ]);
        $response = curl_exec($curl);
        curl_close($curl);

        return response()->json([
            'status' => 'sent',
            'response' => json_decode($response, true),
        ]);
    }

    public function aiOpenRouter($userMessage)
    {
        $sistemMessage = 'Kamu adalah customer service abdul gafur yang ramah dan mengutamakan salam dan memanggil user itu dengan kak';
        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'X-Title'       => 'Kampus Chatbot',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'deepseek/deepseek-chat-v3-0324:free',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $sistemMessage,
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage, // ini yang dikirim user via URL
                ],
            ]
        ]);

        if ($response->successful()) {
            return response()->json([
                'message' => $response->json()['choices'][0]['message']['content'] ?? 'Tidak ada jawaban.',
            ]);
        }

        return response()->json([
            'error' => 'Gagal memanggil API OpenRouter.',
            'details' => $response->json(),
        ], $response->status());
    }
}
