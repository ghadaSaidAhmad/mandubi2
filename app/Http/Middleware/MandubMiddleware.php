<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MandubMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (auth()->user()->tokenCan('role:mandub')) {
            return $next($request);
        }

        return response()->json('Not Authorized', 401);
    }
}
