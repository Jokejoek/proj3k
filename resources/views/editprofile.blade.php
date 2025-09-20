<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <style>
    .avatar-lg{width:180px;height:180px;border-radius:50%;object-fit:cover;border:4px solid #2b3644;}
    .btn-soft{background:#2d3748;color:#fff;border-radius:8px;padding:6px 14px;cursor:pointer;}
    .btn-soft:hover{background:#4a5568;}
  </style>
</head>
<body>

  {{-- Navbar (ใช้ partial เดิม) --}}
  @include('partials.AfterNav')

  <div class="container py-5">

    {{-- Flash Message --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="card p-4" style="background:#111827; color:#fff; border-radius:16px;">
      <div class="row align-items-center">

        {{-- Left: Avatar --}}
        <div class="col-md-5 d-flex flex-column align-items-center mb-4 mb-md-0">
          <img id="avatarPreview"
               src="{{ $user->avatar_url ?: 'https://ui-avatars.com/api/?size=256&name='.urlencode($user->username ?? $user->email) }}"
               class="avatar-lg mb-3" alt="avatar">

          <label class="btn btn-soft">
            <i class="fa-regular fa-image mr-2"></i> Change Picture
            <input id="avatarInput" type="file" name="avatar" form="profileForm" accept="image/*" hidden>
          </label>
        </div>

        {{-- Right: Profile form --}}
        <div class="col-md-7">
          <h4 class="mb-4">Edit Profile</h4>

          <form id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Username --}}
            <div class="form-group font-weight-bold">
              <label>Username</label>
              <input type="text" name="username" value="{{ $user->username }}" class="form-control">
            </div>

            {{-- Email (readonly) --}}
            <div class="form-group">
              <label class="font-weight-bold d-block">Email</label>
              <p class="mb-0">{{ $user->email }}</p>
            </div>

            {{-- Karma Score --}}
            <div class="form-group">
              <label class="font-weight-bold d-block">Karma Score</label>
              <span class="badge badge-info">{{ $user->karma }}</span>
            </div>

            {{-- Contribution Score --}}
            <div class="form-group">
              <label class="font-weight-bold d-block">Contribution Score</label>
              <span class="badge badge-success">{{ $user->contribution_score }}</span>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- จำเป็นสำหรับ dropdown/toggler ของ Bootstrap 4 --}}
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
