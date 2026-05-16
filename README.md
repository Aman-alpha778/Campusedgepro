# CampusEdgePro Laravel App

CampusEdgePro is a Laravel app with a contact form that stores inquiries in the database and sends notification emails.

## Local Setup

Update your local `.env` with your database settings, then run:

```bash
php artisan config:clear
php artisan migrate
php artisan serve --host=127.0.0.1 --port=8000
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Render Logging

To make Laravel errors visible in the Render dashboard, use:

```env
LOG_CHANNEL=stack
LOG_STACK=single,stderr
LOG_LEVEL=debug
```

## Recommended Production Mail Setup

Gmail SMTP can be slow or unreliable on Render. Resend is the recommended production mail provider for this app.

Use these Render environment variables:

```env
MAIL_MAILER=resend
RESEND_API_KEY=your_resend_api_key
MAIL_FROM_ADDRESS=onboarding@your-domain.com
MAIL_FROM_NAME=CampusEdgePro
MAIL_TO_ADDRESS=your-inbox@example.com
MAIL_TO_NAME=CampusEdgePro
MAIL_SHOW_ERROR_DETAILS=true
MAIL_TIMEOUT=8
```

After changing mail settings on Render:

```bash
php artisan optimize:clear
```

Then redeploy or restart the service.

## Contact Form Troubleshooting

When email delivery fails, the contact form still saves the inquiry and logs a unique `failure_id`.

Check Render logs for:

```text
Contact inquiry email send failed.
```

Each log entry includes:

- `failure_id`
- `mailer`
- exception class
- exception message
- recipient and inquiry context
