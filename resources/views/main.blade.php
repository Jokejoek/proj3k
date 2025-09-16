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
            <div class="row">
                {{-- Card 1 --}}
                <div class="col-md-4 mb-3">
                <div class="community-card">
                    <div class="community-title">I need to jerk off</div>
                    <div class="author-row">
                    <img class="author-avatar" src="{{ asset('images/users/jokec.jpg') }}"
                        onerror="this.src='https://i.pravatar.cc/64?u=jokec'">
                    <div>
                        <div class="author-name">Jokec</div>
                        <div class="author-meta">1d</div>
                    </div>
                    <div class="post-actions">
                        <i class="fa-regular fa-comment"></i><span>8</span>
                    </div>
                    </div>
                </div>
                </div>

                {{-- Card 2 --}}
                <div class="col-md-4 mb-3">
                <div class="community-card">
                    <div class="community-title">why i run code and it error</div>
                    <div class="author-row">
                    <img class="author-avatar" src="{{ asset('images/users/dabbydoo.jpg') }}"
                        onerror="this.src='https://i.pravatar.cc/64?u=dabbydoo'">
                    <div>
                        <div class="author-name">DabbyDoo</div>
                        <div class="author-meta">21h</div>
                    </div>
                    <div class="post-actions">
                        <i class="fa-regular fa-comment"></i><span>5</span>
                    </div>
                    </div>
                </div>
                </div>

                {{-- Card 3 --}}
                <div class="col-md-4 mb-3">
                <div class="community-card">
                    <div class="community-title">what is CVE</div>
                    <div class="author-row">
                    <img class="author-avatar" src="{{ asset('images/users/pakin.jpg') }}"
                        onerror="this.src='https://i.pravatar.cc/64?u=pakin'">
                    <div>
                        <div class="author-name">Pakin</div>
                        <div class="author-meta">60d</div>
                    </div>
                    <div class="post-actions">
                        <i class="fa-regular fa-comment"></i><span>9</span>
                    </div>
                    </div>
                </div>
                </div>

                {{-- Card 4 --}}
                <div class="col-md-4 mb-3">
                <div class="community-card">
                    <div class="community-title">why im so smart</div>
                    <div class="author-row">
                    <img class="author-avatar" src="{{ asset('images/users/domon.jpg') }}"
                        onerror="this.src='https://i.pravatar.cc/64?u=domon'">
                    <div>
                        <div class="author-name">Domon</div>
                        <div class="author-meta">4d</div>
                    </div>
                    <div class="post-actions">
                        <i class="fa-regular fa-comment"></i><span>4</span>
                    </div>
                    </div>
                </div>
                </div>

                {{-- Card 5 --}}
                <div class="col-md-4 mb-3">
                <div class="community-card">
                    <div class="community-title">Jokec pen gay</div>
                    <div class="author-row">
                    <img class="author-avatar" src="{{ asset('images/users/domon.jpg') }}"
                        onerror="this.src='https://i.pravatar.cc/64?u=domon2'">
                    <div>
                        <div class="author-name">doMon</div>
                        <div class="author-meta">5h</div>
                    </div>
                    <div class="post-actions">
                        <i class="fa-regular fa-comment"></i><span>3</span>
                    </div>
                    </div>
                </div>
                </div>

                {{-- Card 6 --}}
                <div class="col-md-4 mb-3">
                <div class="community-card">
                    <div class="community-title">i have 100,000 salay</div>
                    <div class="author-row">
                    <img class="author-avatar" src="{{ asset('images/users/domon.jpg') }}"
                        onerror="this.src='https://i.pravatar.cc/64?u=domon3'">
                    <div>
                        <div class="author-name">dOmOn</div>
                        <div class="author-meta">60d</div>
                    </div>
                    <div class="post-actions">
                        <i class="fa-regular fa-comment"></i><span>1</span>
                    </div>
                    </div>
                </div>
                </div>

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
            <h5 class="section-title">Recent CVE</h5> <!-- ✅ ย้ายหัวข้อออกจาก table -->
            <div class="table-responsive">
                <table class="table table-darkish text-white mb-0">
                <thead>
                    <tr>
                    <th style="width:28%">Name</th>
                    <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @php use Illuminate\Support\Str; @endphp
                    @forelse($recentCves as $row)
                    <tr>
                        <td><a class="text-white" href="{{ route('cve.show', $row->cve_id) }}">{{ $row->cve_id }}</a></td>
                        <td>{{ $row->description ? Str::limit($row->description, 180) : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-muted">No CVEs yet.</td></tr>
                    @endforelse
                </tbody>
                </table>
            </div>
            </div> <!-- ✅ ปิด container ที่นี่จริงๆ -->
        </main>
        <!-- Footer -->
  <div class="footer">
    <p class="m-0">© 2025 Cyber Security Portal</p>
  </div>

  <!-- JS (โหลดครั้งเดียว และเอา canvas ตัวล่างออก) -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script>
    const ctx = document.getElementById('toolsChart');
    const chartRows = @json($toolsChart);
    const labels = chartRows.map(r => r.name ?? '(no name)');
    const scores = chartRows.map(r => Number(r.popularity_score) || 0);
    new Chart(ctx, {
      type: 'bar',
      data: { labels, datasets: [{ label: 'Usage', data: scores, backgroundColor: '#7CFC00' }] },
      options: {
        plugins: { legend: { display: false } },
        scales: { x: { ticks: { color: '#fff' } }, y: { ticks: { color: '#fff' } } }
      }
    });
  </script>
</body>
