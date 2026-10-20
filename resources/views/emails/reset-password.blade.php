@extends('emails.base')

@section('title', 'Reset Your Password')

@section('content')
    <h1 style="margin:0 0 16px;font-size:22px;color:#111827;">
        Reset Your Password
    </h1>

    <p style="margin:0 0 16px;">
        Hi {{ $user->name }}, we received a request to reset the password for your account.
    </p>

    <p style="margin:0 0 24px;text-align:center;">
        <a href="{{ $resetUrl }}"
           style="display:inline-block;padding:12px 20px;background:#2563eb;color:#ffffff;
                  text-decoration:none;border-radius:6px;font-weight:600;">
            Reset Password
        </a>
    </p>

    <p style="margin:0;color:#6b7280;font-size:13px;">
        If you didn't request a password reset, you can safely ignore this email. This link will expire in 60 minutes.
    </p>
@endsection