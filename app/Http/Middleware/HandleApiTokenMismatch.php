<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class HandleApiTokenMismatch
{
    public function handle($request, Closure $next)
    {
        try { 
            return $next($request);
        } catch (TokenMismatchException $e) {
            return response()->json(['error' => 'Access denied from using this system.'], 403);
        }
    }
}
