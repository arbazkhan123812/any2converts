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
        set_time_limit(0);
        $url = $request->input('url');
        $format = $request->input('vQuality', '1080');

        if (!$url) {
            return response()->json(['error' => 'No URL provided'], 400);
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json(['error' => 'Invalid URL'], 400);
        }

        $binDir = base_path('bin');
        $ytDlp = $binDir . DIRECTORY_SEPARATOR . 'yt-dlp.exe';

        if (!file_exists($ytDlp)) {
            return response()->json(['error' => 'Downloader executable not found on server.'], 500);
        }

        $uniqueId = uniqid('yt_');
        $downloadDirRelative = 'downloads/youtube/' . $uniqueId;
        $downloadDir = public_path($downloadDirRelative);
        if (!is_dir($downloadDir)) {
            mkdir($downloadDir, 0755, true);
        }

        $outputPath = $downloadDir . DIRECTORY_SEPARATOR . '%(title)s.%(ext)s';

        if ($format === 'mp3') {
            $formatArg = "-x --audio-format mp3 --audio-quality 0";
        } else {
            $formatArg = "-f \"bestvideo[height<={$format}][ext=mp4]+bestaudio[ext=m4a]/best[height<={$format}][ext=mp4]/best\" --merge-output-format mp4";
        }

        $cmd = sprintf(
            '"%s" --ffmpeg-location "%s" %s -o "%s" "%s"',
            $ytDlp,
            $binDir,
            $formatArg,
            $outputPath,
            escapeshellarg($url)
        );

        exec($cmd . ' 2>&1', $output, $returnCode);

        if ($returnCode !== 0) {
            return response()->json(['error' => 'Download failed', 'details' => implode("\n", $output)], 500);
        }

        $files = glob($downloadDir . '/*');
        if (empty($files)) {
            return response()->json(['error' => 'File not found after download'], 500);
        }

        $downloadedFile = basename($files[0]);
        $fileUrl = asset($downloadDirRelative . '/' . rawurlencode($downloadedFile));

        return response()->json(['url' => $fileUrl, 'filename' => $downloadedFile]);
    }
}
