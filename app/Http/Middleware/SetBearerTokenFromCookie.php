<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

use function Psy\debug;

class SetBearerTokenFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the token is available in the cookies
        $token = $request->cookie('token');
        if ($token) {
            // Set the token as a Bearer token in the Authorization header
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
        return $next($request);
    }
}
