<?php

namespace App\Support;

class Canonical
{
    /**
     * Generate the absolute canonical URL for the current request or a given path.
     * Always normalizes to https://any2convert.com and removes /public, /index.php, and trailing slashes.
     */
    public static function url(?string $customPath = null): string
    {
        if ($customPath !== null) {
            $path = $customPath;
        } else {
            $path = request()->getPathInfo();
        }

        // Strip leading /public if present
        if (str_starts_with($path, '/public/')) {
            $path = substr($path, 7);
        } elseif ($path === '/public') {
            $path = '/';
        }

        // Strip /index.php or trailing .php
        $path = preg_replace('#^/index\.php(/|$)#i', '$1', $path);
        $path = preg_replace('#\.php$#i', '', $path);

        // Strip trailing slash unless root
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        if ($path === '' || $path === '/') {
            return 'https://any2convert.com/';
        }

        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        return 'https://any2convert.com' . $path;
    }
}
