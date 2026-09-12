<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware {
    public function handle( Request $request, Closure $next, string ...$roles ): Response {
        if(! $request->user()) {
            abort(401);
        }
        
        $userRole = $request->user()->role;
        
        if($userRole instanceof \BackedEnum) {
            $userRole = $userRole->value;
        }
        
        if(! in_array($userRole, $roles, true)) {
            abort(403);
        }
        
        return $next($request);        
    }
}
