@php
    use Illuminate\Support\Str;

    $u = $c->user;
    $avatarPath = $u->avatar_url;

    // ถ้าเป็น URL เต็มแล้วใช้ตรง ๆ, ถ้าเป็น path ใน public ให้ห่อด้วย asset()
    $avatar = $avatarPath
        ? (Str::startsWith($avatarPath, ['http://','https://']) ? $avatarPath : asset($avatarPath))
        : 'https://ui-avatars.com/api/?size=48&name=' . urlencode($u->username ?? $u->email);

    // กัน cache ให้โหลดไฟล์ใหม่เวลาเปลี่ยนรูป
    $version = optional($u->updated_at)->timestamp ?? time();
    $avatar .= (str_contains($avatar, '?') ? '&' : '?') . 'v=' . $version;
@endphp


@php $indent = ($level ?? 0) * 24; @endphp

<div class="comment-item mb-2" style="margin-left: {{ $indent }}px">
  <div class="d-flex align-items-start">
    <img src="{{ $c->user?->avatar_url }}" class="rounded-circle mr-2" width="28" height="28" onerror="this.src='https://i.pravatar.cc/64?u=fallback'">
    <div>
      <div class="small text-muted">
        <strong class="text-dark">{{ $c->user?->username ?? 'User' }}</strong>
        · {{ $c->created_at?->diffForHumans() }}
      </div>

      <div class="mb-1" style="white-space:pre-wrap">{{ $c->content }}</div>

      <div class="d-flex align-items-center">
      {{-- Upvote --}}
      <form method="POST" action="{{ route('comments.vote', $c->comment_id) }}" class="mr-2">
        @csrf
        <input type="hidden" name="value" value="1">
        <button type="submit" class="btn btn-sm btn-light action-btn">
          <i class="far fa-thumbs-up"></i> {{ $c->upvotes ?? 0 }}
        </button>
      </form>

      {{-- Downvote --}}
      <form method="POST" action="{{ route('comments.vote', $c->comment_id) }}" class="mr-2">
        @csrf
        <input type="hidden" name="value" value="-1">
        <button type="submit" class="btn btn-sm btn-light action-btn">
          <i class="far fa-thumbs-down"></i> {{ $c->downvotes ?? 0 }}
        </button>
      </form>

      {{-- ปุ่ม Reply --}}
      <form method="POST" action="{{ route('comments.reply', $c->comment_id) }}">
        @csrf
        <button type="submit" class="btn btn-sm btn-light action-btn">
          <i class="far fa-comment"></i> Reply
        </button>
      </form>
    </div>
    </div>
  </div>
</div>

{{-- วาดลูกแบบ recursive --}}
@if($c->replies && $c->replies->count())
  @foreach($c->replies as $child)
    @include('partials.comment_item', ['c' => $child, 'level' => ($level ?? 0)+1])
  @endforeach
@endif

