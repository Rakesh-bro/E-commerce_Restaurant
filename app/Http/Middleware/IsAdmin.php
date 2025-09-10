<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle($request, Closure $next)
{
    if (auth()->check() && auth()->user()->is_admin == 1) {
        return $next($request);
    }
    
    abort(403, 'Unauthorized');
}
}
