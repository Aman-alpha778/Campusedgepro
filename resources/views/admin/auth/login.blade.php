<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | CampusEdgePro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="portal-body">
    <main class="portal-auth">
      <section class="portal-auth-card">
        <div class="portal-auth-brand">
          <img class="portal-auth-logo" src="/assets/camplogo-admin.png" alt="CampusEdgePro">
          <small>CampusEdgePro Admin Portal</small>
        </div>
        <h1 class="portal-auth-title">Admin login</h1>
        <p class="portal-auth-subtitle">Sign in to review demo requests, approve access, and manage expiry controls.</p>

        @if ($errors->any())
          <div class="portal-error-list">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form class="portal-form" method="post" action="{{ route('admin.login.store') }}">
          @csrf
          <label class="portal-field">
            <span>Email</span>
            <input type="email" name="email" value="{{ old('email') }}" required>
          </label>
          <label class="portal-field">
            <span>Password</span>
            <input type="password" name="password" required>
          </label>
          <label class="portal-remember">
            <input type="checkbox" name="remember" value="1">
            <span>Remember me</span>
          </label>
          <button class="portal-button" type="submit">Login to dashboard</button>
        </form>
      </section>
    </main>
  </body>
</html>
