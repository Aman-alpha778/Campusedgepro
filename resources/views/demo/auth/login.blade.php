<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo ERP Login | CampusEdgePro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="portal-body demo-login-page">
    <main class="portal-auth">
      <section class="portal-auth-card demo-login-card">
        <div class="portal-auth-brand">
          <span class="demo-login-logo-wrap">
            <img class="portal-auth-logo demo-login-logo" src="{{ asset('assets/camplogo.png') }}" alt="CampusEdgePro">
          </span>
          <span>CampusEdgePro Demo Access</span>
        </div>
        <h1>Demo ERP Login</h1>
      

        @if ($errors->any())
          <div class="portal-error-list">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form class="portal-form" method="post" action="{{ route('demo.login.store') }}">
          @csrf
          <label class="portal-field">
            <span>Username</span>
            <input type="text" name="username" value="{{ old('username') }}" required>
          </label>
          <label class="portal-field">
            <span>Password</span>
            <input type="password" name="password" required>
          </label>
          <button class="portal-button" type="submit">Login to demo ERP</button>
        </form>
      </section>
    </main>
  </body>
</html>
