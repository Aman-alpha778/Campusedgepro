<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo Expiry Reminder</title>
  </head>
  <body style="margin:0; padding:0; background:#edf4ff; font-family:Arial, sans-serif; color:#10213a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#edf4ff; padding:24px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; background:#ffffff; border-radius:24px; overflow:hidden;">
            <tr>
              <td style="padding:28px;">
                <h1 style="margin-top:0;">Your demo access expires soon</h1>
                <p>Hello {{ $demoRequest->admin_name }},</p>
                <p>This is a reminder that your CampusEdgePro demo access for {{ $demoRequest->college_name }} will expire on <strong>{{ $demoUser->expiry_date->format('d M Y h:i A') }}</strong>.</p>
                <p>You can still log in here before expiry: <a href="{{ route('demo.login') }}" style="color:#1f4ed8;">{{ route('demo.login') }}</a></p>
                <p>If you need more time or want a guided product walkthrough, please contact {{ config('mail.from.address') }}.</p>
                <p style="margin-bottom:0;">Regards,<br>CampusEdgePro Team</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
