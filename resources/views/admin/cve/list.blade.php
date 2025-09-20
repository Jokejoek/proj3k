@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">

      <div class="d-flex align-items-center justify-content-between mb-2">
        <h3 class="mb-0">:: CVE data ::</h3>
        <a href="{{ route('admin.backend.cve.create') }}" class="btn btn-primary btn-sm">+ CVE</a>
      </div>

      <table class="table table-bordered table-striped table-hover align-middle">
        <thead>
          <tr class="table-info">
            <th class="text-center" style="width:5%">No.</th>
            <th style="width:10%">Image</th>
            <th style="width:12%">CVE</th>
            <th style="width:30%">Title</th>
            <th style="width:18%">Vendor / Product</th>
            <th class="text-center" style="width:7%">CVSS</th>
            <th class="text-center" style="width:8%">Severity</th>
            <th class="text-center" style="width:5%">Year</th>
            <th class="text-center" style="width:5%">Exploit</th>
            <th class="text-center" style="width:12%">Published</th>
            <th class="text-center" style="width:5%">Edit</th>
            <th class="text-center" style="width:6%">Delete</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($cves as $row)
            <tr>
              <td class="text-center">{{ $cves->firstItem() + $loop->index }}.</td>

              {{-- Image --}}
              <td>
                @if($row->image_url)
                  <img src="{{ $row->image_url }}"
                       alt="img-{{ $row->cve_id }}"
                       class="img-thumbnail"
                       style="max-width:100px; max-height:80px; object-fit:cover;"
                       onerror="this.style.display='none'">
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>

              {{-- CVE ID --}}
              <td><strong>{{ $row->cve_id }}</strong></td>

              {{-- Title --}}
              <td>{{ \Illuminate\Support\Str::limit($row->title, 120, '…') }}</td>

              {{-- Vendor/Product --}}
              <td>
                <strong>{{ $row->vendor ?? '-' }}</strong><br>
                <span class="text-muted">{{ $row->product ?? '-' }}</span>
              </td>

              {{-- CVSS (plain text) --}}
              <td class="text-center">
                {{ $row->cvss_score !== null ? number_format((float)$row->cvss_score, 1) : '-' }}
              </td>

              {{-- Severity (plain text) --}}
              <td class="text-center">{{ $row->severity ?? '-' }}</td>

              {{-- Year --}}
              <td class="text-center">{{ $row->year ?? '-' }}</td>

              {{-- Exploit (plain text) --}}
              <td class="text-center">{{ $row->exploit_available ? 'Yes' : 'No' }}</td>

              {{-- Published date --}}
              <td class="text-center">
                {{ optional($row->published_date)->format('Y-m-d') ?? '-' }}
              </td>

              {{-- Edit (ไว้ทำภายหลัง) --}}
              <td class="text-center">
                <a href="{{ route('admin.backend.cve.edit', $row->cve_id) }}" class="btn btn-warning btn-sm">Edit</a>
              </td>

              {{-- Delete --}}
              <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="deleteConfirm('{{ $row->cve_id }}')">
                  delete
                </button>
                <form id="delete-form-{{ $row->cve_id }}"
                      action="{{ route('admin.backend.cve.destroy', $row->cve_id) }}"
                      method="POST" style="display:none;">
                  @csrf
                  @method('delete')
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="12" class="text-center text-muted">— ไม่มีข้อมูล —</td></tr>
          @endforelse
        </tbody>
      </table>

      <div>{{ $cves->links() }}</div>

    </div>
  </div>
</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteConfirm(id) {
  Swal.fire({
    title: 'คุณแน่ใจหรือไม่?',
    text: "หากลบแล้วจะไม่สามารถกู้คืนได้!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'ใช่, ลบเลย!',
    cancelButtonText: 'ยกเลิก'
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('delete-form-' + id).submit();
    }
  });
}
</script>
@endsection
