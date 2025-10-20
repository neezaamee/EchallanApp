<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', 'Department Dashboard')</title>

  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  @livewireStyles
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="{{ route('dashboard') }}">CTPF</a>
      <div class="d-flex">
          <span class="me-2">{{ auth()->user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="btn btn-outline-secondary btn-sm">Logout</button>
          </form>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @livewireScripts
</body>
</html>
