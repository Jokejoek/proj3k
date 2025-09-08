@extends('layout.layoutshowTools')
@section('title', $tool->name.' - Tools')

@section('content')
<div class="container tool-detail py-4">

  {{-- ชื่อเรื่อง --}}
  <h1 class="detail-title mb-2">{{ $tool->name }}</h1>

  {{-- ชิป/แท็ก --}}
  <div class="detail-tags mb-3">
    @if($tool->category)
      <span class="badge-cat">{{ strtolower($tool->category) }}</span>
    @endif

    @php
      // รองรับกรณีมีคอลัมน์ tags หรือ os เป็น comma string (ถ้าไม่มีจะข้ามอัตโนมัติ)
      $extraTags = [];
      if(!empty($tool->tags)) {
        $extraTags = array_filter(array_map('trim', explode(',', $tool->tags)));
      }
      if(!empty($tool->os)) {
        $osTags = is_array($tool->os) ? $tool->os : explode(',', $tool->os);
        $extraTags = array_merge($extraTags, array_map('trim', $osTags));
      }
    @endphp
    @foreach($extraTags as $tg)
      <span class="chip">{{ strtolower($tg) }}</span>
    @endforeach
  </div>

  {{-- รูปใหญ่ (hero) --}}
  <div class="detail-hero mb-4">
    <img
      src="{{ $tool->image_url ?: 'https://picsum.photos/seed/'.md5($tool->name).'/1200/600' }}"
      alt="{{ $tool->name }}">
  </div>

  {{-- เนื้อหา --}}
  <div class="detail-body">
    @if($tool->title)
      <p class="lead mb-3">{{ $tool->title }}</p>
    @endif

    @if($tool->description)
      <div class="mb-3">{!! nl2br(e($tool->description)) !!}</div>
    @endif
  </div>

  {{-- ปุ่มแอ็กชัน --}}
  <div class="detail-actions mt-3">
    @if($tool->download_link)
      <a href="{{ $tool->download_link }}" target="_blank" class="btn btn-success mr-2">ดาวน์โหลด</a>
    @endif
  </div>

  {{-- ข้อมูลเสริม --}}
  <div class="detail-meta mt-3">
    <small class="text-muted">
      อัปเดตล่าสุด: {{ optional($tool->updated_at)->format('d M Y H:i') }}
    </small>
  </div>

</div>
@endsection
