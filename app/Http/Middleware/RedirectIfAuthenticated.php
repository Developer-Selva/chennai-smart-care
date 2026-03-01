<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * Redirects already-authenticated users away from guest-only pages
     * (login, register) to the correct dashboard.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Admin guard → admin dashboard
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                // Default user guard → user dashboard
                return redirect()->route('user.dashboard');
            }
        }

        return $next($request);
    }
}