<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 12 Basic CRUD by devbanban.com 2025</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    @yield('css_before')
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
            <a href="/admin/dashboard" class="list-group-item list-group-item-action active" aria-current="true">
              Home
            </a>
          
            <a href="admin/backoffice/admin" class="list-group-item list-group-item-action"> - Admin </a>

            <a href="admin/backoffice/user" class="list-group-item list-group-item-action"> - User </a>
            
            <a href="admin/backoffice/Cve" class="list-group-item list-group-item-action"> - Cve </a>

            <a href="admin/backoffice/Tools" class="list-group-item list-group-item-action"> - Tools </a>
          
          </div>
          @yield('sidebarMenu')
        </div>

        <div class="col-md-9">
          @yield('content')
        </div>

      </div>
    </div>

    <footer class="mt-5 mb-2">
      <p class="text-center">by devbanban.com @2025</p>
    </footer>
    
    @yield('footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    @yield('js_before')

    

    {{-- >>>>>>> ตรงนี้สำคัญ <<<<<<< --}}
    @include('sweetalert::alert')

  </body>
</html>
