<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure session always has a locale; FORCE default to Indonesian ('id')
        if (!$request->session()->has('locale')) {
            $request->session()->put('locale', 'id');
        }
        $locale = $request->session()->get('locale', 'id');
        app()->setLocale($locale);
        return $next($request);
    }
}
