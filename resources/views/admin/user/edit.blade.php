@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-sm-12">

      <h3>:: Update Admin ::</h3>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- ใช้ $user_id แทน $id --}}
      <form method="POST" action="{{ route('admin.backend.users.update', $user_id) }}">
        @csrf
        @method('PUT')

        {{-- Username --}}
        <div class="form-group row mb-3">
          <label class="col-sm-2 col-form-label">Username</label>
          <div class="col-sm-6">
            <input
              type="text"
              name="username"
              class="form-control @error('username') is-invalid @enderror"
              required minlength="3"
              value="{{ old('username', $username ?? '') }}"
            >
            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Email --}}
        <div class="form-group row mb-3">
          <label class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-6">
            <input
              type="email"
              name="email"
              class="form-control @error('email') is-invalid @enderror"
              required
              value="{{ old('email', $email ?? '') }}"
            >
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Actions --}}
        <div class="form-group row">
          <label class="col-sm-2"></label>
          <div class="col-sm-6">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.backend.users') }}" class="btn btn-danger">Cancel</a>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
