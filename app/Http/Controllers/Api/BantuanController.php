<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BantuanController extends Controller
{
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:4000',
            'history' => 'array', // optional: [{role: "user"|"model", content: "..."}]
            'model'   => 'sometimes|string',
        ]);

        $provider = env('AI_PROVIDER', 'gemini');
        $model = $validated['model'] ?? null;
        
        if ($provider === 'openai') {
            $apiKey = env('OPENAI_API_KEY');
            if (!$apiKey) {
                return response()->json([
                    'error' => 'Server belum dikonfigurasi. Set OPENAI_API_KEY di file .env.'
                ], 500);
            }
            $model = $model ?? env('OPENAI_MODEL', 'gpt-3.5-turbo');
        } else {
            $apiKey = env('GEMINI_API_KEY');
            if (!$apiKey) {
                return response()->json([
                    'error' => 'Server belum dikonfigurasi. Set GEMINI_API_KEY di file .env.'
                ], 500);
            }
            $model = $model ?? env('GEMINI_MODEL', 'gemini-1.5-flash');
            // Normalisasi nama model untuk REST API Google AI
            // - Hilangkan prefix 'models/' jika ada
            // - Ubah alias '*-latest' menjadi nama dasar agar tidak 404
            if (str_starts_with($model, 'models/')) {
                $model = substr($model, 7);
            }
            if (function_exists('str_ends_with')) {
                if (str_ends_with($model, '-latest')) {
                    $model = substr($model, 0, -7);
                }
            } else {
                // Fallback PHP <8.0 (seharusnya tidak terpakai di project ini)
                if (substr($model, -7) === '-latest') {
                    $model = substr($model, 0, -7);
                }
            }
        }

        // Build provider-specific payload
        $useOpenAI = $provider === 'openai';
        if ($useOpenAI) {
            $messages = [];
            if (!empty($validated['history'])) {
                foreach ($validated['history'] as $turn) {
                    if (!isset($turn['role'], $turn['content'])) continue;
                    $role = $turn['role'] === 'model' ? 'assistant' : 'user';
                    $messages[] = [ 'role' => $role, 'content' => Str::limit((string)$turn['content'], 4000, '') ];
                }
            }
            $messages[] = [ 'role' => 'user', 'content' => $validated['message'] ];
        } else {
            $contents = [];
            if (!empty($validated['history'])) {
                foreach ($validated['history'] as $turn) {
                    if (!isset($turn['role'], $turn['content'])) continue;
                    $role = $turn['role'] === 'model' ? 'model' : 'user';
                    $contents[] = [
                        'role' => $role,
                        'parts' => [ ['text' => Str::limit((string)$turn['content'], 4000, '')] ],
                    ];
                }
            }
            $contents[] = [
                'role' => 'user',
                'parts' => [ ['text' => $validated['message']] ],
            ];
        }

        try {
            $http = Http::withHeaders(['Content-Type' => 'application/json']);
            if (env('HTTP_INSECURE', false)) { $http = $http->withoutVerifying(); }
            if ($useOpenAI) {
                $endpoint = 'https://api.openai.com/v1/chat/completions';
                $resp = $http->withHeaders([
                    'Authorization' => 'Bearer '.$apiKey,
                ])->timeout(20)->retry(2, 200)->post($endpoint, [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 512,
                ]);
            } else {
                $apiVersion = str_starts_with($model, 'gemini-1.0-') ? 'v1beta' : 'v1';
                $makeReq = function($version, $mdl) use ($http, $contents, $apiKey) {
                    $endpoint = sprintf('https://generativelanguage.googleapis.com/%s/models/%s:generateContent?key=%s', $version, $mdl, $apiKey);
                    return $http->timeout(20)->retry(2, 200)->post($endpoint, [
                        'contents' => $contents,
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'maxOutputTokens' => 512,
                        ],
                    ]);
                };

                // Build attempt matrix: versions x models
                $versions = $apiVersion === 'v1' ? ['v1', 'v1beta'] : ['v1beta', 'v1'];
                $models = [$model, 'gemini-1.5-flash'];

                $resp = null;
                foreach ($versions as $ver) {
                    foreach ($models as $mdl) {
                        $resp = $makeReq($ver, $mdl);
                        if ($resp->ok()) { break 2; }
                        // If 404 model not found, continue to next combo; otherwise stop early
                        $status = $resp->status();
                        $body = $resp->json();
                        $msg = is_array($body) ? ($body['error']['message'] ?? '') : '';
                        $isNotFound = ($status === 404) && (stripos($msg, 'not found') !== false || stripos($msg, 'is not found') !== false);
                        if (!$isNotFound) { break 2; }
                    }
                }
            }

            if (!$resp->ok()) {
                $body = $resp->json();
                $msg = $body['error']['message'] ?? 'Gagal meminta jawaban dari Gemini.';
                Log::error('AI provider error', ['status'=>$resp->status(), 'body'=>$resp->body()]);
                return response()->json([
                    'error' => $msg,
                    'details' => $body,
                ], $resp->status());
            }

            $data = $resp->json();
            if ($useOpenAI) {
                $text = $data['choices'][0]['message']['content'] ?? '';
            } else {
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            }
            return response()->json([
                'reply' => $text,
            ]);
        } catch (\Throwable $e) {
            Log::error('AI chat exception', ['message'=>$e->getMessage()]);
            return response()->json([
                'error' => 'Terjadi kesalahan saat memproses permintaan.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
