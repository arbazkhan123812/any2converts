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
                'desc' => 'Get results fast. Many tools run right in your browser. Other tasks use fast servers so you do not wait long.',
            ],
            'files-never-leave-your-device' => [
                'label' => 'Files Never Leave Your Device',
                'desc' => 'Keep your files safe. Many tools run right on your device. Your private files are not sent to our servers.',
            ],
            'no-file-uploads' => [
                'label' => 'No File Uploads',
                'desc' => 'You do not need to upload files. Many tools work right inside your browser. This keeps your private items safe.',
            ],
            'free-forever' => [
                'label' => 'Free Forever',
                'desc' => 'Our site is free to use. There are no hidden fees. We do not ask you to pay to use our core tools.',
            ],
            'works-in-browser' => [
                'label' => 'Works in Browser',
                'desc' => 'Just open the web page and start working. You do not need to install any apps. It works on all web browsers.',
            ],
            'works-on-any-device' => [
                'label' => 'Works on Any Device',
                'desc' => 'Use our tools on your phone, tablet, or computer. You do not need an app. The site works well on any screen.',
            ],
            'always-free-no-watermarks' => [
                'label' => 'Always Free, No Watermarks',
                'desc' => 'Use our tools for free. We do not put ugly marks on your files. There are no sneaky costs to worry about.',
            ],
            'instant-results' => [
                'label' => 'Instant Results',
                'desc' => 'Get your files right away. Many tasks finish the moment you click. You can keep working instead of waiting.',
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
            'description' => 'Read our tips and guides. Learn how to change files and keep your data safe.',
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
                'desc' => 'Learn how a PDF keeps your photos safe. Find out how to lock files to stop others from seeing them.',
                'content' => '<p>Putting photos in a PDF is smart. It keeps them safe in one file. You can easily add a password. You can also stop people from changing your file.</p>',
            ],
            'qr-guide' => [
                'title' => 'Business QR Code Best Practices',
                'desc' => 'Find out how to make good QR codes. Learn to make codes that scan fast and work well for your customers.',
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
