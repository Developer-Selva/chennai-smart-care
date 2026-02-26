<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    const DEFAULT_TERMS = "1. Payment is due upon receipt of invoice.\n" .
                          "2. All repairs carry a 6-month service warranty.\n" .
                          "3. Warranty covers the same issue recurrence only.\n" .
                          "4. For warranty claims, call +91 94449 00470.\n" .
                          "5. This invoice is computer generated.";

    /**
     * Build a SYSTEM invoice — auto-populated from booking items.
     * Returns an unsaved preview array for the frontend form.
     */
    public function buildSystemPreview(Booking $booking): array
    {
        $booking->loadMissing(['items.service', 'user']);

        $items = $booking->items->map((fn ($item, $i) => [
            'description' => $item->service_name,
            'category'    => 'service',
            'unit_price'  => (float) $item->unit_price,
            'quantity'    => (int) $item->quantity,
            'total'       => (float) $item->total_price,
            'sort_order'  => $i,
        ]))->values()->toArray();

        $subtotal = collect($items)->sum('total');

        return [
            'type'              => 'system',
            'items'             => $items,
            'subtotal'          => $subtotal,
            'discount_amount'   => (float) ($booking->discount_amount ?? 0),
            'discount_percent'  => 0,
            'tax_percent'       => 0,
            'tax_amount'        => 0,
            'total'             => $subtotal - (float) ($booking->discount_amount ?? 0),
            'payment_method'    => 'cash',
            'payment_reference' => '',
            'notes'             => '',
            'terms'             => self::DEFAULT_TERMS,
            'customer_name'     => $booking->customer_name,
            'customer_phone'    => $booking->customer_phone ?? '',
            'customer_email'    => $booking->customer_email ?? '',
            'customer_address'  => $booking->address . ($booking->area ? ', ' . $booking->area : '') . ($booking->pincode ? ' - ' . $booking->pincode : ''),
        ];
    }

    /**
     * Build a blank CUSTOM invoice template for the booking.
     */
    public function buildCustomPreview(Booking $booking): array
    {
        $booking->loadMissing(['items.service', 'user']);

        return [
            'type'              => 'custom',
            'items'             => [],
            'subtotal'          => 0,
            'discount_amount'   => 0,
            'discount_percent'  => 0,
            'tax_percent'       => 18,   // default GST 18%
            'tax_amount'        => 0,
            'total'             => 0,
            'payment_method'    => 'cash',
            'payment_reference' => '',
            'notes'             => '',
            'terms'             => self::DEFAULT_TERMS,
            'customer_name'     => $booking->customer_name,
            'customer_phone'    => $booking->customer_phone ?? '',
            'customer_email'    => $booking->customer_email ?? '',
            'customer_address'  => $booking->address . ($booking->area ? ', ' . $booking->area : '') . ($booking->pincode ? ' - ' . $booking->pincode : ''),
        ];
    }

    /**
     * Create & persist an invoice from validated admin input.
     */
    public function createInvoice(Booking $booking, array $data, $admin): Invoice
    {
        return DB::transaction(function () use ($booking, $data, $admin) {

            // Recalculate totals server-side (never trust client)
            $items    = $data['items'] ?? [];
            $subtotal = collect($items)->sum(fn ($i) => (float) $i['unit_price'] * (float) $i['quantity']);

            $discountPct = (float) ($data['discount_percent'] ?? 0);
            $discountAmt = $discountPct > 0
                ? round($subtotal * $discountPct / 100, 2)
                : (float) ($data['discount_amount'] ?? 0);

            $afterDiscount = $subtotal - $discountAmt;

            $taxPct = (float) ($data['tax_percent'] ?? 0);
            $taxAmt = $taxPct > 0 ? round($afterDiscount * $taxPct / 100, 2) : 0;

            $total = $afterDiscount + $taxAmt;

            $invoice = Invoice::create([
                'invoice_number'    => Invoice::generateNumber(),
                'booking_id'        => $booking->id,
                'user_id'           => $booking->user_id,
                'admin_id'          => $admin->id,
                'type'              => $data['type'] ?? 'system',
                'status'            => 'draft',
                'customer_name'     => $data['customer_name']    ?? $booking->customer_name,
                'customer_phone'    => $data['customer_phone']   ?? $booking->customer_phone,
                'customer_email'    => $data['customer_email']   ?? $booking->customer_email,
                'customer_address'  => $data['customer_address'] ?? $booking->address,
                'business_name'     => 'Chennai Smart Care',
                'business_phone'    => '+91 94449 00470',
                'business_email'    => 'support@chennaismartcare.com',
                'business_address'  => 'Chennai, Tamil Nadu - 600001',
                'business_gstin'    => config('app.gstin', null),
                'subtotal'          => $subtotal,
                'discount_amount'   => $discountAmt,
                'discount_percent'  => $discountPct,
                'tax_amount'        => $taxAmt,
                'tax_percent'       => $taxPct,
                'total'             => $total,
                'payment_method'    => $data['payment_method']    ?? 'pending',
                'payment_reference' => $data['payment_reference'] ?? null,
                'paid_at'           => in_array($data['payment_method'] ?? '', ['cash', 'upi', 'card', 'bank_transfer'])
                                        ? now() : null,
                'notes'             => $data['notes'] ?? null,
                'terms'             => $data['terms'] ?? self::DEFAULT_TERMS,
            ]);

            if ($invoice->paid_at) {
                $invoice->update(['status' => 'paid']);
            }

            // Persist line items
            foreach ($items as $i => $item) {
                $qty   = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => $item['description'],
                    'category'    => $item['category'] ?? 'service',
                    'unit_price'  => $price,
                    'quantity'    => $qty,
                    'total'       => round($price * $qty, 2),
                    'sort_order'  => $i,
                ]);
            }

            // Sync final amount back to booking
            $booking->update(['final_amount' => $total]);

            Log::info("Invoice created: {$invoice->invoice_number} for {$booking->booking_number}");

            return $invoice->load('items');
        });
    }

    /**
     * Update an existing draft invoice.
     */
    public function updateInvoice(Invoice $invoice, array $data): Invoice
    {
        if ($invoice->status !== 'draft') {
            throw new \RuntimeException('Only draft invoices can be edited.');
        }

        return DB::transaction(function () use ($invoice, $data) {
            $items    = $data['items'] ?? [];
            $subtotal = collect($items)->sum(fn ($i) => (float) $i['unit_price'] * (float) $i['quantity']);

            $discountPct = (float) ($data['discount_percent'] ?? 0);
            $discountAmt = $discountPct > 0
                ? round($subtotal * $discountPct / 100, 2)
                : (float) ($data['discount_amount'] ?? 0);

            $afterDiscount = $subtotal - $discountAmt;
            $taxPct = (float) ($data['tax_percent'] ?? 0);
            $taxAmt = $taxPct > 0 ? round($afterDiscount * $taxPct / 100, 2) : 0;
            $total  = $afterDiscount + $taxAmt;

            $invoice->items()->delete();

            foreach ($items as $i => $item) {
                $qty   = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => $item['description'],
                    'category'    => $item['category'] ?? 'service',
                    'unit_price'  => $price,
                    'quantity'    => $qty,
                    'total'       => round($price * $qty, 2),
                    'sort_order'  => $i,
                ]);
            }

            $invoice->update([
                'type'              => $data['type'] ?? $invoice->type,
                'subtotal'          => $subtotal,
                'discount_amount'   => $discountAmt,
                'discount_percent'  => $discountPct,
                'tax_amount'        => $taxAmt,
                'tax_percent'       => $taxPct,
                'total'             => $total,
                'payment_method'    => $data['payment_method']    ?? $invoice->payment_method,
                'payment_reference' => $data['payment_reference'] ?? $invoice->payment_reference,
                'notes'             => $data['notes']  ?? $invoice->notes,
                'terms'             => $data['terms']  ?? $invoice->terms,
                'customer_name'     => $data['customer_name']    ?? $invoice->customer_name,
                'customer_phone'    => $data['customer_phone']   ?? $invoice->customer_phone,
                'customer_email'    => $data['customer_email']   ?? $invoice->customer_email,
                'customer_address'  => $data['customer_address'] ?? $invoice->customer_address,
            ]);

            $invoice->booking?->update(['final_amount' => $total]);

            return $invoice->fresh('items');
        });
    }

    /**
     * Mark invoice as sent and record timestamp.
     */
    public function markSent(Invoice $invoice): Invoice
    {
        $invoice->update([
            'status'  => 'sent',
            'sent_at' => now(),
        ]);
        return $invoice->fresh('items');
    }

    /**
     * Mark invoice as paid.
     */
    public function markPaid(Invoice $invoice, string $method, ?string $reference = null): Invoice
    {
        $invoice->update([
            'status'            => 'paid',
            'payment_method'    => $method,
            'payment_reference' => $reference,
            'paid_at'           => now(),
        ]);
        return $invoice->fresh('items');
    }
}