<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Prevent infinite loop if they are already on the password change page or logging out
            if (!$request->is('password/change') && !$request->is('logout')) {
                return redirect('/password/change')->with('warning', 'You must change your default password before continuing.');
            }
        }

        return $next($request);
    }
}
