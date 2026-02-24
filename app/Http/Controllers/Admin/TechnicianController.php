<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TechnicianController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Technicians/Index', [
            'technicians' => Technician::withCount('bookings')->latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Technicians/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'         => 'required|string|max:100',
            'phone'        => 'required|string|unique:technicians,phone',
            'email'        => 'nullable|email|unique:technicians,email',
            'skills'       => 'nullable|array',
            'is_available' => 'boolean',
            'is_active'    => 'boolean',
            'notes'        => 'nullable|string|max:500',
        ]);

        Technician::create($data);

        return redirect()->route('admin.technicians.index')->with('success', 'Technician added.');
    }

    public function edit(Technician $technician): Response
    {
        return Inertia::render('Admin/Technicians/Edit', [
            'technician' => $technician,
        ]);
    }

    public function update(Request $request, Technician $technician): RedirectResponse
    {
        $data = $request->validate([
            'name'         => 'required|string|max:100',
            'phone'        => 'required|string|unique:technicians,phone,' . $technician->id,
            'email'        => 'nullable|email|unique:technicians,email,' . $technician->id,
            'skills'       => 'nullable|array',
            'is_available' => 'boolean',
            'is_active'    => 'boolean',
            'notes'        => 'nullable|string|max:500',
        ]);

        $technician->update($data);

        return redirect()->route('admin.technicians.index')->with('success', 'Technician updated.');
    }

    public function destroy(Technician $technician): RedirectResponse
    {
        $technician->update(['is_active' => false, 'is_available' => false]);

        return redirect()->route('admin.technicians.index')->with('success', 'Technician deactivated.');
    }
}