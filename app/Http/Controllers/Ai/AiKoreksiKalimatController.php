<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiKoreksiKalimatController extends Controller
{
    public function koreksiKalimat($userMessage)
    {
        $systemMessage = 'Perbaiki kesalahan penulisan (typo) dari teks berbahasa Indonesia. Jangan ubah jumlah kata. Jangan tambahkan penjelasan. Jawaban hanya teks hasil koreksi dalam bahasa Indonesia.';


        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
            'X-Title'       => 'Kampus Chatbot',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'mistralai/mistral-7b-instruct:free',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemMessage,
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage,
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
