<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Services\IMustVerifyMobile;

class EnsureMobileIsVerifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $req, Closure $next): Response
    {
        if(! $req->user() ||
            ($req->user() instanceof IMustVerifyMobile && 
            ! $req->user()->hasVerifiedMobile())){
                return $req->expectsJson()
                    ? abort(403, 'Your mobile number is not verified')
                    : redirect(route("verification-mobile.notice"));
            }

        return $next($req);
    }
}
