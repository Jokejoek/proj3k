@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-sm-9">

      <h3 class="mb-3">:: form Add Admin ::</h3>

      <form method="POST" action="{{ route('admin.backend.admins.store') }}">
        @csrf

        {{-- Username --}}
        <div class="form-group row mb-2">
          <label class="col-sm-2 col-form-label">Username</label>
          <div class="col-sm-6">
            <input type="text"
                   name="username"
                   class="form-control @error('username') is-invalid @enderror"
                   placeholder="username"
                   minlength="3"
                   value="{{ old('username') }}"
                   required>
            @error('username')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Email --}}
        <div class="form-group row mb-2">
          <label class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-6">
            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="email@example.com"
                   value="{{ old('email') }}"
                   required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Password --}}
        <div class="form-group row mb-2">
          <label class="col-sm-2 col-form-label">Password</label>
          <div class="col-sm-6">
            <input type="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Password"
                   minlength="8"
                   required>
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Confirm Password --}}
        <div class="form-group row mb-3">
          <label class="col-sm-2 col-form-label">Confirm</label>
          <div class="col-sm-6">
            <input type="password"
                   name="password_confirmation"
                   class="form-control"
                   placeholder="Confirm password"
                   minlength="8"
                   required>
          </div>
        </div>

        {{-- Actions --}}
        <div class="form-group row">
          <label class="col-sm-2"></label>
          <div class="col-sm-6">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.backend.admins.index') }}" class="btn btn-danger">Cancel</a>
          </div>
        </div>

      </form>

    </div>
  </div>
</div>
@endsection
