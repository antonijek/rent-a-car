<?php

namespace App\Http\Middleware;

use Closure;

class TokenAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        // Perform token authentication logic here
        $token = $request->header('Authorization');

        if ($token !== 'YOUR_TOKEN_VALUE') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Token is valid, continue to the next middleware or the route handler
        return $next($request);
    }
}
