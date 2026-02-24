<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ConsultationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Consultations/Index', [
            'consultations' => Consultation::latest()->paginate(30),
        ]);
    }

    public function show(int $id): Response
    {
        return Inertia::render('Admin/Consultations/Show', [
            'consultation' => Consultation::findOrFail($id),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'status'      => 'required|in:new,contacted,converted,closed',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        Consultation::findOrFail($id)->update($data);

        return back()->with('success', 'Consultation updated.');
    }
}