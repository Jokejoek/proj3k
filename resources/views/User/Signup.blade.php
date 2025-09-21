<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - ProJ3K</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Site theme (แนะนำให้ใช้ main.css เป็นธีมหลัก) -->
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <!-- หน้า Signup (ถ้าต้องการสไตล์เฉพาะหน้านี้ ค่อยใส่เพิ่มในไฟล์นี้) -->
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

      <!-- ถ้าต้องการ toggler มือถือ เพิ่มปุ่มนี้ได้ -->
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
          <a href="{{ url('/login') }}" class="nav-item nav-link fontLog">Login</a>
          <a href="{{ url('/signup') }}" class="nav-item nav-link btn-auth">Sign up</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main content -->
  <main class="signup-wrapper">
  <div class="card p-4 signup-card">
    <h3 class="text-center mb-4">Sign Up</h3>
    <form method="POST" action="{{ route('signup.submit') }}">
      @csrf
      <!-- Username -->
      <div class="form-group">
        <label for="username">Username</label>
        <input id="username" type="text" class="form-control" name="username" required autofocus>
      </div>
      <!-- Email -->
      <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control" name="email" required>
      </div>
      <!-- Password -->
      <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" class="form-control" name="password" required>
      </div>
      <!-- Confirm Password -->
      <div class="form-group mb-4">
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
      </div>
      <!-- Submit -->
      <button type="submit" class="btn-auth btn-block">Sign Up</button>
    </form>
  </div>
</main>


  <!-- Footer -->
  <footer class="footer">
    <p>&copy; 2025 ProJ3K. All rights reserved.</p>
  </footer>

  <!-- (ถ้าจะใช้ toggler ให้ทำงาน) Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
