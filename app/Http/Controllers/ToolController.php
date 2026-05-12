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
}
