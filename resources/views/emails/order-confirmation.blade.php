@extends('emails.base')

@section('title', 'Order Confirmation #' . $order->number)

@section('content')
    <h1 style="margin:0 0 16px;font-size:22px;color:#111827;">
        Thank you for your order!
    </h1>

    <p style="margin:0 0 16px;">
        Hi {{ $order->customer_name }}, we've received your order <strong>#{{ $order->number }}</strong> and are getting it ready.
    </p>

    <table width="100%" cellpadding="0" cellspacing="0" style="margin:24px 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
        @foreach ($order->items as $item)
            <tr>
                <td style="padding: 12px 0; color: #333;">
                    <strong>{{ $item->product_name }}</strong><br>
                    <span style="color: #6b7280; font-size: 13px;">Qty: {{ $item->quantity }}</span>
                </td>
                <td align="right" style="padding: 12px 0; color: #333;">
                    {{ $item->total_amount->formatToLocale('en_US') }}
                </td>
            </tr>
        @endforeach
    </table>

    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
        <tr>
            <td align="right" style="padding: 4px 0; font-weight: bold; color: #111827;">
                Total:
            </td>
            <td align="right" width="100" style="padding: 4px 0; font-weight: bold; color: #111827; font-size: 18px;">
                {{ $order->total_amount->formatToLocale('en_US') }}
            </td>
        </tr>
        <tr>
            <td align="right" style="padding: 4px 0; color: #6b7280; font-size: 13px;">
                Payment Method:
            </td>
            <td align="right" style="padding: 4px 0; color: #6b7280; font-size: 13px;">
                {{ $order->paymentMethod->name ?? 'N/A' }}
            </td>
        </tr>
    </table>

    <h2 style="margin:24px 0 8px; font-size: 16px; color: #111827;">Delivery Details</h2>
    <p style="margin:0; color: #4b5563; line-height: 1.5;">
        {{ $order->customer_name }}<br>
        {{ $order->customer_address }}<br>
        {{ $order->customer_city }} {{ $order->customer_index }}<br>
        {{ $order->customer_phone }}
    </p>

    @if($order->note)
        <h2 style="margin:24px 0 8px; font-size: 16px; color: #111827;">Order Note</h2>
        <p style="margin:0; color: #4b5563; font-style: italic;">
            "{{ $order->note }}"
        </p>
    @endif

    <p style="margin:32px 0 0; color: #6b7280; font-size: 13px;">
        If you have any questions about your order, please reply to this email.
    </p>
@endsection