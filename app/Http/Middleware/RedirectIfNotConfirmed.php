<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotConfirmed
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
        if( ! request()->user()->confirmed) return redirect('/threads')->with('flash', 'You must confirm your email address.');

        return $next($request);
    }
}
