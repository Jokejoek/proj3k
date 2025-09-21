<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ProJ3K</title>
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('css/Tools.css') }}">
</head>
<body>
  <!-- Navbar -->
  @include('partials.AfterNav')

  <!-- Content -->
  <main class="flex-grow-1">
    <div class="container py-4">

     <!-- Community Post -->
  <div class="mb-5">
    <h5 class="section-title">Recent Community Post</h5>

    @php
        // ใช้ route('login') ถ้ามี ไม่งั้น fallback เป็น /login
        $loginUrl = \Illuminate\Support\Facades\Route::has('login')
            ? route('login')
            : url('/login');
    @endphp

    <div class="row mx-n2">
    @forelse ($recentPosts->take(6) as $post)   {{-- << แก้เฉพาะตรงนี้ --}}
        <div class="col-12 col-sm-6 col-lg-4 col-xl-4 px-2 mb-3 d-flex">
          <div class="community-card position-relative w-100 h-100 d-flex flex-column">
            {{-- Title / Login --}}
            @guest('web')
              <div class="stretched-link" style="cursor:pointer"
                  onclick="needLoginAlert('{{ $loginUrl }}')">
                <span class="community-title d-block text-white">
                  {{ Str::limit($post->title, 60) }}
                </span>
              </div>
            @else
              <a class="community-title stretched-link d-block text-white"
                href="{{ route('posts.show', $post) }}"
                title="{{ $post->title }}">
                {{ Str::limit($post->title, 60) }}
              </a>
            @endguest

            {{-- Footer --}}
            <div class="author-row mt-auto">
              @php $uname = $post->user?->username ?? 'Unknown'; @endphp
              <img class="author-avatar"
                  src="{{ $post->user?->avatar_url }}"
                  onerror="this.src='https://i.pravatar.cc/64?u=fallback'">
              <div>
                <div class="author-name">{{ $uname }}</div>
                <div class="author-meta">{{ optional($post->created_at)->diffForHumans() }}</div>
              </div>
              <div class="post-actions">
                <i class="fa-regular fa-comment"></i>
                <span>{{ $post->comment_count ?? 0 }}</span>
              </div>
            </div>
          </div>
        </div>
      @empty
      <div class="col-12 px-2"><div class="text-white-60">ยังไม่มีโพสต์ล่าสุด</div></div>
    @endforelse
  </div>
  </div>


      <!-- Popular Tools -->
      <div class="panel mb-4">
        <div class="d-flex justify-content-between">
          <h5 class="section-title">Popular Tools</h5>
        </div>
        <canvas id="toolsChart" height="120"></canvas>
      </div>

      <!-- Recent CVE -->
      <h5 class="section-title">Recent CVE</h5>
      <div class="table-responsive">
        <table class="table table-darkish text-white mb-0">
          <thead>
            <tr>
              <th style="width:28%">Name</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentCves as $row)
              <tr>
                <td>
                  <a class="text-white" href="{{ route('cve.show', $row->cve_id) }}">
                    {{ $row->cve_id }}
                  </a>
                </td>
                <td>{{ $row->description ? \Illuminate\Support\Str::limit($row->description, 180) : '—' }}</td>
              </tr>
            @empty
              <tr><td colspan="2" class="text-muted">No CVEs yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div> <!-- /container -->
  </main>

  <!-- Footer -->
  <div class="footer">
    <p class="m-0">© 2025 Cyber Security Portal</p>
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  function needLoginAlert(loginUrl) {
    Swal.fire({
      icon: 'warning',
      title: 'You need to Login First',
      text: 'Please login before viewing this post.',
      showCancelButton: true,
      confirmButtonText: 'Login',
      cancelButtonText: 'Close'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = loginUrl;
      }
    });
  }
  </script>
  <script>
  const ctx = document.getElementById('toolsChart');
  const chartRows = @json($toolsChart);
  const labels = chartRows.map(r => r.name ?? '(no name)');
  const scores = chartRows.map(r => Number(r.popularity_score) || 0);

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Usage',
        data: scores,
        backgroundColor: '#7CFC00'
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: {
        x: { ticks: { color: '#fff' } },
        y: { ticks: { color: '#fff' } }
      }
    }
  });
</script>
</body>
</html>
