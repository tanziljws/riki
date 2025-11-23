<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReplaceStoragePath
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Hanya replace untuk HTML response
        if ($response instanceof \Illuminate\Http\Response && 
            str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            $content = $response->getContent();
            // Replace semua /storage/ dengan /files/ di HTML
            $content = str_replace('/storage/', '/files/', $content);
            $content = str_replace('"/storage/', '"/files/', $content);
            $content = str_replace("'/storage/", "'/files/", $content);
            $content = str_replace('url(/storage/', 'url(/files/', $content);
            $response->setContent($content);
        }
        
        return $response;
    }
}

