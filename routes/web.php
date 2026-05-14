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
    'title' => 'About Any2Convert',
    'description' => 'Learn more about Any2Convert, the free online PDF and document conversion toolkit.',
    'headline' => 'About Any2Convert',
    'content' => '<p>Any2Convert provides free online PDF, document, image, and utility tools with a privacy-first approach. Many tools process files locally in the browser while others offer managed conversions without unnecessary tracking.</p>'
]);

Route::view('/contact', 'page', [
    'title' => 'Contact Any2Convert',
    'description' => 'Reach out to Any2Convert for support, feedback, or partnership inquiries.',
    'headline' => 'Contact Any2Convert',
    'content' => '<p>Need help or have feedback? Contact Any2Convert to share ideas, report an issue, or ask about new tool suggestions.</p>'
]);

Route::view('/privacy', 'page', [
    'title' => 'Privacy Policy - Any2Convert',
    'description' => 'Read the privacy policy for Any2Convert and how we handle user data and file processing.',
    'headline' => 'Privacy Policy',
    'content' => '<p>Any2Convert is designed to minimize data collection and keep many tasks local to your device. We do not store files longer than necessary for server-based conversions.</p>'
]);

Route::view('/terms', 'page', [
    'title' => 'Terms of Service - Any2Convert',
    'description' => 'Read the terms of service for Any2Convert before using the site and tools.',
    'headline' => 'Terms of Service',
    'content' => '<p>By using Any2Convert, you agree to our terms governing access, permitted use, and tool availability. This site is provided as a free service.</p>'
]);

Route::view('/login', 'page', [
    'title' => 'Login - Any2Convert',
    'description' => 'Sign in to Any2Convert to access saved features and account-based tools.',
    'headline' => 'Login',
    'content' => '<p>Login functionality is available for account-based features. If you need help signing in, please contact support.</p>'
]);

Route::view('/register', 'page', [
    'title' => 'Register - Any2Convert',
    'description' => 'Create a free Any2Convert account to save preferences and access additional features.',
    'headline' => 'Register',
    'content' => '<p>Register for a free Any2Convert account to unlock saved settings and additional document management workflows.</p>'
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
