<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f7;font-family:Arial,Helvetica,sans-serif;color:#333;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f4f7;padding:24px 0;">
        <tr>
            <td align="center">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"
                       style="background:#ffffff;border-radius:8px;overflow:hidden;max-width:600px;width:100%;">

                    {{-- Header --}}
                    <tr>
                        <td align="center" style="background:#111827;padding:24px;">
                            <span style="color:#ffffff;font-size:20px;font-weight:600;letter-spacing:0.5px;">
                                {{ config('app.name') }}
                            </span>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td align="center" style="padding:24px;color:#6b7280;font-size:12px;line-height:1.5;">
                            <p style="margin:0 0 8px;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                            <p style="margin:0;">
                                If you have any questions, reply to this email and we'll get back to you.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>