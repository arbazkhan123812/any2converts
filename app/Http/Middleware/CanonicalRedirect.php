<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalRedirect
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return $next($request);
        }

        $host = strtolower($request->getHost());
        $path = $request->getPathInfo();
        $originalPath = $path;
        $shouldRedirect = false;

        // Redirect www.any2convert.com to any2convert.com
        $targetHost = $host;
        if ($host === 'www.any2convert.com') {
            $targetHost = 'any2convert.com';
            $shouldRedirect = true;
        }

        // Clean /public prefix
        if (str_starts_with($path, '/public/')) {
            $path = substr($path, 7);
            $shouldRedirect = true;
        } elseif ($path === '/public') {
            $path = '/';
            $shouldRedirect = true;
        }

        // Clean /index.php prefix
        if (preg_match('#^/index\.php(/|$)#i', $path)) {
            $path = preg_replace('#^/index\.php(/|$)#i', '$1', $path);
            $shouldRedirect = true;
        }

        // Clean trailing .php
        if (str_ends_with($path, '.php')) {
            $path = substr($path, 0, -4);
            $shouldRedirect = true;
        }

        // Clean trailing slash if not root
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
            $shouldRedirect = true;
        }

        if ($path === '') {
            $path = '/';
        }

        if ($shouldRedirect) {
            $scheme = ($targetHost === 'any2convert.com') ? 'https' : $request->getScheme();
            $query = $request->getQueryString();
            $targetUrl = $scheme . '://' . $targetHost . $path . ($query ? '?' . $query : '');

            return redirect($targetUrl, 301);
        }

        return $next($request);
    }
}
