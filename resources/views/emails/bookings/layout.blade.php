{{-- resources/views/emails/bookings/layout.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title') | Chennai Smart Care</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      background-color: #f3f4f6;
      color: #111827;
      -webkit-font-smoothing: antialiased;
    }
    .wrapper   { max-width: 600px; margin: 32px auto; padding: 0 16px 40px; }
    .card      { background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.08); }
    .header    { background: linear-gradient(135deg, #1d4ed8 0%, #4338ca 100%); padding: 32px 36px; }
    .header h1 { color: #fff; font-size: 22px; font-weight: 700; letter-spacing: -.3px; }
    .header p  { color: #bfdbfe; font-size: 14px; margin-top: 4px; }
    .body      { padding: 32px 36px; }
    .greeting  { font-size: 16px; color: #374151; margin-bottom: 20px; }
    .info-box  {
      background: #f8fafc; border: 1px solid #e2e8f0;
      border-radius: 12px; padding: 20px 24px; margin: 20px 0;
    }
    .info-row  { display: flex; justify-content: space-between; align-items: flex-start; padding: 8px 0; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-label { color: #6b7280; font-weight: 500; min-width: 130px; }
    .info-value { color: #111827; font-weight: 600; text-align: right; }
    .btn {
      display: inline-block; background: #2563eb; color: #fff !important;
      text-decoration: none; padding: 13px 28px; border-radius: 10px;
      font-weight: 700; font-size: 15px; margin: 20px 0;
    }
    .btn-outline {
      display: inline-block; border: 2px solid #2563eb; color: #2563eb !important;
      text-decoration: none; padding: 11px 24px; border-radius: 10px;
      font-weight: 600; font-size: 14px; margin-left: 12px;
    }
    .status-badge {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 5px 14px; border-radius: 999px; font-size: 13px; font-weight: 600;
    }
    .status-confirmed  { background: #d1fae5; color: #065f46; }
    .status-pending    { background: #fef3c7; color: #92400e; }
    .status-rescheduled{ background: #ffedd5; color: #9a3412; }
    .divider { border: none; border-top: 1px solid #f1f5f9; margin: 24px 0; }
    .footer { padding: 24px 36px; background: #f9fafb; border-top: 1px solid #f1f5f9; }
    .footer p { font-size: 12px; color: #9ca3af; line-height: 1.6; }
    .footer a { color: #2563eb; text-decoration: none; }
    .help-text { font-size: 14px; color: #6b7280; margin-top: 20px; line-height: 1.6; }
    .highlight { color: #2563eb; font-weight: 600; }
    @media (max-width: 480px) {
      .body, .header, .footer { padding-left: 20px; padding-right: 20px; }
      .info-row { flex-direction: column; gap: 2px; }
      .info-value { text-align: left; }
      .btn-outline { margin-left: 0; margin-top: 10px; display: block; text-align: center; }
    }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="card">

    {{-- Header --}}
    <div class="header">
      <table width="100%"><tr>
        <td>
          <h1>Chennai Smart Care</h1>
          <p>Expert Appliance Repair · Chennai</p>
        </td>
        <td align="right" style="color:#93c5fd;font-size:36px;">🔧</td>
      </tr></table>
    </div>

    {{-- Body --}}
    <div class="body">
      @yield('body')
    </div>

    {{-- Footer --}}
    <div class="footer">
      <p>
        <strong>Chennai Smart Care</strong> · Serving all areas within Chennai (20km radius)<br />
        📞 <a href="tel:{{ $phone }}">{{ $phone }}</a> &nbsp;·&nbsp;
        🌐 <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
      </p>
      <p style="margin-top:10px;">
        This is an automated email. Please do not reply directly to this message.
        If you need help, please call us or
        <a href="https://wa.me/91{{ preg_replace('/\D/', '', $phone) }}">chat on WhatsApp</a>.
      </p>
    </div>

  </div>
</div>
</body>
</html>