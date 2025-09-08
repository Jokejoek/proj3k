<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title','ProJ3k :: Back Office')</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ProJ3k Admin Theme -->
  <link rel="stylesheet" href="{{ asset('css/Admin/dashboard.css') }}">

  @stack('styles')
  @yield('css_before')
</head>
<body>
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg proj-nav">
    <div class="container">
      <a class="navbar-brand proj-brand d-flex align-items-center" href="{{ url('/') }}">
        <img src="{{ asset('Images/Proj3k.png') }}" alt="ProJ3k">
        ProJ3k
      </a>
      <button class="navbar-toggler bg-dark-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div id="mainNav" class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="#">Tools</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Vulnerability</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Community</a></li>
          <li class="nav-item"><a class="nav-link active" href="{{ url('/admin/backoffice') }}">Back Office</a></li>
        </ul>
        @auth('admin')
          <form class="d-flex" method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <span class="me-3 muted">{{ auth('admin')->user()->username ?? auth('admin')->user()->email }}</span>
            <button class="btn btn-outline-danger btn-sm">Logout</button>
          </form>
        @endauth
      </div>
    </div>
  </nav>

  <!-- CONTENT -->
  <div class="container page-wrap">
    @yield('content')
  </div>

  <footer class="py-4 mt-5">
    <div class="container small text-center">ProJ3k Back Office • © {{ date('Y') }}</div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
  @yield('js_before')
</body>
</html>
