<!-- resources/views/admin/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Admin Login - ProJ3k</title>
  <link rel="stylesheet" href="{{ asset('css/Admin/admin-login.css') }}">
</head>
<body>
  <!-- Navbar: โลโก้ + ชื่อ -->
  <header class="nav">
      <a href="{{ url('/') }}" class="nav__brand-link">
        <img class="nav__logo" src="{{ asset('Images/Proj3k.png') }}" alt="ProJ3k Logo">
        <h1 class="nav__brand">ProJ3k</h1>
      </a>
  </header>

  <!-- เนื้อหากลางจอ -->
  <main class="wrap">
    <section class="card" role="form" aria-label="Admin Login">
      <h2 class="card__title">Admin</h2>

      <form method="POST" action="{{ route('admin.login.submit') }}" autocomplete="on">
        @csrf

        <div class="field">
          <input
            class="input"
            type="email"
            name="email"
            placeholder="Username"
            required
            autocomplete="username"
            inputmode="email">
          @error('email') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <input
            class="input"
            type="password"
            name="password"
            placeholder="Password"
            required
            autocomplete="current-password">
          @error('password') <div class="err">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn">Login</button>
      </form>
    </section>
  </main>

  <!-- Footer ชิดล่างเสมอ -->
  <footer class="footer">
    <p>© 2025 Cyber Security Portal</p>
  </footer>
</body>
</html>
