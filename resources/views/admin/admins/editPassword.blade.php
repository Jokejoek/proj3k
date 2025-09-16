@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-sm-12">
      <h3>:: Reset Admin Password ::</h3>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.backend.admins.reset.update', $user_id) }}">
        @csrf
        @method('PUT')

        {{-- Readonly info --}}
        <div class="form-group row mb-3">
          <label class="col-sm-2 col-form-label">Username</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" value="{{ $username }}" disabled>
          </div>
        </div>

        <div class="form-group row mb-3">
          <label class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-6">
            <input type="email" class="form-control" value="{{ $email }}" disabled>
          </div>
        </div>

        {{-- New password --}}
        <div class="form-group row mb-3">
          <label class="col-sm-2 col-form-label">New Password</label>
          <div class="col-sm-6">
            <input
              type="password"
              name="password"
              class="form-control @error('password') is-invalid @enderror"
              placeholder="อย่างน้อย 8 ตัวอักษร"
              required
              minlength="8"
              autocomplete="new-password"
            >
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Confirm --}}
        <div class="form-group row mb-4">
          <label class="col-sm-2 col-form-label">Confirm Password</label>
          <div class="col-sm-6">
            <input
              type="password"
              name="password_confirmation"
              class="form-control"
              placeholder="ยืนยันรหัสผ่าน"
              required
              minlength="8"
              autocomplete="new-password"
            >
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-2"></label>
          <div class="col-sm-6">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.backend.admins.index') }}" class="btn btn-danger">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
