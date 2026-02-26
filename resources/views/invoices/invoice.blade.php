<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $invoice->invoice_number }} — Chennai Smart Care</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 13px; color: #1a1a2e; background: #f8fafc; }

    .page { max-width: 760px; margin: 0 auto; background: #fff; }

    /* ── Header ─────────────────────────────── */
    .header { background: linear-gradient(135deg, #1d4ed8 0%, #3730a3 100%); color: #fff; padding: 36px 40px; }
    .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
    .brand { }
    .brand-name { font-size: 22px; font-weight: 800; letter-spacing: -0.5px; }
    .brand-sub  { font-size: 11px; opacity: .75; margin-top: 2px; }
    .inv-meta   { text-align: right; }
    .inv-number { font-size: 20px; font-weight: 700; font-family: monospace; }
    .inv-label  { font-size: 10px; text-transform: uppercase; letter-spacing: 1px; opacity: .7; }
    .status-badge {
      display: inline-block; margin-top: 8px;
      padding: 4px 14px; border-radius: 999px; font-size: 11px; font-weight: 700; letter-spacing: .5px;
    }
    .status-paid   { background: #22c55e; color: #fff; }
    .status-sent   { background: #f59e0b; color: #fff; }
    .status-draft  { background: rgba(255,255,255,.2); color: #fff; }

    /* ── Party info ─────────────────────────── */
    .parties { display: grid; grid-template-columns: 1fr 1fr; gap: 0; padding: 28px 40px; border-bottom: 1px solid #e5e7eb; }
    .party-block { }
    .party-block + .party-block { border-left: 1px solid #e5e7eb; padding-left: 28px; }
    .party-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6b7280; margin-bottom: 8px; }
    .party-name  { font-size: 15px; font-weight: 700; color: #111; margin-bottom: 4px; }
    .party-detail { font-size: 12px; color: #4b5563; line-height: 1.6; }

    /* ── Meta strip ─────────────────────────── */
    .meta-strip { display: grid; grid-template-columns: repeat(3,1fr); gap: 0; background: #f9fafb; border-bottom: 1px solid #e5e7eb; }
    .meta-cell  { padding: 14px 20px; }
    .meta-cell + .meta-cell { border-left: 1px solid #e5e7eb; }
    .meta-cell .label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .8px; color: #9ca3af; margin-bottom: 4px; }
    .meta-cell .value { font-size: 13px; font-weight: 600; color: #111; }

    /* ── Items table ────────────────────────── */
    .items-wrap { padding: 28px 40px; }
    .items-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6b7280; margin-bottom: 14px; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #f1f5f9; }
    th { padding: 10px 14px; text-align: left; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #6b7280; }
    th:last-child, td:last-child { text-align: right; }
    td { padding: 12px 14px; font-size: 13px; color: #374151; border-bottom: 1px solid #f3f4f6; }
    tr:last-child td { border-bottom: none; }
    .category-badge {
      display: inline-block; font-size: 10px; font-weight: 600; padding: 2px 8px;
      border-radius: 4px; margin-left: 6px; vertical-align: middle;
      background: #e0f2fe; color: #0369a1;
    }
    .category-labour { background: #fef3c7; color: #92400e; }
    .category-parts  { background: #f0fdf4; color: #166534; }
    .category-extra  { background: #fdf4ff; color: #7e22ce; }

    /* ── Totals ─────────────────────────────── */
    .totals-wrap { padding: 0 40px 28px; display: flex; justify-content: flex-end; }
    .totals-table { width: 280px; }
    .totals-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; color: #4b5563; }
    .totals-row.discount { color: #16a34a; }
    .totals-row.tax      { color: #6b7280; }
    .totals-divider { border: none; border-top: 2px solid #1d4ed8; margin: 8px 0; }
    .totals-final { display: flex; justify-content: space-between; padding: 10px 0 0; font-size: 17px; font-weight: 800; color: #1d4ed8; }

    /* ── Payment ────────────────────────────── */
    .payment-strip { margin: 0 40px 28px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; }
    .payment-strip.pending { background: #fffbeb; border-color: #fde68a; }
    .payment-dot { width: 10px; height: 10px; border-radius: 50%; background: #22c55e; flex-shrink: 0; }
    .payment-dot.pending { background: #f59e0b; }
    .payment-text { font-size: 13px; font-weight: 600; color: #15803d; }
    .payment-text.pending { color: #92400e; }
    .payment-ref { font-size: 11px; color: #6b7280; margin-top: 2px; }

    /* ── Notes / Terms ──────────────────────── */
    .notes-section { padding: 0 40px 28px; display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .notes-block .label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; margin-bottom: 8px; }
    .notes-block p { font-size: 11.5px; color: #6b7280; line-height: 1.7; white-space: pre-line; }

    /* ── Footer ─────────────────────────────── */
    .footer { background: #f1f5f9; padding: 18px 40px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e2e8f0; }
    .footer-left { font-size: 11px; color: #6b7280; }
    .footer-right { font-size: 11px; color: #9ca3af; }
    .warranty-badge { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; padding: 6px 12px; font-size: 11px; color: #1d4ed8; font-weight: 600; }

    /* ── Print ──────────────────────────────── */
    @media print {
      body { background: #fff; }
      .page { max-width: 100%; box-shadow: none; }
      .no-print { display: none !important; }
    }
  </style>
</head>
<body>
  <!-- Print / Download Button -->
  <div class="no-print" style="text-align:center; padding: 16px; background:#f1f5f9; border-bottom:1px solid #e2e8f0;">
    <button onclick="window.print()"
      style="background:#1d4ed8;color:#fff;padding:10px 28px;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">
      🖨️ Download / Print Invoice
    </button>
  </div>

  <div class="page">

    <!-- ── HEADER ── -->
    <div class="header">
      <div class="header-top">
        <div class="brand">
          <div class="brand-name">🔧 Chennai Smart Care</div>
          <div class="brand-sub">Expert Appliance Repair · Chennai</div>
          <div style="margin-top:10px; font-size:12px; opacity:.8;">
            {{ $invoice->business_phone }}<br>
            {{ $invoice->business_email }}<br>
            {{ $invoice->business_address }}
            @if($invoice->business_gstin)
              <br>GSTIN: {{ $invoice->business_gstin }}
            @endif
          </div>
        </div>
        <div class="inv-meta">
          <div class="inv-label">Invoice</div>
          <div class="inv-number">{{ $invoice->invoice_number }}</div>
          <span class="status-badge status-{{ $invoice->status }}">
            @if($invoice->status === 'paid') ✓ PAID
            @elseif($invoice->status === 'sent') SENT
            @else DRAFT
            @endif
          </span>
        </div>
      </div>
    </div>

    <!-- ── PARTIES ── -->
    <div class="parties">
      <div class="party-block">
        <div class="party-label">Bill To</div>
        <div class="party-name">{{ $invoice->customer_name }}</div>
        <div class="party-detail">
          📞 {{ $invoice->customer_phone }}<br>
          @if($invoice->customer_email) ✉ {{ $invoice->customer_email }}<br>@endif
          📍 {{ $invoice->customer_address }}
        </div>
      </div>
      <div class="party-block">
        <div class="party-label">Booking Reference</div>
        <div class="party-name">{{ $booking->booking_number }}</div>
        <div class="party-detail">
          📅 Service Date: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}<br>
          ⏰ Slot: {{ $booking->time_slot }}<br>
          @if($booking->technician)
            👨‍🔧 Tech: {{ $booking->technician->name }}
          @endif
        </div>
      </div>
    </div>

    <!-- ── META STRIP ── -->
    <div class="meta-strip">
      <div class="meta-cell">
        <div class="label">Invoice Date</div>
        <div class="value">{{ $invoice->created_at->format('d M Y') }}</div>
      </div>
      <div class="meta-cell">
        <div class="label">Invoice Type</div>
        <div class="value">{{ ucfirst($invoice->type) }}</div>
      </div>
      <div class="meta-cell">
        <div class="label">Due Date</div>
        <div class="value">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : 'Upon Receipt' }}</div>
      </div>
    </div>

    <!-- ── ITEMS ── -->
    <div class="items-wrap">
      <div class="items-title">Services & Charges</div>
      <table>
        <thead>
          <tr>
            <th style="width:50%">Description</th>
            <th style="width:15%;text-align:right">Unit Price</th>
            <th style="width:10%;text-align:right">Qty</th>
            <th style="width:15%;text-align:right">Amount</th>
          </tr>
        </thead>
        <tbody>
          @foreach($invoice->items as $item)
          <tr>
            <td>
              {{ $item->description }}
              @if($item->category && $item->category !== 'service')
                <span class="category-badge category-{{ $item->category }}">{{ ucfirst($item->category) }}</span>
              @endif
            </td>
            <td style="text-align:right">₹{{ number_format($item->unit_price, 2) }}</td>
            <td style="text-align:right">{{ rtrim(rtrim(number_format($item->quantity,2),'0'),'.') }}</td>
            <td style="text-align:right; font-weight:600">₹{{ number_format($item->total, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- ── TOTALS ── -->
    <div class="totals-wrap">
      <div class="totals-table">
        <div class="totals-row">
          <span>Subtotal</span>
          <span>₹{{ number_format($invoice->subtotal, 2) }}</span>
        </div>
        @if($invoice->discount_amount > 0)
        <div class="totals-row discount">
          <span>Discount{{ $invoice->discount_percent > 0 ? ' ('.$invoice->discount_percent.'%)' : '' }}</span>
          <span>−₹{{ number_format($invoice->discount_amount, 2) }}</span>
        </div>
        @endif
        @if($invoice->tax_amount > 0)
        <div class="totals-row tax">
          <span>GST ({{ $invoice->tax_percent }}%)</span>
          <span>+₹{{ number_format($invoice->tax_amount, 2) }}</span>
        </div>
        @endif
        <hr class="totals-divider">
        <div class="totals-final">
          <span>Total</span>
          <span>₹{{ number_format($invoice->total, 2) }}</span>
        </div>
      </div>
    </div>

    <!-- ── PAYMENT STATUS ── -->
    <div class="payment-strip {{ $invoice->status !== 'paid' ? 'pending' : '' }}">
      <div class="payment-dot {{ $invoice->status !== 'paid' ? 'pending' : '' }}"></div>
      <div>
        <div class="payment-text {{ $invoice->status !== 'paid' ? 'pending' : '' }}">
          @if($invoice->status === 'paid')
            Payment Received · {{ ucfirst($invoice->payment_method) }}
            @if($invoice->paid_at) · {{ $invoice->paid_at->format('d M Y, h:i A') }} @endif
          @else
            Payment Pending · Accepted: Cash / UPI / Card
          @endif
        </div>
        @if($invoice->payment_reference)
          <div class="payment-ref">Ref: {{ $invoice->payment_reference }}</div>
        @endif
      </div>
    </div>

    <!-- ── NOTES & TERMS ── -->
    @if($invoice->notes || $invoice->terms)
    <div class="notes-section">
      @if($invoice->notes)
      <div class="notes-block">
        <div class="label">Notes</div>
        <p>{{ $invoice->notes }}</p>
      </div>
      @endif
      @if($invoice->terms)
      <div class="notes-block">
        <div class="label">Terms & Conditions</div>
        <p>{{ $invoice->terms }}</p>
      </div>
      @endif
    </div>
    @endif

    <!-- ── FOOTER ── -->
    <div class="footer">
      <div class="footer-left">
        <div class="warranty-badge">🛡️ All repairs carry a 6-month service warranty</div>
      </div>
      <div class="footer-right">
        Thank you for choosing Chennai Smart Care!<br>
        <strong>+91 94449 00470 · chennaismartcare.com</strong>
      </div>
    </div>

  </div>
</body>
</html>