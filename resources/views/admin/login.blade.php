<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Login</title>
  <link rel="stylesheet" href="{{ asset('css/Admin/admin-login.css') }}">
</head>
<body>

  <!-- เนื้อหากลางจอ -->
  <main class="wrap">
    <section class="card" role="form" aria-label="Admin Login">
      <h2 class="card__title">Admin Login</h2>

      <form method="POST" action="{{ route('admin.login.submit') }}" autocomplete="on">
        @csrf

        {{-- Login (username/email) --}}
        <div class="field">
          <input
            class="input @error('login') is-invalid @enderror"
            type="text"
            name="login"
            value="{{ old('login') }}"
            placeholder="Username / Email"
            required
            autocomplete="username"
            autofocus>
          @error('login')
            <div class="err">{{ $message }}</div>
          @enderror
        </div>

        {{-- Password --}}
        <div class="field">
          <input
            class="input @error('password') is-invalid @enderror"
            type="password"
            name="password"
            placeholder="Password"
            required
            autocomplete="current-password">
          @error('password')
            <div class="err">{{ $message }}</div>
          @enderror
        </div>


        {{-- Submit --}}
        <button type="submit" class="btn">Login</button>
      </form>
    </section>
  </main>

  <!-- Footer ชิดล่างเสมอ -->
  <footer class="footer">
    <p>© {{ date('Y') }} ProJ3K. All rights reserved.</p>
  </footer>
</body>
</html>
