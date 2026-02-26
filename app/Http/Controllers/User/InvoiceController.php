<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function show(Invoice $invoice): Response|\Illuminate\Http\Response
    {
        $user = auth('web')->user();

        // Only the booking owner can see this invoice
        abort_if($invoice->user_id !== $user->id, 403);
        abort_if($invoice->status === 'void', 404);

        $invoice->loadMissing(['items', 'booking.technician']);

        if (request()->query('download')) {
            $html = view('invoices.invoice', [
                'invoice' => $invoice,
                'booking' => $invoice->booking,
            ])->render();

            return response($html, 200, [
                'Content-Type'        => 'text/html',
                'Content-Disposition' => "inline; filename=\"{$invoice->invoice_number}.html\"",
            ]);
        }

        return Inertia::render('User/InvoiceView', [
            'invoice' => $invoice->append(['is_paid', 'is_sent', 'status_label']),
            'booking' => $invoice->booking,
        ]);
    }
}