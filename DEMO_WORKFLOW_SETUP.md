# CampusEdgePro Demo Workflow Setup

1. Configure environment values in `.env`:
   - `APP_URL`
   - `DB_*`
   - `MAIL_*`
   - `ADMIN_NAME`
   - `ADMIN_EMAIL`
   - `ADMIN_PASSWORD`

2. Run database setup:
   - `php artisan migrate`
   - `php artisan db:seed`

3. Login URLs:
   - Admin login: `/admin/login`
   - Demo login: `/demo-portal/login`

4. Scheduler:
   - Add Laravel scheduler on the server so `php artisan schedule:run` executes every minute.
   - The `demo-access:maintain` command is scheduled daily at `08:00` to expire old demo accounts and send reminder emails.

5. Approval flow:
   - New requests are stored from the public demo page.
   - Admin approves requests from `/admin/demo-requests`.
   - Approval creates credentials, hashes the password, sets a 7-day expiry window, and sends email credentials automatically.

6. Security:
   - Admin routes require authenticated admin users.
   - Demo routes use a dedicated `demo` guard plus expiry middleware.
   - Expired users are blocked on login and on every protected demo request.
