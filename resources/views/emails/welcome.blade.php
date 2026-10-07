@extends('emails.base')

@section('title', 'Welcome to ' . config('app.name'))

@section('content')
    <h1 style="margin:0 0 16px;font-size:22px;color:#111827;">Hi {{ $user->name }},</h1>

    <p style="margin:0 0 16px;">
        Welcome to {{ config('app.name') }}! We're glad to have you on board.
    </p>

    <p style="margin:0 0 24px;">
        You can start using your account right away:
    </p>

    <p style="margin:0 0 24px;text-align:center;">
        <a href="{{ config('app.url') }}"
           style="display:inline-block;padding:12px 20px;background:#2563eb;color:#ffffff;
                  text-decoration:none;border-radius:6px;font-weight:600;">
            Open {{ config('app.name') }}
        </a>
    </p>

    <p style="margin:0;color:#6b7280;font-size:13px;">
        If you didn't create this account, you can safely ignore this email.
    </p>
@endsection