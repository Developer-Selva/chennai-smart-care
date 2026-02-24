<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user'  => $request->user('web')
                    ? $request->user('web')->only(['id', 'name', 'phone', 'email', 'loyalty_points'])
                    : null,
                'admin' => $request->user('admin')
                    ? $request->user('admin')->only(['id', 'name', 'email', 'role'])
                    : null,
            ],
            'flash' => [
                'success' => session('success'),
                'error'   => session('error'),
            ],
            'pending_count' => cache()->remember('admin_pending_count', 60, function () {
                return \App\Models\Booking::where('status', 'pending')->count();
            }),
        ]);
    }
}