<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Services/Index', [
            'services'   => Service::with('category')->orderBy('category_id')->orderBy('sort_order')->get(),
            'categories' => ServiceCategory::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Services/Create', [
            'categories' => ServiceCategory::active()->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id'      => 'required|exists:service_categories,id',
            'name'             => 'required|string|max:150',
            'slug'             => 'required|string|unique:services,slug',
            'base_price'       => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'duration_estimate'=> 'nullable|string|max:50',
            'features'         => 'nullable|array',
            'is_active'        => 'boolean',
            'is_featured'      => 'boolean',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:320',
        ]);

        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function edit(Service $service): Response
    {
        return Inertia::render('Admin/Services/Edit', [
            'service'    => $service,
            'categories' => ServiceCategory::active()->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'category_id'      => 'required|exists:service_categories,id',
            'name'             => 'required|string|max:150',
            'base_price'       => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'duration_estimate'=> 'nullable|string|max:50',
            'features'         => 'nullable|array',
            'is_active'        => 'boolean',
            'is_featured'      => 'boolean',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:320',
        ]);

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->update(['is_active' => false]);

        return redirect()->route('admin.services.index')->with('success', 'Service deactivated.');
    }
}