<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="{{ url()->full() }}">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description ?? '' }}">
    <link rel="icon" type="image/png" href="/mylogo.png">
    <style>
        html { font-family: 'DM Sans', sans-serif; background:#f8f8fc; color:#111118; }
        body { margin:0; padding:0; }
        a { color:#6C63FF; text-decoration:none; }
        .page-shell { max-width:900px; margin:0 auto; padding:36px 20px; }
        .page-card { background:#fff; border:1px solid rgba(15,23,42,0.08); border-radius:24px; padding:32px; box-shadow:0 24px 60px rgba(15,23,42,0.08); }
        .page-title { margin:0 0 16px; font-size:2rem; line-height:1.1; }
        .page-content p { margin:0 0 18px; line-height:1.8; color:#475569; }
        .page-footer { margin-top:24px; font-size:0.92rem; color:#64748b; }
    </style>
</head>
<body>
    <div class="page-shell">
        <header style="margin-bottom:24px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <a href="/" style="font-weight:700; color:#111118; font-size:1rem;">Any2Convert</a>
            <nav style="display:flex; gap:14px; font-size:0.95rem; color:#475569;">
                <a href="/">Home</a>
                <a href="/blog">Blog</a>
                <a href="/privacy">Privacy</a>
                <a href="/terms">Terms</a>
            </nav>
        </header>

        <article class="page-card">
            <h2 class="page-title">{{ $headline ?? $title }}</h2>
            <div class="page-content">{!! $content ?? '<p>Welcome to Any2Convert.</p>' !!}</div>
            <div class="page-footer">
                <p>Return to the <a href="/">Any2Convert homepage</a> anytime.</p>
            </div>
        </article>
    </div>
</body>
</html>
