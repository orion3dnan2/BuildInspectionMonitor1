<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SystemAccessMiddleware
{
    public function handle(Request $request, Closure $next, string $system): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        if (!$user->hasSystemAccess($system)) {
            abort(403, 'ليس لديك صلاحية للوصول إلى هذا النظام');
        }
        
        return $next($request);
    }
}
