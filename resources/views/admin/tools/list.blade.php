@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">

      <div class="d-flex align-items-center justify-content-between mb-2">
        <h3 class="mb-0">:: Tools data ::</h3>
        <a href="{{ route('admin.backend.tools.create') }}" class="btn btn-primary btn-sm">+ Tool</a>
      </div>

      <table class="table table-bordered table-striped table-hover align-middle">
        <thead>
          <tr class="table-info">
            <th class="text-center" style="width:6%">No.</th>
            <th style="width:12%">Image</th>
            <th style="width:16%">Name</th>
            <th style="width:28%">Title</th>
            <th style="width:12%">Category</th>
            <th class="text-center" style="width:8%">Popularity</th>
            <th class="text-center" style="width:8%">Download</th>
            <th class="text-center" style="width:10%">Created</th>
            <th class="text-center" style="width:6%">Edit</th>
            <th class="text-center" style="width:6%">Delete</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($tools as $row)
            <tr>
              <td class="text-center">{{ $tools->firstItem() + $loop->index }}.</td>

              {{-- Image --}}
              <td>
                @if($row->image_url)
                  <img src="{{ $row->image_url }}"
                      alt="tool-{{ $row->tool_id }}"
                      class="img-thumbnail"
                      style="max-width:100px; max-height:80px; object-fit:cover;"
                      onerror="this.src='{{ asset('images/no-image.png') }}'">
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>

              {{-- Name --}}
              <td><strong>{{ $row->name ?? '-' }}</strong></td>

              {{-- Title --}}
              <td>
                @php
                  $title = $row->title ?? '';
                @endphp
                {{ $title !== '' ? \Illuminate\Support\Str::limit($title, 120, '…') : '-' }}
              </td>

              {{-- Category --}}
              <td>{{ $row->category ?? '-' }}</td>

              {{-- Popularity --}}
              <td class="text-center">{{ $row->popularity_score ?? 0 }}</td>

              {{-- Download link --}}
              <td class="text-center">
                @if(!empty($row->download_link))
                  <a href="{{ $row->download_link }}" target="_blank" rel="noopener"
                     class="btn btn-outline-info btn-sm">Link</a>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>

              {{-- Created at --}}
              <td class="text-center">
                {{ optional($row->created_at)->format('Y-m-d') ?? '-' }}
              </td>

              {{-- Edit --}}
              <td class="text-center">
                <a href="{{ route('admin.backend.tools.edit', $row->tool_id) }}"
                   class="btn btn-warning btn-sm">Edit</a>
              </td>

              {{-- Delete --}}
              <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="deleteTool('{{ $row->tool_id }}')">
                  delete
                </button>
                <form id="delete-tool-{{ $row->tool_id }}"
                      action="{{ route('admin.backend.tools.destroy', $row->tool_id) }}"
                      method="POST" style="display:none;">
                  @csrf
                  @method('delete')
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="10" class="text-center text-muted">— ไม่มีข้อมูล —</td></tr>
          @endforelse
        </tbody>
      </table>

      <div>{{ $tools->links() }}</div>

    </div>
  </div>
</div>

{{-- SweetAlert (ใช้ชุดเดียวกับหน้า CVE) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteTool(id) {
  Swal.fire({
    title: 'คุณแน่ใจหรือไม่?',
    text: "ลบแล้วจะไม่สามารถกู้คืนได้!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'ใช่, ลบเลย!',
    cancelButtonText: 'ยกเลิก'
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('delete-tool-' + id).submit();
    }
  });
}
</script>
@endsection
