<?php

namespace App\Http\Middleware;

use Closure;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'errors' => [
                        'status' => false,
                        'code' => 401,
                        'message' => 'Unauthorized',
                    ],
                ], 401);
            }

            return $next($request);

        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json([
                'errors' => [
                    'status' => false,
                    'code' => 401,
                    'message' => $e->getMessage(),
                ],
            ], 401);
        }

    }
}
