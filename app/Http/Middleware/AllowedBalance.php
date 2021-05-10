<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;

class AllowedBalance
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
        $setting = Setting::first();

        if (auth()->user()->balance < $setting->allowable_balance) {
            return $next($request);
        }

        $data = [
            'message' => 'Your balance exceeded the limit, please pay to contine',
            'code' => 403
        ];
        return response()->json($data, 401);
    }
}
