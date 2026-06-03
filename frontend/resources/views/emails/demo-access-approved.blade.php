<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo Access Approved</title>
  </head>
  <body style="margin:0; padding:0; background:#eef4fb; font-family:Arial, sans-serif; color:#10213a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef4fb; padding:24px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px; background:#ffffff; border-radius:24px; overflow:hidden;">
            <tr>
              <td style="background:linear-gradient(135deg, #1f4ed8, #4f8df7); padding:28px; color:#ffffff;">
                <h1 style="margin:0 0 10px; font-size:28px;">Your CampusEdgePro demo is ready</h1>
                <p style="margin:0; font-size:16px;">Hello {{ $demoRequest->admin_name }}, your demo access has been approved.</p>
              </td>
            </tr>
            <tr>
              <td style="padding:28px;">
                <p style="margin-top:0;">Use the details below to access your CampusEdgePro demo ERP workspace:</p>
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f8fbff; border:1px solid #d7e5fb; border-radius:18px; padding:18px;">
                  <tr><td style="padding:8px 0;"><strong>User Name:</strong> {{ $demoRequest->admin_name }}</td></tr>
                  <tr><td style="padding:8px 0;"><strong>Demo ERP URL:</strong> <a href="{{ $loginUrl }}" style="color:#1f4ed8;">{{ $loginUrl }}</a></td></tr>
                  <tr><td style="padding:8px 0;"><strong>Username:</strong> {{ $demoUser->username }}</td></tr>
                  <tr><td style="padding:8px 0;"><strong>Temporary Password:</strong> {{ $plainPassword }}</td></tr>
                  <tr><td style="padding:8px 0;"><strong>Expiry Date:</strong> {{ $demoUser->expiry_date->format('d M Y h:i A') }}</td></tr>
                </table>
                <p style="margin:22px 0 0;">Support information: reply to this email or contact {{ config('mail.from.address') }} for assistance during your evaluation.</p>
                <p style="margin:22px 0 0;">Regards,<br>CampusEdgePro Team</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
