<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol {
    public function handle($request, Closure $next)
    {
        if ($request->header('x-forwarded-proto') !== 'https' && !$request->secure() && env('REDIRECT_HTTPS') == true) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
