@extends('layout.app')

@section('title', 'Back Office :: Dashboard')

@section('content')
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="mb-0">Back Office :: Dashboard</h3>
      <div class="subtitle">ข้อมูล 30 วันที่ผ่านมา (ถึง {{ $since->format('Y-m-d') }})</div>
    </div>
    
  </div>

  <div class="row g-3">
    <div class="col-md-3 col-6">
      <div class="card p-3">
        <h6>Users</h6>
        <h3 class="mb-2">{{ $stats['users'] }}</h3>
        <!--@can('users.manage')
          <a href="{{ route('admin.backend.users') }}" class="btn btn-sm btn-primary">Manage Users</a>
        @endcan-->
      </div>
    </div>

    <div class="col-md-3 col-6">
      <div class="card p-3">
        <h6>CVE</h6>
        <h3 class="mb-2">{{ $stats['cves'] }}</h3>
        <!--@can('content.manage')
          <a href="{{ route('admin.backend.cve.create') }}" class="btn btn-sm btn-warning">New CVE</a>
        @endcan-->
      </div>
    </div>

    <div class="col-md-3 col-6">
      <div class="card p-3">
        <h6>Tools</h6>
        <h3 class="mb-2">{{ $stats['tools'] }}</h3>
      </div>
    </div>

    <div class="col-md-3 col-6">
      <div class="card p-3">
        <h6>Views (30d)</h6>
        <h3 class="mb-2">{{ $stats['views_30d'] }}</h3>
        <span class="chip">รวมทั้ง CVE & Tools</span>
      </div>
    </div>
  </div>

  <div class="row g-3 mt-3">
    <div class="col-lg-6">
      <div class="card p-3">
        <div class="d-flex justify-content-between mb-2">
          <h6 class="mb-0">Top CVE (by views)</h6>
          <span class="chip">30 days</span>
        </div>
        <canvas id="cveChart" height="220"></canvas>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card p-3">
        <div class="d-flex justify-content-between mb-2">
          <h6 class="mb-0">Top Tools (by views)</h6>
          <span class="chip">30 days</span>
        </div>
        <canvas id="toolChart" height="220"></canvas>
      </div>
    </div>



  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  const C = { grid:'rgba(157,176,201,0.15)', tick:'#9db0c9', bar1:'#38bdf8', bar2:'#10b981', border:'#22304a' };

  function makeBar(el, labels, data, color){
    return new Chart(el.getContext('2d'), {
      type:'bar',
      data:{ labels:labels, datasets:[{ label:'Views', data:data, backgroundColor:color, borderColor:C.border, borderWidth:1.5 }]},
      options:{
        responsive:true,
        scales:{
          x:{ ticks:{ color:C.tick }, grid:{ color:C.grid } },
          y:{ ticks:{ color:C.tick, precision:0 }, grid:{ color:C.grid }, beginAtZero:true }
        },
        plugins:{ legend:{ labels:{ color:C.tick } } }
      }
    });
  }

  makeBar(document.getElementById('cveChart'),
          @json($chart['cve']['labels']), @json($chart['cve']['data']), C.bar2);

  makeBar(document.getElementById('toolChart'),
          @json($chart['tool']['labels']), @json($chart['tool']['data']), C.bar1);
</script>
@endpush
