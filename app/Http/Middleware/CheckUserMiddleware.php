<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //check if mobile verified
        if (!auth()->user()->hasVerifiedMobile()) {
            $data = [
                'message' => 'mobile number not verified',
                'code' => 403
            ];
            return response()->json($data);
        }
        if (!auth()->user()->hasAdminVerified()) {
            $data = [
                'message' => 'plaese wait while admin verify your account',
                'code' => 403
            ];
            return response()->json($data);
        }
        if (!auth()->user()->hasCompleteRegister()) {
            $data = [
                'message' => 'plaese ',
                'code' => 403
            ];
            return response()->json($data);
        }
        return $next($request);


    }
}
