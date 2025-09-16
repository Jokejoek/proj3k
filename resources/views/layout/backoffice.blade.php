{{-- resources/views/layout/backoffice.blade.php --}}
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Backoffice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  @stack('styles')
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg bg-dark navbar-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="{{ route('backoffice.admin.list') }}">Backoffice</a>
    <div class="ms-auto">
      <a class="btn btn-sm btn-outline-light" href="{{ route('backoffice.admin.create') }}">+ Admin</a>
    </div>
  </div>
</nav>

<main class="container">
  @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
