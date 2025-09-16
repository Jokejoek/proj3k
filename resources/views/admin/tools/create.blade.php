@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">

      <div class="d-flex align-items-center justify-content-between mb-2">
        <h3 class="mb-0">:: Form Add Tool ::</h3>
        <a href="{{ route('admin.backend.tools.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
      </div>

      {{-- Global validation errors --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.backend.tools.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- Name --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
          <div class="col-sm-7">
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" maxlength="100" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Category + Popularity --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Category <span class="text-danger">*</span></label>
          <div class="col-sm-3">
            <input type="text" name="category" class="form-control @error('category') is-invalid @enderror"
                   value="{{ old('category') }}" maxlength="50" required
                   placeholder="เช่น Web, Scanner, Exploitation">
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <label class="col-sm-2 col-form-label">Popularity</label>
          <div class="col-sm-2">
            <input type="number" name="popularity_score" class="form-control @error('popularity_score') is-invalid @enderror"
                   value="{{ old('popularity_score', 0) }}" min="0" step="1">
            @error('popularity_score') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Title --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Title</label>
          <div class="col-sm-7">
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title') }}">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Description --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Description</label>
          <div class="col-sm-7">
            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4"
                      placeholder="รายละเอียดของเครื่องมือ">{{ old('description') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Image upload --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Image</label>
          <div class="col-sm-7">
            <input type="file" name="image_file"
                   class="form-control @error('image_file') is-invalid @enderror"
                   accept="image/*" onchange="previewToolImage(event)">
            <div class="form-text">รองรับ .jpg .png .webp (สูงสุด ~5MB)</div>
            @error('image_file') <div class="invalid-feedback">{{ $message }}</div> @enderror

            {{-- Preview --}}
            <img id="preview-img" src="#" alt="" class="mt-2 d-none img-thumbnail"
                 style="max-width:160px; max-height:120px; object-fit:cover;">
          </div>
        </div>

        {{-- Download link --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Download Link</label>
          <div class="col-sm-7">
            <input type="url" name="download_link"
                   class="form-control @error('download_link') is-invalid @enderror"
                   value="{{ old('download_link') }}" placeholder="https://...">
            @error('download_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Buttons --}}
        <div class="mb-3 row">
          <label class="col-sm-2"></label>
          <div class="col-sm-7 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Insert Tool</button>
            <a href="{{ route('admin.backend.tools.index') }}" class="btn btn-danger">Cancel</a>
          </div>
        </div>

      </form>

    </div>
  </div>
</div>

{{-- image preview --}}
<script>
function previewToolImage(evt) {
  const input = evt.target;
  const img = document.getElementById('preview-img');
  if (!input.files || !input.files[0]) {
    img.classList.add('d-none');
    img.src = '#';
    return;
  }
  const file = input.files[0];
  const reader = new FileReader();
  reader.onload = e => {
    img.src = e.target.result;
    img.classList.remove('d-none');
  };
  reader.readAsDataURL(file);
}
</script>
@endsection
