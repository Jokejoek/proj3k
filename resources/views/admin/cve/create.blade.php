@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">

      <div class="d-flex align-items-center justify-content-between mb-2">
        <h3 class="mb-0">:: Form Add CVE ::</h3>
        <!--<a href="{{ route('admin.backend.cve.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>-->
      </div>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.backend.cve.store') }}"
      method="POST"
      enctype="multipart/form-data">
        @csrf

        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">CVE ID</label>
          <div class="col-sm-7">
            <input type="text" name="cve_id" class="form-control"
                   placeholder="เช่น CVE-2025-12345"
                   value="{{ old('cve_id') }}" required>
            <div class="form-text">รูปแบบ: CVE-YYYY-NNNN (ตัวเลขด้านหลัง 4 หลักขึ้นไป)</div>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Title</label>
          <div class="col-sm-7">
            <input type="text" name="title" class="form-control"
                   value="{{ old('title') }}" required maxlength="150">
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Description</label>
          <div class="col-sm-7">
            <textarea name="description" class="form-control" rows="4"
                      placeholder="รายละเอียดช่องโหว่">{{ old('description') }}</textarea>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Severity</label>
          <div class="col-sm-3">
            <select name="severity" class="form-select" required>
              <option value="">-- เลือก --</option>
              @foreach($severities as $s)
                <option value="{{ $s }}" @selected(old('severity')===$s)>{{ $s }}</option>
              @endforeach
            </select>
          </div>

          <label class="col-sm-1 col-form-label">CVSS</label>
          <div class="col-sm-2">
            <input type="number" name="cvss_score" class="form-control"
                   step="0.1" min="0" max="10"
                   value="{{ old('cvss_score') }}" placeholder="0.0">
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Vendor</label>
          <div class="col-sm-3">
            <input type="text" name="vendor" class="form-control" value="{{ old('vendor') }}">
          </div>

          <label class="col-sm-1 col-form-label">Product</label>
          <div class="col-sm-3">
            <input type="text" name="product" class="form-control" value="{{ old('product') }}">
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Image</label>
          <div class="col-sm-7">
            <input type="file" name="image_file" class="form-control"
                  accept="image/*" onchange="previewCveImage(event)">
            <div class="form-text">รองรับ .jpg .png .webp (สูงสุด ~5MB)</div>

            <img id="preview-img" src="#" alt="" class="mt-2 d-none img-thumbnail"
                style="max-width:160px; max-height:120px; object-fit:cover;">

          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Year</label>
          <div class="col-sm-2">
            <select name="year" class="form-select">
              <option value="">—</option>
              @foreach($years as $y)
                <option value="{{ $y }}" @selected((string)old('year')===(string)$y)>{{ $y }}</option>
              @endforeach
            </select>
          </div>

          <label class="col-sm-2 col-form-label">Published date</label>
          <div class="col-sm-3">
            <input type="date" name="published_date" class="form-control" value="{{ old('published_date') }}">
          </div>

          <div class="col-sm-3 d-flex align-items-center">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="exploitable" name="exploit_available" value="1"
                     @checked(old('exploit_available'))>
              <label class="form-check-label" for="exploitable">
                มี exploit แล้ว
              </label>
            </div>
          </div>
        </div>

        <div class="mb-3 row">
          <label class="col-sm-2"></label>
          <div class="col-sm-7 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Insert CVE</button>
            <a href="{{ route('admin.backend.cve.index') }}" class="btn btn-danger">Cancel</a>
          </div>
        </div>

      </form>

    </div>
  </div>
</div>
<script>
function previewCveImage(evt) {
  const input = evt.target;
  const img = document.getElementById('preview-img');

  if (!input.files || !input.files[0]) {
    img.classList.add('d-none');
    img.src = '#';
    return;
  }

  const file = input.files[0];

  // จำกัด 5MB ฝั่ง client
  if (file.size > 5 * 1024 * 1024) {
    alert('ไฟล์ใหญ่เกินไป (สูงสุด 5MB)');
    input.value = '';
    img.classList.add('d-none');
    img.src = '#';
    return;
  }

  const reader = new FileReader();
  reader.onload = e => {
    img.src = e.target.result;
    img.classList.remove('d-none');
  };
  reader.readAsDataURL(file);
}
</script>
@endsection
