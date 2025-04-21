<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLastOnlineMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()){
            \Illuminate\Support\Facades\DB::table('users')
                ->where('id', auth()->user()->id)
                ->update(['last_online_at' => now()]);
        }
        return $next($request);
    }
}
