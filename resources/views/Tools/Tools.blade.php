<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ProJ3K</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/Tools.css') }}">
</head>
<body>
  <!-- NavBar -->
  @include('partials.AfterNav')

  <!-- Content -->
  <div class="container py-4">
    <section id="tools" class="mb-4">
      <h4 class="section-title text-white mb-3">Tools</h4>

      {{-- Toolbar --}}
      <form id="filterForm" class="bg-dark p-3 rounded mb-3" method="GET" action="{{ route('tools.index') }}">
        <div class="form-row align-items-end">
          <div class="col-md-8 mb-2">
            <label class="text-light mb-1">ค้นหา</label>
            <input type="text" class="form-control" name="q" id="q"
                   value="{{ request('q') }}" placeholder="เช่น nmap, wireshark">
          </div>
          <div class="col-md-4 mb-2 d-flex justify-content-end">
            <button type="button" id="toggleFilters" class="btn btn-outline-light mr-2">ตัวกรองเพิ่มเติม</button>
            <button type="submit" class="btn btn-success">ค้นหา</button>
          </div>
        </div>

        {{-- Advanced filters (ซ่อนเริ่มต้น) --}}
        <div id="advancedFilters" class="mt-3 {{ request()->hasAny(['cat','sort']) ? '' : 'd-none' }}">
          <div class="form-row">
            <div class="col-md-4 mb-2">
              <label class="text-light mb-1">หมวดหมู่</label>
              @php $cat = request('cat'); @endphp
              <select name="cat" id="cat" class="form-control">
                <option value="">ทั้งหมด</option>
                <option {{ $cat==='Network'?'selected':'' }}>Network</option>
                <option {{ $cat==='Web'?'selected':'' }}>Web</option>
                <option {{ $cat==='Wireless'?'selected':'' }}>Wireless</option>
                <option {{ $cat==='Forensics'?'selected':'' }}>Forensics</option>
                <option {{ $cat==='Password'?'selected':'' }}>Password</option>
                <option {{ $cat==='Scanner'?'selected':'' }}>Scanner</option>
                <option {{ $cat==='Fuzzer'?'selected':'' }}>Fuzzer</option>
                <option {{ $cat==='Exploitation'?'selected':'' }}>Exploitation</option>
              </select>
            </div>

            <div class="col-md-4 mb-2">
              <label class="text-light mb-1">เรียงลำดับ</label>
              @php $sort = request('sort','az'); @endphp
              <select name="sort" id="sort" class="form-control">
                <option value="az"  {{ $sort==='az'?'selected':'' }}>A → Z</option>
                <option value="za"  {{ $sort==='za'?'selected':'' }}>Z → A</option>
                <option value="pop" {{ $sort==='pop'?'selected':'' }}>ความนิยม</option>
              </select>
            </div>
          </div>

          <div class="d-flex justify-content-end">
            <a href="{{ route('tools.index') }}" class="btn btn-secondary">ล้างตัวกรอง</a>
          </div>
        </div>
      </form>

      {{-- สรุปผล --}}
      <div class="d-flex justify-content-between align-items-center mb-2">
        <small class="text-light">
          {{ number_format($tools->total()) }} รายการ
          @if(request('q') || request('cat') || request('sort')) (มีการกรอง) @endif
        </small>
      </div>

     {{-- การ์ดรายการ --}}
      <div id="toolGrid" class="row">
        @forelse($tools as $t)
          <div class="col-md-4 mb-3">
            <article class="card h-100 shadow-sm tool-card position-relative">
              <img class="card-img-top"
                  src="{{ $t->image_url ?: 'https://picsum.photos/seed/'.md5($t->name).'/512/288' }}"
                  alt="{{ $t->name }}">
              <div class="card-body d-flex flex-column">
                <h5 class="card-title mb-1">{{ $t->name }}</h5>

                <div class="mb-2">
                  @if($t->category)
                    <span class="badge-cat">{{ $t->category }}</span>
                  @endif
                </div>

                @if(!is_null($t->popularity_score))
                  <small class="text-warning d-block mb-2">★ {{ $t->popularity_score }}</small>
                @endif

                <p class="card-text flex-grow-1">
                  {{ \Illuminate\Support\Str::limit($t->title, 140) }}
                </p>

                {{-- ลิงก์โปร่งใส คลุมทั้งการ์ด --}}
                <a href="{{ route('tools.show', $t) }}"
                  class="stretched-link"
                  aria-label="ดูรายละเอียด {{ $t->name }}"></a>

                <div class="mt-auto d-flex">
                  @if($t->download_link)
                    <a href="{{ $t->download_link }}" target="_blank" class="btn btn-sm btn-success">ดาวน์โหลด</a>
                  @endif
                </div>
              </div>
            </article>
          </div>
        @empty
          <div class="col-12">
            <div class="alert alert-secondary">ไม่พบเครื่องมือที่ตรงกับตัวกรอง</div>
          </div>
        @endforelse
      </div>

      {{-- เพจจิเนชัน --}}
      <div class="mt-3">
        {{ $tools->onEachSide(1)->links() }}
      </div>
    </section>
  </div>

  <div class="footer"><p>© 2025 Cyber Security Portal</p></div>

  <!-- Dropdown.js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  {{-- เหลือแค่ toggle ตัวกรอง --}}
  <script>
    const toggleBtn = document.getElementById('toggleFilters');
    const advBox    = document.getElementById('advancedFilters');
    if (toggleBtn && advBox) {
      toggleBtn.addEventListener('click', () => advBox.classList.toggle('d-none'));
    }
  </script>
</body>
</html>
