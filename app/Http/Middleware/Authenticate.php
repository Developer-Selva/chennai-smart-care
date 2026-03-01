<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Redirect unauthenticated users to the correct login page based on the guard.
     *
     * - auth:admin guard  → /admin/login
     * - auth (user) guard → /user/login
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null; // API — return 401, no redirect
        }

        // Detect admin routes by URL prefix or guard
        if ($this->isAdminRoute($request)) {
            return route('admin.login');
        }

        return route('user.login');
    }

    private function isAdminRoute(Request $request): bool
    {
        return str_starts_with($request->path(), 'admin');
    }
}