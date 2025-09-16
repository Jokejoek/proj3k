@extends('home')

@section('content')
<div class="container mt-4">
  <div class="row">
    <div class="col-md-12">

      <div class="d-flex align-items-center justify-content-between mb-2">
        <h3 class="mb-0">:: Form Edit CVE ::</h3>
        <a href="{{ route('admin.backend.cve.index') }}" class="btn btn-outline-secondary btn-sm">← Back</a>
      </div>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.backend.cve.update', $cve->cve_id) }}"
            method="POST"
            enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- CVE ID --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">CVE ID</label>
          <div class="col-sm-7">
            <input type="text" name="cve_id" class="form-control"
                   placeholder="เช่น CVE-2025-12345"
                   value="{{ old('cve_id', $cve->cve_id) }}" required>
            <div class="form-text">รูปแบบ: CVE-YYYY-NNNN (ตัวเลขด้านหลัง 4 หลักขึ้นไป)</div>
          </div>
        </div>

        {{-- Title --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Title</label>
          <div class="col-sm-7">
            <input type="text" name="title" class="form-control"
                   value="{{ old('title', $cve->title) }}" required maxlength="150">
          </div>
        </div>

        {{-- Description --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Description</label>
          <div class="col-sm-7">
            <textarea name="description" class="form-control" rows="4"
                      placeholder="รายละเอียดช่องโหว่">{{ old('description', $cve->description) }}</textarea>
          </div>
        </div>

        {{-- Severity + CVSS --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Severity</label>
          <div class="col-sm-3">
            @php $sev = old('severity', $cve->severity); @endphp
            <select name="severity" class="form-select" required>
              <option value="">-- เลือก --</option>
              @foreach($severities as $s)
                <option value="{{ $s }}" {{ $sev===$s ? 'selected':'' }}>{{ $s }}</option>
              @endforeach
            </select>
          </div>

          <label class="col-sm-1 col-form-label">CVSS</label>
          <div class="col-sm-2">
            <input type="number" name="cvss_score" class="form-control"
                   step="0.1" min="0" max="10"
                   value="{{ old('cvss_score', $cve->cvss_score) }}" placeholder="0.0">
          </div>
        </div>

        {{-- Vendor / Product --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Vendor</label>
          <div class="col-sm-3">
            <input type="text" name="vendor" class="form-control"
                   value="{{ old('vendor', $cve->vendor) }}">
          </div>

          <label class="col-sm-1 col-form-label">Product</label>
          <div class="col-sm-3">
            <input type="text" name="product" class="form-control"
                   value="{{ old('product', $cve->product) }}">
          </div>
        </div>

        {{-- Image (with preview) --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Image</label>
          <div class="col-sm-7">
            @if($cve->image_url)
              <div class="mb-2">
                <img src="{{ $cve->image_url }}" alt="img-{{ $cve->cve_id }}"
                     style="max-width:150px; max-height:120px; object-fit:cover">
              </div>
            @endif
            <input type="file" name="image_file" class="form-control" accept="image/*">
          </div>
        </div>

        {{-- Year / Published / Exploit --}}
        <div class="mb-3 row">
          <label class="col-sm-2 col-form-label">Year</label>
          <div class="col-sm-2">
            <select name="year" class="form-select">
              <option value="">—</option>
              @foreach($years as $y)
                <option value="{{ $y }}" {{ (int)old('year', $cve->year)===$y ? 'selected':'' }}>{{ $y }}</option>
              @endforeach
            </select>
          </div>

          <label class="col-sm-2 col-form-label">Published date</label>
          <div class="col-sm-3">
            <input type="date" name="published_date" class="form-control"
                   value="{{ old('published_date', optional($cve->published_date)->format('Y-m-d')) }}">
          </div>

          <div class="col-sm-3 d-flex align-items-center">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="exploitable" name="exploit_available" value="1"
                     {{ old('exploit_available', $cve->exploit_available) ? 'checked' : '' }}>
              <label class="form-check-label" for="exploitable">มี exploit แล้ว</label>
            </div>
          </div>
        </div>

        {{-- Actions --}}
        <div class="mb-3 row">
          <label class="col-sm-2"></label>
          <div class="col-sm-7 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Update CVE</button>
            <a href="{{ route('admin.backend.cve.index') }}" class="btn btn-danger">Cancel</a>
          </div>
        </div>

      </form>

    </div>
  </div>
</div>
@endsection
