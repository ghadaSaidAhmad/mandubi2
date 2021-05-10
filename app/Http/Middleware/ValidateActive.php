<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateActive
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
        if ($request->user()->verification_code) {
            return $next($request);
        } else {
            abort(403); // or send your JSON response
        }
    }
}
