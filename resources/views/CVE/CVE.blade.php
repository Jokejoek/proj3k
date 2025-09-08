<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>ProJ3K</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/CVE.css') }}">
</head>
<body>

  <!-- NavBar -->
  @include('partials.AfterNav')

  <!-- Content -->
  <div class="container py-4">
    <!-- ===== CVE Section ===== -->
    <section id="cve" class="mb-4">
      <h4 class="section-title text-white mb-3">CVE</h4>

      <!-- Toolbar -->
<form id="filterForm" class="bg-dark p-3 rounded mb-3" method="GET" action="{{ route('cve.index') }}">
  <div class="form-row align-items-end">
    <div class="col-md-8 mb-2">
      <label class="text-light mb-1">ค้นหา CVE</label>
      <input type="text" class="form-control" name="q" id="q"
             value="{{ request('q') }}"
             placeholder="เช่น CVE-2023-1234, Apache, Windows">
    </div>
    <div class="col-md-4 mb-2 d-flex justify-content-end">
      <button type="button" id="toggleFilters" class="btn btn-outline-light mr-2">ตัวกรองเพิ่มเติม</button>
      <button type="submit" class="btn btn-danger">ค้นหา</button>
    </div>
  </div>

  <!-- Advanced filters -->
  <div id="advancedFilters" class="mt-3 {{ request()->hasAny(['sev','year','sort']) ? '' : 'd-none' }}">
    <div class="form-row">
      <div class="col-md-4 mb-2">
        <label class="text-light mb-1">Severity</label>
        <select name="sev" id="sev" class="form-control">
          @php $sev = request('sev'); @endphp
          <option value="">ทั้งหมด</option>
          <option value="Critical" {{ $sev==='Critical'?'selected':'' }}>Critical</option>
          <option value="High"     {{ $sev==='High'?'selected':'' }}>High</option>
          <option value="Medium"   {{ $sev==='Medium'?'selected':'' }}>Medium</option>
          <option value="Low"      {{ $sev==='Low'?'selected':'' }}>Low</option>
        </select>
      </div>

      <div class="col-md-4 mb-2">
        <label class="text-light mb-1">Year</label>
        <select name="year" id="year" class="form-control">
          @php $y = request('year'); @endphp
          <option value="">ทั้งหมด</option>
          @foreach(($years ?? []) as $yy)
            <option value="{{ $yy }}" {{ (string)$y === (string)$yy ? 'selected' : '' }}>{{ $yy }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4 mb-2">
        <label class="text-light mb-1">เรียงลำดับ</label>
        <select name="sort" id="sort" class="form-control">
          @php $s = request('sort','new'); @endphp
          <option value="new" {{ $s==='new'?'selected':'' }}>ใหม่ล่าสุด</option>
          <option value="old" {{ $s==='old'?'selected':'' }}>เก่าสุด</option>
          <option value="sev" {{ $s==='sev'?'selected':'' }}>Severity สูงสุด</option>
        </select>
      </div>
    </div>
    <div class="d-flex justify-content-end">
      <a href="{{ route('cve.index') }}" class="btn btn-secondary">ล้างตัวกรอง</a>
    </div>
  </div>
</form>

<!-- สรุปผล -->
<div class="d-flex justify-content-between align-items-center mb-2">
  <small class="text-light">
    {{ number_format($cves->total()) }} รายการ
    @if(request('q') || request('sev') || request('year'))
      (มีการกรอง)
    @endif
  </small>
</div>

<!-- Grid -->
<div id="cveGrid" class="row">
  @forelse($cves as $c)
    <div class="col-md-6 mb-3">
      {{-- เพิ่ม position-relative ตรงนี้ --}}
      <article class="card cve-card position-relative h-100 shadow-sm">
        <img src="https://picsum.photos/seed/{{ $c->cve_id }}/600/250"
             class="card-img-top" alt="{{ $c->cve_id }}"
             style="object-fit:cover; max-height:200px;">

        <div class="card-body d-flex flex-column">
          <h5 class="card-title cve-id mb-1">{{ $c->cve_id }}</h5>

          <div class="mb-2">
            <span class="badge-sev sev-{{ strtolower($c->severity) }}">{{ $c->severity }}</span>
            @if(!is_null($c->cvss_score))
              <span class="cvss-chip">CVSS {{ rtrim(rtrim(number_format((float)$c->cvss_score, 1, '.', ''), '0'), '.') }}</span>
            @endif
            @if(!is_null($c->year))
              <span class="year-chip">{{ $c->year }}</span>
            @endif
          </div>

          <p class="cve-desc mt-2 flex-grow-1">
            {{ $c->title ?? \Illuminate\Support\Str::limit($c->description, 160) }}
          </p>

          @if($c->product || $c->vendor)
            <p class="mb-2">
              <b>Product:</b> {{ trim(($c->vendor ? $c->vendor.' ' : '').($c->product ?? '')) }}
            </p>
          @endif

          {{-- ลิงก์โปร่งใสคลุมการ์ดทั้งหมด --}}
          <a href="{{ route('cve.show', $c) }}"
             class="stretched-link"
             aria-label="ดูรายละเอียด {{ $c->cve_id }}"></a>
        </div>
      </article>
    </div>
  @empty
    <div class="col-12">
      <div class="alert alert-secondary">ไม่พบรายการตามเงื่อนไขที่เลือก</div>
    </div>
  @endforelse
</div>

<!-- Pagination -->
<div class="mt-3">
  {{ $cves->onEachSide(1)->links() }}
</div>

<!-- Dropdown.js -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
{{-- JS ที่เหลือ: แค่ toggle advance --}}
<script>
  const toggleBtn = document.getElementById('toggleFilters');
  const advBox    = document.getElementById('advancedFilters');
  if (toggleBtn && advBox) {
    toggleBtn.addEventListener('click', () => advBox.classList.toggle('d-none'));
  }
</script>

</body>
</html>
