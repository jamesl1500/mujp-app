<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if(!$request->user()){
            return redirect('/');
        }

        $isAuhtorized = false;
        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                $isAuhtorized = true;
                break;
            }
        }

        if ($isAuhtorized) {
            return $next($request);
        }
        return redirect('/login');

        abort(401, 'This action is unauthorized.');
    }
}
