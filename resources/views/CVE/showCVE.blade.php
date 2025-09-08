@extends('layout.layoutshowCVE')
@section('title', $cve->cve_id.' - CVE')

@section('content')
<div class="container tool-detail py-4 ">
  <h1 class="detail-title mb-2">{{ $cve->cve_id }}</h1>

    <div class="detail-tags mb-3">
        <span class="badge-sev sev-{{ strtolower($cve->severity) }}">
            {{ ucfirst(strtolower($cve->severity)) }}
        </span>

        @if(!is_null($cve->cvss_score))
            <span class="cvss-chip">CVSS {{ number_format((float)$cve->cvss_score, 1) }}</span>
        @endif

        @if(!is_null($cve->year))
            <span class="year-chip">{{ $cve->year }}</span>
        @endif
    </div>

  <div class="detail-hero mb-4">
    <img src="{{ $cve->image_url ?: 'https://picsum.photos/seed/'.md5($cve->cve_id).'/1200/600' }}"
         alt="{{ $cve->cve_id }}">
  </div>

  <div class="detail-body">
    @if($cve->title)
      <p class="lead mb-3">{{ $cve->title }}</p>
    @endif

    @if($cve->vendor || $cve->product)
      <p class="mb-2"><strong>Product:</strong>
        {{ trim(($cve->vendor ? $cve->vendor.' ' : '').($cve->product ?? '')) }}
      </p>
    @endif

    @if($cve->description)
      <div class="mb-3">{!! nl2br(e($cve->description)) !!}</div>
    @endif
  </div>

  <div class="detail-meta mt-3">
    <small class="text-muted">
      {{ $cve->published_date instanceof \Carbon\Carbon ? $cve->published_date->format('Y-m-d H:i') : $cve->published_date }}
      |
      {{ $cve->last_modified instanceof \Carbon\Carbon ? $cve->last_modified->format('Y-m-d H:i') : $cve->last_modified }}
    </small>
  </div>
</div>
@endsection
