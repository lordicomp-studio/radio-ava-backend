<?php

namespace App\Http\Middleware;

use App\Enums\UserLevelEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()
            && (auth()->user()->level === UserLevelEnum::Seller || auth()->user()->level === UserLevelEnum::Admin))
        {
            return $next($request);
        }else{
            abort(401, 'Access denied for user level.');
        }
    }
}
