<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - ProJ3K</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Site theme -->
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <!-- ใช้ CSS Signup เดียวกัน -->
  <link rel="stylesheet" href="{{ asset('css/Users/Signup.css') }}">
  <link rel="stylesheet" href="{{ asset('css/Tools.css') }}">
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
        <img src="{{ asset('Images/Proj3k.png') }}" alt="Logo" height="30" class="mr-2">
        <span>ProJ3K</span>
      </a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav"
              aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div id="mainNav" class="ml-auto collapse navbar-collapse">
        <div class="navbar-nav ml-auto">
          <a href="{{ url('/Tools') }}" class="nav-item nav-link fontLog">Tools</a>
          <a href="{{ url('/Vulnerability') }}" class="nav-item nav-link fontLog">Vulnerability</a>
          <a href="{{ url('/Community') }}" class="nav-item nav-link fontLog">Community</a>
          <a href="{{ url('/about') }}" class="nav-item nav-link fontLog">About</a>

          @guest('web')
            {{-- ยังไม่ล็อกอินฝั่งผู้ใช้ --}}
            <a href="{{ route('login.form') }}" class="nav-item nav-link fontLog">Login</a>
            <a href="{{ route('signup.form') }}" class="nav-item nav-link btn-auth ml-2">Sign up</a>
          @else
            {{-- ล็อกอินฝั่งผู้ใช้แล้ว --}}
            @php $u = Auth::guard('web')->user(); @endphp
            <div class="nav-item dropdown ml-3">
              <a class="nav-link d-flex align-items-center dropdown-toggle p-0"
                 href="#" id="userMenu" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @php
                  $u = Auth::user();
                  $initial = strtoupper(mb_substr($u->username ?? $u->email, 0, 1));
                @endphp

                @if(!empty($u->avatar_url))
                  <img src="{{ $u->avatar_url }}" class="avatar mr-2" alt="avatar">
                @else
                  <div class="avatar avatar-fallback mr-2">{{ $initial }}</div>
                @endif

                <span class="username text-white-90">{{ $u->username ?? Str::before($u->email, '@') }}</span>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userMenu">
                <a class="dropdown-item" href="{{ url('/profile') }}">
                  <i class="far fa-user mr-2"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                  </button>
                </form>
              </div>
            </div>
          @endguest
        </div>
      </div>
    </div>
  </nav>

  <!-- Main content -->
  <main class="signup-wrapper">
    <div class="card p-4 signup-card">
      <h3 class="text-center mb-4">Login</h3>
      <form method="POST" action="{{ route('login.submit') }}">
        @csrf
        <!-- Login (Email or Username) -->
        <div class="form-group">
          <label for="login">Email / Username</label>
          <input id="login" type="text" class="form-control" name="login" required autofocus>
        </div>
        <!-- Password -->
        <div class="form-group mb-4">
          <label for="password">Password</label>
          <input id="password" type="password" class="form-control" name="password" required>
        </div>
        <!-- Submit -->
        <button type="submit" class="btn-auth btn-block">Login</button>
      </form>
    </div>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <p>&copy; 2025 ProJ3K. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
