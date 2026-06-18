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

        // Detect OS and set appropriate yt-dlp path
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        
        if ($isWindows) {
            $binDir = base_path('bin');
            $ytDlp = base_path('bin/yt-dlp.exe');
            $ffmpegLocationArg = '--ffmpeg-location "' . $binDir . '"';
        } else {
            // On Linux server, it's usually installed globally in /usr/local/bin/yt-dlp or /usr/bin/yt-dlp
            $ytDlp = '/usr/local/bin/yt-dlp';
            if (!file_exists($ytDlp)) {
                $ytDlp = '/usr/bin/yt-dlp'; // fallback
            }
            $ffmpegLocationArg = ''; // On Linux, ffmpeg is usually installed globally and found automatically
        }

        if (!file_exists($ytDlp)) {
            return response()->json(['error' => 'Downloader executable not found on server. Expected at: ' . $ytDlp], 500);
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
            '"%s" %s %s -o "%s" "%s"',
            $ytDlp,
            $ffmpegLocationArg,
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
