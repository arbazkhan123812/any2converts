<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ToolController extends Controller
{
    public function render(Request $request): Response
    {
        require_once app_path('Support/tool_handlers.php');

        return response(renderToolHandlerHTML((string) $request->query('tool', '')));
    }

    public function unavailable(): JsonResponse
    {
        return response()->json([
            'error' => 'This server-side endpoint is not configured in the Laravel project yet.',
        ], 501);
    }

    public function youtubeDownload(Request $request): JsonResponse
    {
        $url = $request->input('url');
        $format = $request->input('vQuality', '1080');
        $isAudioOnly = $request->input('isAudioOnly', false);

        if (!$url) {
            return response()->json(['error' => 'No URL provided'], 400);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->post('https://api.cobalt.tools/api/json', [
                'url' => $url,
                'vQuality' => $format,
                'isAudioOnly' => $isAudioOnly,
                'vCodec' => 'h264'
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Backend connection failed', 'text' => $e->getMessage()], 500);
        }
    }
}
