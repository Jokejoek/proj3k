<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title','ProJ3K')</title>

  {{-- CSS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/Tools.css') }}">
  <link rel="stylesheet" href="{{ asset('css/CVE.css') }}"> 
  @stack('styles')
</head>
<body>
  {{-- Navbar --}}
  @include('partials.AfterNav')

  {{-- <<<<<< อันนี้สำคัญ ต้องมี @yield("content") >>>>>> --}}
  <main class="py-3">
  {{-- ถ้าไม่มี section จะโชว์ debug ให้รู้เลยว่าไม่ถูกส่งมา --}}
  @hasSection('content')
    @yield('content')
  @else
    <div class="container text-warning">[DEBUG] section 'content1' ไม่ถูกส่งมาจาก view ลูก</div>
  @endif
</main>

  {{-- Footer --}}
  <div class="footer"><p>© 2025 Cyber Security Portal</p></div>

  {{-- JS --}}
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
