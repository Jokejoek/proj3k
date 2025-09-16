<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 12 Basic CRUD by devbanban.com 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    @yield('css_before')
    <link rel="stylesheet" href="{{ asset('css/backend.css') }}">
  </head>
  <body>

    <div class="container">
      <div class="row">
          <div class="col">
              <div class="alert alert-success text-center" role="alert">
                <h4>Back Office || Laravel 12 || ยินดีต้อนรับคุณ Admin</h4>
              </div>
          </div>
      </div>
    </div>

    @yield('header')

    <div class="container">
      <div class="row">

        <div class="col-md-3">
          <div class="list-group">
            <a href="{{ route('admin.dashboard') }}"class="list-group-item list-group-item-action active home" aria-current="true">
              Home
            </a>

              {{-- Admins --}}
              <a href="{{ route('admin.backend.admins.index') }}"
                class="list-group-item list-group-item-action
                        {{ request()->routeIs('admin.backend.admins.*') ? 'active' : '' }}">
                - Admin
              </a>

              {{-- Users (หน้า index ผู้ใช้ทั่วไปที่ BackofficeController คืนค่า) --}}
              <a href="{{ route('admin.backend.users') }}"
                class="list-group-item list-group-item-action
                        {{ request()->routeIs('admin.backend.users') ? 'active' : '' }}">
                - User
              </a>

              {{-- CVE --}}
              <a href="{{ route('admin.backend.cve.index') }}"
                class="list-group-item list-group-item-action
                        {{ request()->routeIs('admin.backend.cve.*') ? 'active' : '' }}">
                - CVE
              </a>

              {{-- Tools --}}
              <a href="{{ route('admin.backend.tools.index') }}"
                class="list-group-item list-group-item-action
                      {{ request()->routeIs('admin.backend.tools.*') ? 'active' : '' }}">
                - Tools
              </a>
          
          </div>
          @yield('sidebarMenu')
        </div>

        <div class="col-md-9">
          @yield('content')
        </div>

      </div>
    </div>

    <footer class="mt-5 mb-2">
      <div class="container small text-center">ProJ3k Back Office • © {{ date('Y') }}</div>
    </footer>
    
    @yield('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    @yield('js_before')

    

    {{-- >>>>>>> ตรงนี้สำคัญ <<<<<<< --}}
    @include('sweetalert::alert')
    @stack('scripts')
  </body>
</html>
