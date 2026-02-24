<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Customers/Index', [
            'customers' => User::withCount('bookings')
                ->latest()
                ->paginate(30),
        ]);
    }

    public function show(int $id): Response
    {
        $customer = User::withCount('bookings')
            ->with(['bookings' => fn($q) => $q->latest()->limit(10)])
            ->findOrFail($id);

        return Inertia::render('Admin/Customers/Show', [
            'customer' => $customer,
        ]);
    }
}