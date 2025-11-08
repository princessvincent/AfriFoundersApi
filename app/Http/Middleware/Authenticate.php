<?php

namespace App\Http\Middleware;

class Authenticate
{
    protected function redirectTo($request): ?string
    {
        // For API requests, do not redirect
        if ($request->expectsJson()) {
            return null;
        }

        return route('login'); // only used for web routes
    }
}
