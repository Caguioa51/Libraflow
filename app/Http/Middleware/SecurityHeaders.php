<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply security headers in production environment
        if (app()->environment('production')) {
            $securityConfig = config('security');
            $headers = $securityConfig['headers'] ?? [];

            foreach ($headers as $header => $value) {
                $response->headers->set($header, $value);
            }

            // Add additional security headers
            $response->headers->set('X-Powered-By', ''); // Remove server signature
        }

        return $response;
    }
}
