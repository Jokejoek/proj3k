@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">

      <div class="d-flex align-items-center justify-content-between mb-2">
        <h3 class="mb-0">:: Admin data ::</h3>
        <a href="{{ route('admin.backend.admins.add') }}" class="btn btn-primary btn-sm">+ Admin</a>
      </div>

      <table class="table table-bordered table-striped table-hover">
        <thead>
          <tr class="table-info">
            <th class="text-center" style="width:5%">No.</th>
            <th style="width:20%">Admin Username</th>
            <th style="width:20%">Email</th>
            <th width="15%">Role</th>   {{-- เพิ่มคอลัมน์ --}}
            <th style="width:5%">Edit</th>
            <th style="width:5%">ResetPass</th>
            <th style="width:5%">Delete</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($AdminList as $row)
            <tr>
              <td class="text-center">{{ $AdminList->firstItem() + $loop->index }}.</td>
              <td>{{ $row->username }}</td>
              <td>{{ $row->email }}</td>
              <td>{{ $row->role->name ?? '-' }}</td> {{-- แสดง role_name --}}
              <td>
                <a href="{{ route('admin.backend.admins.edit', $row->user_id) }}" class="btn btn-warning btn-sm">Edit</a>
              </td>
              <td>
                <a href="{{ route('admin.backend.admins.reset', $row->user_id) }}" class="btn btn-info btn-sm">Reset</a>
              </td>
              <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteConfirm({{ $row->user_id }})">
                  delete
                </button>
                <form id="delete-form-{{ $row->user_id }}"
                      action="{{ route('admin.backend.admins.remove', $row->user_id) }}"
                      method="POST" style="display:none;">
                  @csrf
                  @method('delete')
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <div>{{ $AdminList->links() }}</div>

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
