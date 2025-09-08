<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
      <img src="{{ asset('Images/Proj3k.png') }}" alt="ProJ3K Logo" height="30" class="mr-2">
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

        @guest
          {{-- ยังไม่ล็อกอิน --}}
          <a href="{{ url('/login') }}" class="nav-item nav-link fontLog">Login</a>
          <a href="{{ url('/signup') }}" class="nav-item nav-link btn-auth ml-2">Sign up</a>
        @else
          @php
            $u = Auth::user();
            $initial = strtoupper(mb_substr($u->username ?? $u->email, 0, 1));
            $displayName = $u->username ?? \Illuminate\Support\Str::before($u->email, '@');
          @endphp

          <div class="nav-item dropdown ml-3">
            <a class="nav-link d-flex align-items-center dropdown-toggle p-0"
               href="#" id="userMenu" role="button"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              @if(!empty($u->avatar_url))
                <img src="{{ $u->avatar_url }}" class="avatar mr-2" alt="avatar">
              @else
                <div class="avatar avatar-fallback mr-2">{{ $initial }}</div>
              @endif
              <span class="username text-white-90">{{ $displayName }}</span>
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
