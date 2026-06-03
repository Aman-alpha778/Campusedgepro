<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo Request Update</title>
  </head>
  <body style="margin:0; padding:0; background:#f4f7fb; font-family:Arial, sans-serif; color:#10213a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f7fb; padding:24px 0;">
      <tr>
        <td align="center">
          <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px; background:#ffffff; border-radius:24px; overflow:hidden;">
            <tr>
              <td style="padding:28px;">
                <h1 style="margin-top:0;">Update on your CampusEdgePro demo request</h1>
                <p>Hello {{ $demoRequest->admin_name }},</p>
                <p>Thank you for your interest in CampusEdgePro. At the moment, we are unable to approve the demo request for {{ $demoRequest->college_name }}.</p>
                <p>If you would like to discuss your requirements further, please contact our team at {{ config('mail.from.address') }} and we will be happy to help.</p>
                <p style="margin-bottom:0;">Regards,<br>CampusEdgePro Team</p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
