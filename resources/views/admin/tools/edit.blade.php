@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">

      <div class="d-flex align-items-center justify-content-between mb-2">
        <h3 class="mb-0">:: Form Edit Tool ::</h3>
        <!--<a href="{{ route('admin.backend.tools.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>-->
      </div>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.backend.tools.update', $tool->tool_id) }}"
            method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Name --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
          <div class="col-sm-7">
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $tool->name) }}" maxlength="100" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Category --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Category <span class="text-danger">*</span></label>
          <div class="col-sm-3">
            <input type="text" name="category"
                   class="form-control @error('category') is-invalid @enderror"
                   value="{{ old('category', $tool->category) }}" maxlength="50" required
                   placeholder="เช่น Web, Scanner, Exploitation">
            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Title --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Title</label>
          <div class="col-sm-7">
            <input type="text" name="title"
                   class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $tool->title) }}">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Description --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Description</label>
          <div class="col-sm-7">
            <textarea name="description"
                      class="form-control @error('description') is-invalid @enderror"
                      rows="4"
                      placeholder="รายละเอียดของเครื่องมือ">{{ old('description', $tool->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Image (current + upload new) --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Image</label>
          <div class="col-sm-7">
            @if($tool->image_url)
              <div class="mb-2">
                <img src="{{ $tool->image_url }}"
                     alt="tool-{{ $tool->tool_id }}"
                     class="img-thumbnail"
                     style="max-width:150px; max-height:120px; object-fit:cover;"
                     onerror="this.src='{{ asset('images/no-image.png') }}'">
              </div>
            @endif

            <input type="file" name="image_file"
                   class="form-control @error('image_file') is-invalid @enderror"
                   accept="image/*" onchange="previewToolImage(event)">
            <div class="form-text">รองรับ .jpg .png .webp (สูงสุด ~5MB)</div>
            @error('image_file') <div class="invalid-feedback">{{ $message }}</div> @enderror

            {{-- Preview of new file --}}
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
                   value="{{ old('download_link', $tool->download_link) }}" placeholder="https://...">
            @error('download_link') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>

        {{-- Actions --}}
        <div class="mb-3 row">
          <label class="col-sm-2"></label>
          <div class="col-sm-7 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update Tool</button>
            <a href="{{ route('admin.backend.tools.index') }}" class="btn btn-danger">Cancel</a>
          </div>
        </div>

      </form>

    </div>
  </div>
</div>

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
  // ถ้าต้องการเช็คขนาด 5MB ฝั่ง client:
  if (file.size > 5 * 1024 * 1024) {
    alert('ไฟล์ใหญ่เกินไป (สูงสุด 5MB)');
    input.value = '';
    img.classList.add('d-none');
    img.src = '#';
    return;
  }
  const reader = new FileReader();
  reader.onload = e => { img.src = e.target.result; img.classList.remove('d-none'); };
  reader.readAsDataURL(file);
}
</script>
@endsection
