<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>New Contact Inquiry</title>
  </head>
  <body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h2 style="margin-bottom: 16px;">New Contact Inquiry</h2>

    <p><strong>Inquiry Type:</strong> {{ $contactData['inquiry_type'] }}</p>
    <p><strong>First Name:</strong> {{ $contactData['first_name'] }}</p>
    <p><strong>Last Name:</strong> {{ $contactData['last_name'] ?: 'N/A' }}</p>
    <p><strong>Email:</strong> {{ $contactData['email'] }}</p>
    <p><strong>Phone:</strong> {{ $contactData['phone'] }}</p>
    <p><strong>Country:</strong> {{ $contactData['country'] ?: 'N/A' }}</p>
    <p><strong>Wants Updates:</strong> {{ $contactData['updates'] ? 'Yes' : 'No' }}</p>

    <h3 style="margin-top: 24px; margin-bottom: 8px;">Message</h3>
    <p style="white-space: pre-line;">{{ $contactData['message'] ?: 'No message provided.' }}</p>
  </body>
</html>
