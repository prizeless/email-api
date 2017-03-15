<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class BasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = env('API_USER');
        $password = env('API_PASSWORD');

        if ($request->getUser() !== $user || $password !== $request->getPassword()) {
            return new JsonResponse('Username or password incorrect', 401);
        }

        return $next($request);
    }
}
