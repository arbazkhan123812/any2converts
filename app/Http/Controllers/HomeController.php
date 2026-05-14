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
                'desc' => 'Get results fast with tools that run locally in the browser or use managed conversions with minimal wait time.',
            ],
            'files-never-leave-your-device' => [
                'label' => 'Files Never Leave Your Device',
                'desc' => 'Many tools process files directly in your browser so sensitive files stay private and do not get uploaded unnecessarily.',
            ],
            'no-file-uploads' => [
                'label' => 'No File Uploads',
                'desc' => 'Some Any2Convert tools handle files entirely in your browser, so private content stays local while conversion runs.',
            ],
            'free-forever' => [
                'label' => 'Free Forever',
                'desc' => 'Any2Convert remains free to use without unexpected paywalls, so you can complete conversions without subscription barriers.',
            ],
            'works-in-browser' => [
                'label' => 'Works in Browser',
                'desc' => 'Open Any2Convert in any modern browser and use tools immediately, without installing software or browser extensions.',
            ],
            'works-on-any-device' => [
                'label' => 'Works on Any Device',
                'desc' => 'Use Any2Convert on desktop, tablet, or mobile without installing anything. The tools adapt seamlessly to your browser.',
            ],
            'always-free-no-watermarks' => [
                'label' => 'Always Free, No Watermarks',
                'desc' => 'Any2Convert is free to use with no forced watermarks, no hidden fees, and no paywalls on supported file tasks.',
            ],
            'instant-results' => [
                'label' => 'Instant Results',
                'desc' => 'Many conversions complete instantly in the browser, so you can keep working without waiting on uploads or downloads.',
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
            'description' => 'Read the latest guides, best practices, and privacy tips from Any2Convert.',
            'headline' => 'Any2Convert Blog',
            'content' => '<p>Explore guides and articles about file conversion, privacy, and using Any2Convert more effectively.</p>' .
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
                'desc' => 'Learn how converting images to PDFs can preserve privacy and protect your documents from unauthorized access.',
                'content' => '<p>Converting images to PDF can help centralize document security and make it easier to apply protections such as passwords and restricted editing permissions.</p>',
            ],
            'qr-guide' => [
                'title' => 'Business QR Code Best Practices',
                'desc' => 'Discover how to create scannable QR codes for your business with better reliability and user experience.',
                'content' => '<p>Good QR codes are high contrast, use a simple design, and avoid placing them on busy backgrounds. Test across phones before publishing.</p>',
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
