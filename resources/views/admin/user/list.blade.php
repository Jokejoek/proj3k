@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">

      <div class="d-flex align-items-center justify-content-between mb-2">
        <h3 class="mb-0">:: User data ::</h3>
      </div>

      @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
          <thead>
            <tr class="table-info">
              <th class="text-center" style="width:5%">No.</th>

              {{-- เพิ่มคอลัมน์รูป --}}
              <th class="text-center" style="width:8%">รูป</th>

              <th style="width:20%">Username</th>
              <th style="width:25%">Email</th>
              <th style="width:15%">Role</th>
              <th class="text-center" style="width:5%">Edit</th>
              <th class="text-center" style="width:8%">ResetPass</th>
              <th class="text-center" style="width:8%">Delete</th>
            </tr>
          </thead>
          <tbody>
          @forelse ($UserList as $row)
            @php
              $roleName = optional($row->role)->name ?? 'user';

              // คำนวณ URL ของรูป
              // - ถ้าเป็น http/https ใช้ตรง ๆ
              // - ถ้าเป็น path ใน storage ใช้ Storage::url()
              // - ถ้าไม่มี ให้ใช้ placeholder
              $avatar = $row->avatar_url
                  ? (preg_match('/^https?:\/\//', $row->avatar_url)
                      ? $row->avatar_url
                      : Storage::url($row->avatar_url))
                  : asset('images/avatar-placeholder.png');
            @endphp
            <tr>
              <td class="text-center">{{ $UserList->firstItem() + $loop->index }}.</td>

              {{-- ช่องรูป --}}
              <td class="text-center">
                <img src="{{ $avatar }}"
                    alt="avatar"
                    class="rounded-circle"
                    style="width:40px;height:40px;object-fit:cover;box-shadow:0 0 0 1px rgba(255,255,255,.2);" />
              </td>

              <td>{{ $row->username }}</td>
              <td>{{ $row->email }}</td>
              <td><span>{{ $roleName }}</span></td>
              <td class="text-center">
                <a href="{{ route('admin.backend.users.edit', $row->user_id) }}" class="btn btn-warning btn-sm">Edit</a>
              </td>
              <td class="text-center">
                <a href="{{ route('admin.backend.users.reset', $row->user_id) }}" class="btn btn-info btn-sm">Reset</a>
              </td>
              <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteConfirm({{ $row->user_id }})">
                  Delete
                </button>
                <form id="delete-form-{{ $row->user_id }}"
                      action="{{ route('admin.backend.users.remove', $row->user_id) }}"
                      method="POST" class="d-none">
                  @csrf
                  @method('DELETE')
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">ไม่มีผู้ใช้</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>


      <div>{{ $UserList->links() }}</div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteConfirm(id) {
  const form = document.getElementById('delete-form-' + id);
  if (!form) return;

  Swal.fire({
    title: 'คุณแน่ใจหรือไม่?',
    text: 'หากลบแล้วจะไม่สามารถกู้คืนได้!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'ใช่, ลบเลย!',
    cancelButtonText: 'ยกเลิก'
  }).then((result) => {
    if (result.isConfirmed) form.submit();
  });
}
</script>
@endpush
