<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ToolController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tools/render', [ToolController::class, 'render'])->name('tools.render');
Route::post('/tools/pdf-service', [ToolController::class, 'unavailable'])->name('tools.pdf-service');
Route::post('/tools/ai-image', [ToolController::class, 'unavailable'])->name('tools.ai-image');

Route::view('/about', 'page', [
    'title' => 'About Any2Convert Free Online Tools',
    'description' => 'Learn more about Any2Convert. We offer free and easy online tools for your files.',
    'headline' => 'About Any2Convert',
    'content' => '<p>Any2Convert gives you free online tools for PDFs, documents, and images. We care about your privacy. Many of our tools work right in your browser. This means your files stay on your device.</p>'
]);

Route::view('/contact', 'page', [
    'title' => 'Contact Any2Convert Support and Feedback',
    'description' => 'Contact us if you need help. We love to hear your feedback and new ideas.',
    'headline' => 'Contact Any2Convert',
    'content' => '<p>Do you need help or have ideas to share? Please contact us. You can report bugs, ask questions, or suggest new tools. We love to hear from you.</p>'
]);

Route::view('/privacy', 'page', [
    'title' => 'Privacy Policy for Any2Convert Online Tools',
    'description' => 'Read our privacy policy. Learn how we protect your files and keep your data safe.',
    'headline' => 'Privacy Policy',
    'content' => '<p>We built Any2Convert to keep your data safe. We collect very little info. Many tools run only on your own device. If we must process a file on our server, we delete it right away.</p>'
]);

Route::view('/terms', 'page', [
    'title' => 'Terms of Service for Any2Convert Online Tools',
    'description' => 'Read our terms of service. Learn the rules for using our free online tools and website.',
    'headline' => 'Terms of Service',
    'content' => '<p>By using this site, you agree to our rules. You can use our tools for free. We try to keep all tools online, but we offer them as-is. Please use them fairly.</p>'
]);

Route::view('/login', 'page', [
    'title' => 'Login to Any2Convert Online Tool Account',
    'description' => 'Sign in to your Any2Convert account. Access your saved settings and personalized features.',
    'headline' => 'Login',
    'content' => '<p>Sign in to use your account features. A free account lets you save your settings. If you cannot sign in, please email our support team for help.</p>'
]);

Route::view('/register', 'page', [
    'title' => 'Register for Any2Convert Online Tools',
    'description' => 'Create a free Any2Convert account. Save your preferences and access advanced tool features.',
    'headline' => 'Register',
    'content' => '<p>Sign up for a free account today. An account lets you save your choices. It also helps you work faster with your files. Joining is quick and easy.</p>'
]);

Route::get('/highlights', [HomeController::class, 'legacyHighlight'])->name('highlights.legacy');
Route::get('/highlights/{topic}', [HomeController::class, 'highlight'])
    ->where('topic', '[A-Za-z0-9-]+')
    ->name('highlights');
Route::get('/blog', [HomeController::class, 'blogIndex'])->name('blog.index');
Route::get('/blog/{slug}', [HomeController::class, 'blogArticle'])
    ->where('slug', 'qr-guide|security-benefits')
    ->name('blog.article');

Route::get('/highlights.php', function (Request $request) {
    $target = $request->query('topic')
        ? '/highlights/' . rawurlencode($request->query('topic'))
        : '/highlights';
    return redirect($target, 301);
});

Route::get('/about.php', fn() => redirect('/about', 301));
Route::get('/contact.php', fn() => redirect('/contact', 301));
Route::get('/privacy.php', fn() => redirect('/privacy', 301));
Route::get('/terms.php', fn() => redirect('/terms', 301));

Route::get('/blog/index.php', fn() => redirect('/blog', 301));
Route::get('/blog/qr-guide.php', fn() => redirect('/blog/qr-guide', 301));
Route::get('/blog/security-benefits.php', fn() => redirect('/blog/security-benefits', 301));
Route::get('/blog/highlights.php', function (Request $request) {
    $target = $request->query('topic')
        ? '/highlights/' . rawurlencode($request->query('topic'))
        : '/highlights';
    return redirect($target, 301);
});

Route::get('/blog/about.php', fn() => redirect('/about', 301));
Route::get('/blog/contact.php', fn() => redirect('/contact', 301));
Route::get('/blog/privacy.php', fn() => redirect('/privacy', 301));
Route::get('/blog/terms.php', fn() => redirect('/terms', 301));

Route::get('/public/about.php', fn() => redirect('/about', 301));
Route::get('/public/contact.php', fn() => redirect('/contact', 301));
Route::get('/public/privacy.php', fn() => redirect('/privacy', 301));
Route::get('/public/terms.php', fn() => redirect('/terms', 301));
Route::get('/public/blog/index.php', fn() => redirect('/blog', 301));
Route::get('/public/blog/qr-guide.php', fn() => redirect('/blog/qr-guide', 301));
Route::get('/public/blog/security-benefits.php', fn() => redirect('/blog/security-benefits', 301));
Route::get('/public/highlights.php', function (Request $request) {
    $target = $request->query('topic')
        ? '/highlights/' . rawurlencode($request->query('topic'))
        : '/highlights';
    return redirect($target, 301);
});

Route::get('/{slug}', [HomeController::class, 'tool'])->name('tools.show');
