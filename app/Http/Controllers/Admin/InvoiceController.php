<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Invoice;
use App\Services\InvoiceService;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceService  $invoiceService,
        protected WhatsAppService $whatsApp,
    ) {}

    /**
     * GET /admin/bookings/{booking}/invoice/create
     * Show the invoice builder page.
     */
    public function create(Booking $booking): Response
    {
        $booking->loadMissing(['items.service', 'user', 'technician']);

        $existingInvoice = Invoice::where('booking_id', $booking->id)
            ->with('items')
            ->latest()
            ->first();

        $systemPreview = $this->invoiceService->buildSystemPreview($booking);
        $customPreview = $this->invoiceService->buildCustomPreview($booking);

        return Inertia::render('Admin/Bookings/InvoiceCreate', [
            'booking'         => $booking,
            'existing_invoice'=> $existingInvoice,
            'system_preview'  => $systemPreview,
            'custom_preview'  => $customPreview,
        ]);
    }

    /**
     * POST /admin/bookings/{booking}/invoice
     * Create a new invoice.
     */
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'type'               => 'required|in:system,custom',
            'items'              => 'required|array|min:1',
            'items.*.description'=> 'required|string|max:255',
            'items.*.category'   => 'nullable|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity'   => 'required|numeric|min:0.01',
            'discount_amount'    => 'nullable|numeric|min:0',
            'discount_percent'   => 'nullable|numeric|min:0|max:100',
            'tax_percent'        => 'nullable|numeric|min:0|max:100',
            'payment_method'     => 'required|in:cash,upi,card,bank_transfer,pending',
            'payment_reference'  => 'nullable|string|max:100',
            'notes'              => 'nullable|string|max:1000',
            'terms'              => 'nullable|string|max:2000',
            'customer_name'      => 'required|string|max:150',
            'customer_phone'     => 'required|string|max:20',
            'customer_email'     => 'nullable|email|max:150',
            'customer_address'   => 'required|string|max:500',
        ]);

        $invoice = $this->invoiceService->createInvoice($booking, $data, auth('admin')->user());

        return redirect("/admin/bookings/{$booking->id}/invoice/{$invoice->id}")
            ->with('success', "Invoice {$invoice->invoice_number} created successfully.");
    }

    /**
     * GET /admin/bookings/{booking}/invoice/{invoice}
     * View invoice detail / PDF preview.
     */
    public function show(Booking $booking, Invoice $invoice): Response
    {
        $invoice->loadMissing('items');
        $booking->loadMissing(['user', 'technician', 'items.service']);

        return Inertia::render('Admin/Bookings/InvoiceShow', [
            'booking' => $booking,
            'invoice' => $invoice->append(['is_paid', 'is_sent', 'status_label']),
        ]);
    }

    /**
     * PUT /admin/bookings/{booking}/invoice/{invoice}
     * Update a draft invoice.
     */
    public function update(Request $request, Booking $booking, Invoice $invoice): RedirectResponse
    {
        if ($invoice->status !== 'draft') {
            return back()->withErrors(['invoice' => 'Only draft invoices can be edited.']);
        }

        $data = $request->validate([
            'type'               => 'required|in:system,custom',
            'items'              => 'required|array|min:1',
            'items.*.description'=> 'required|string|max:255',
            'items.*.category'   => 'nullable|string|max:50',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.quantity'   => 'required|numeric|min:0.01',
            'discount_amount'    => 'nullable|numeric|min:0',
            'discount_percent'   => 'nullable|numeric|min:0|max:100',
            'tax_percent'        => 'nullable|numeric|min:0|max:100',
            'payment_method'     => 'required|in:cash,upi,card,bank_transfer,pending',
            'payment_reference'  => 'nullable|string|max:100',
            'notes'              => 'nullable|string|max:1000',
            'terms'              => 'nullable|string|max:2000',
            'customer_name'      => 'required|string|max:150',
            'customer_phone'     => 'required|string|max:20',
            'customer_email'     => 'nullable|email|max:150',
            'customer_address'   => 'required|string|max:500',
        ]);

        $this->invoiceService->updateInvoice($invoice, $data);

        return back()->with('success', 'Invoice updated.');
    }

    /**
     * PATCH /admin/bookings/{booking}/invoice/{invoice}/send
     * Send invoice to customer via WhatsApp and mark as sent.
     */
    public function send(Booking $booking, Invoice $invoice): JsonResponse
    {
        $invoice->loadMissing('items');

        $this->invoiceService->markSent($invoice);

        // Send WhatsApp notification
        dispatch(fn () => $this->whatsApp->sendInvoice($invoice));

        return response()->json([
            'success' => true,
            'message' => "Invoice sent to {$invoice->customer_phone} via WhatsApp.",
        ]);
    }

    /**
     * PATCH /admin/bookings/{booking}/invoice/{invoice}/mark-paid
     * Mark invoice as paid.
     */
    public function markPaid(Request $request, Booking $booking, Invoice $invoice): JsonResponse
    {
        $data = $request->validate([
            'payment_method'    => 'required|in:cash,upi,card,bank_transfer',
            'payment_reference' => 'nullable|string|max:100',
        ]);

        $invoice = $this->invoiceService->markPaid($invoice, $data['payment_method'], $data['payment_reference'] ?? null);

        return response()->json([
            'success' => true,
            'invoice' => $invoice->append(['is_paid', 'status_label']),
        ]);
    }

    /**
     * GET /admin/bookings/{booking}/invoice/{invoice}/download
     * Stream the invoice as HTML (for print/PDF).
     */
    public function download(Booking $booking, Invoice $invoice): \Illuminate\Http\Response
    {
        $invoice->loadMissing('items');

        $html = view('invoices.invoice', [
            'invoice' => $invoice,
            'booking' => $booking,
        ])->render();

        return response($html, 200, [
            'Content-Type'        => 'text/html',
            'Content-Disposition' => "inline; filename=\"{$invoice->invoice_number}.html\"",
        ]);
    }
}