<?php

namespace App\Http\Middleware;

use App\Services\JwtTokenService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiJwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $header = (string) $request->header('Authorization');

        if (! str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'Token JWT ausente.'], 401);
        }

        $user = app(JwtTokenService::class)->userFromToken(trim(substr($header, 7)));

        if ($user === null) {
            return response()->json(['message' => 'Token JWT inválido ou expirado.'], 401);
        }

        $request->setUserResolver(static fn () => $user);

        return $next($request);
    }
}
