<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home');
    }

    public function tool(string $slug): View
    {
        $toolId = $this->toolIdFromSlug($slug);

        abort_unless($toolId, 404);

        return view('home', [
            'initialToolId' => $toolId,
        ]);
    }

    public function legacyHighlight(Request $request)
    {
        $topic = $request->query('topic');

        abort_unless(is_string($topic) && $topic !== '', 404);

        return redirect('/highlights/' . rawurlencode($topic), 301);
    }

    public function highlight(string $topic): View
    {
        $topics = [
            'instant-processing' => [
                'label' => 'Instant Processing',
                'desc' => 'Get fast results with instant processing.',
            ],
            'files-never-leave-your-device' => [
                'label' => 'Files Never Leave Your Device',
                'desc' => 'Files stay on your device for privacy.',
            ],
            'no-file-uploads' => [
                'label' => 'No File Uploads',
                'desc' => 'No file uploads needed for privacy.',
            ],
            'free-forever' => [
                'label' => 'Free Forever',
                'desc' => 'Free forever with no hidden fees.',
            ],
            'works-in-browser' => [
                'label' => 'Works in Browser',
                'desc' => 'Works directly in your web browser.',
            ],
            'works-on-any-device' => [
                'label' => 'Works on Any Device',
                'desc' => 'Works on any device without apps.',
            ],
            'always-free-no-watermarks' => [
                'label' => 'Always Free, No Watermarks',
                'desc' => 'Always free with no watermarks.',
            ],
            'instant-results' => [
                'label' => 'Instant Results',
                'desc' => 'Get instant results quickly.',
            ],
        ];

        abort_unless(isset($topics[$topic]), 404);

        $item = $topics[$topic];

        return view('page', [
            'title' => $item['label'] . ' | Any2Convert Feature',
            'description' => $item['desc'],
            'headline' => $item['label'],
            'content' => '<p>' . $item['desc'] . '</p>',
        ]);
    }

    public function blogIndex(): View
    {
        return view('page', [
            'title' => 'Any2Convert Blog for File Conversion Tips',
            'description' => 'Read tips and guides for file conversion.',
            'headline' => 'Any2Convert Blog',
            'content' => '<p>Read our tips and guides. Learn how to change files and keep your data safe.</p>' .
                '<ul>' .
                '<li><a href="/blog/security-benefits">Why Image to PDF is More Secure</a></li>' .
                '<li><a href="/blog/qr-guide">Business QR Code Best Practices</a></li>' .
                '</ul>',
        ]);
    }

    public function blogArticle(string $slug): View
    {
        $articles = [
            'security-benefits' => [
                'title' => 'Why Image to PDF is More Secure',
                'desc' => 'Learn PDF security benefits for photos.',
                'content' => '<p>Putting photos in a PDF is smart. It keeps them safe in one file. You can easily add a password. You can also stop people from changing your file.</p>',
            ],
            'qr-guide' => [
                'title' => 'Business QR Code Best Practices',
                'desc' => 'Best practices for business QR codes.',
                'content' => '<p>A good QR code is clear and dark. Keep the design very simple. Do not put it on messy backgrounds. Always scan it with your phone before you print it.</p>',
            ],
        ];

        abort_unless(isset($articles[$slug]), 404);

        $article = $articles[$slug];

        return view('page', [
            'title' => $article['title'] . ' Guide | Any2Convert',
            'description' => $article['desc'],
            'headline' => $article['title'],
            'content' => '<p>' . $article['content'] . '</p>',
        ]);
    }

    private function toolIdFromSlug(string $slug): ?string
    {
        $slugs = require app_path('Support/tool_slugs.php');

        return array_search($slug, $slugs, true) ?: null;
    }
}
