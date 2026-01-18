<?php

use App\Http\Controllers\Ai\AiKoreksiKalimatController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/web-hook', [WebhookController::class, 'handleWebhook']);
Route::match(['get', 'post'], '/web-hook', [WebhookController::class, 'handleWebhook']);
Route::get('/maksut-user/{userMessage}', [WebhookController::class, 'aiKoreksiKalimat']);

Route::get('/cek-deepseek', function () {
    $ai = new AiKoreksiKalimatController();
    $response = $ai->koreksiKalimat('indi adbalah kalimat yddang akaan di korehhksi');
    return response()->json($response);
});

// Route::get('/test-openrouter/{message}', function ($userMessage) {
//     $sistemMessage = 'Kamu adalah customer service abdul gafur yang ramah dan mengutamakan salam dan memanggil user itu dengan kak';
//     $response = Http::withHeaders([
//         'Content-Type'  => 'application/json',
//         'Authorization' => 'Bearer ' . env('OPENROUTER_API_KEY'),
//         'X-Title'       => 'Kampus Chatbot',
//     ])->post('https://openrouter.ai/api/v1/chat/completions', [
//         'model' => 'deepseek/deepseek-r1:free',
//         'messages' => [
//             [
//                 'role' => 'system',
//                 'content' => $sistemMessage,
//             ],
//             [
//                 'role' => 'user',
//                 'content' => $userMessage, // ini yang dikirim user via URL
//             ],
//         ]
//     ]);

//     if ($response->successful()) {
//         return response()->json([
//             'message' => $response->json()['choices'][0]['message']['content'] ?? 'Tidak ada jawaban.',
//         ]);
//     }

//     return response()->json([
//         'error' => 'Gagal memanggil API OpenRouter.',
//         'details' => $response->json(),
//     ], $response->status());
// });