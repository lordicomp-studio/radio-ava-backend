<?php

namespace App\Http\Middleware;

use App\Enums\UserLevelEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user() && auth()->user()?->level === UserLevelEnum::Admin){
            return $next($request);
        }else{
            $middlewareGroups = $request->route()->gatherMiddleware();

            if (in_array('api', $middlewareGroups))
                abort(401, 'Access denied for user level.');
            else //if (in_array('web', $middlewareGroups))
                return redirect('/admin/auth/login');
        }
    }
}
