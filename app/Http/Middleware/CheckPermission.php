<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->permission !== 'admin' && $request->user()->permission !== 'manager'){
            return redirect()->route('home');
        }

        return $next($request);
    }
}
