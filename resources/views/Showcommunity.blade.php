{{-- resources/views/community/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $post->title }} · Community</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  {{-- ใช้ชุดเดียวกับหน้า Community เพื่อคุมโทนสี/สไตล์ --}}
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('css/community.css') }}">
  <link rel="stylesheet" href="{{ asset('css/Tools.css') }}">
</head>
<body class="bg-darkish">
  @include('partials.AfterNav')

  @php
    $pid = $post->post_id ?? $post->id;

    // avatar ผู้โพสต์ (รองรับทั้ง URL และไฟล์ใน public)
    $avatarPath = $post->user?->avatar_url;
    $avatar = $avatarPath
      ? (\Illuminate\Support\Str::startsWith($avatarPath, ['http://','https://']) ? $avatarPath : asset($avatarPath))
      : 'https://ui-avatars.com/api/?size=48&name='.urlencode($post->user->username ?? $post->user->email ?? 'user');

    // กัน cache avatar
    $version = optional($post->user?->updated_at)->timestamp ?? time();
    $avatar .= (str_contains($avatar, '?') ? '&' : '?')."v={$version}";

    // รูปโพสต์ (รองรับ URL และ storage path)
    $img = $post->image_url
      ? (\Illuminate\Support\Str::startsWith($post->image_url, ['http://','https://'])
          ? $post->image_url
          : asset('storage/'.ltrim($post->image_url,'/')))
      : null;
  @endphp

  <main class="flex-grow-1">
    <div class="container py-4">  

      {{-- โพสต์หลัก: ใช้ post-card เหมือนหน้า Community --}}
      <div class="post-card mb-4" id="post-{{ $pid }}">
        <div class="post-header d-flex align-items-center mb-2">
          <img src="{{ $avatar }}" class="rounded-circle mr-2" width="36" height="36" alt="avatar"
               onerror="this.src='https://ui-avatars.com/api/?size=48&name={{ urlencode($post->user->username ?? 'user') }}'">
          <div class="post-meta">
            {{ $post->user?->username ?? 'Unknown' }} · {{ $post->created_at?->diffForHumans() }}
          </div>
        </div>

        <h5 class="post-title">{{ $post->title }}</h5>

        <div class="mb-2" style="white-space: pre-line; color:#111;">
          {{ $post->content }}
        </div>

        @if($img)
          <img src="{{ $img }}" class="post-img mb-2" alt="post-image">
        @endif

        {{-- ปุ่ม action ให้เหมือนหน้า Community --}}
        <div class="post-footer">
          <div class="d-flex align-items-center">
            {{-- Upvote --}}
            <form method="POST" action="{{ route('posts.vote', $pid) }}" class="mr-2">
              @csrf
              <input type="hidden" name="value" value="1">
              <button type="submit" class="btn btn-sm btn-light action-btn">
                <i class="far fa-thumbs-up"></i> {{ $post->upvotes ?? 0 }}
              </button>
            </form>
            {{-- Downvote --}}
            <form method="POST" action="{{ route('posts.vote', $pid) }}" class="mr-3">
              @csrf
              <input type="hidden" name="value" value="-1">
              <button type="submit" class="btn btn-sm btn-light action-btn">
                <i class="far fa-thumbs-down"></i> {{ $post->downvotes ?? 0 }}
              </button>
            </form>

            {{-- Reply/Comments (เปิด modal) --}}
            <button class="btn btn-sm btn-light action-btn" data-toggle="modal" data-target="#cmt-{{ $pid }}">
              <i class="far fa-comment"></i> {{ $post->comment_count ?? $post->comments->count() }}
            </button>
          </div>
        </div>
      </div>

      {{-- Modal คอมเมนต์ (อย่างเดียว ไม่ซ้ำ inline) --}}
      <div class="modal fade" id="cmt-{{ $pid }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">
            <div class="cmt-head d-flex align-items-center justify-content-between">
              <div class="font-weight-bold">
                <i class="far fa-comments mr-2"></i> Comments · <span class="text-muted">#{{ $pid }}</span>
              </div>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <div class="cmt-body" id="cmt-body-{{ $pid }}">
              @forelse($post->comments as $c)
                @include('partials.comment_item', ['c' => $c, 'level' => 0])
              @empty
                <div class="text-center text-muted py-4">ยังไม่มีคอมเมนต์</div>
              @endforelse
            </div>

            @auth
              <div class="cmt-footer">
                <form method="POST" action="{{ route('posts.comments.store', $pid) }}" class="d-flex w-100">
                  @csrf
                  <input type="hidden" name="parent_id" id="reply-parent-id" value="">
                  <input type="text" name="content" class="form-control mr-2" placeholder="เขียนคอมเมนต์..." required>
                  <button class="btn btn-lime" type="submit">ส่ง</button>
                </form>
              </div>
            @endauth
          </div>
        </div>
      </div>

    </div>
  </main>

  {{-- JS --}}
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // เวลาเปิด modal ให้เลื่อนไปท้ายสุด
    $('.modal').on('shown.bs.modal', function(){
      const body = this.querySelector('.cmt-body');
      if(body){ body.scrollTop = body.scrollHeight; }
    });

    // ปุ่ม Reply ใน partials.comment_item: ต้องมี data-reply-parent="<id>"
    // คลิกแล้ว set parent_id ในฟอร์มด้านล่าง modal
    document.addEventListener('click', function(e){
      const btn = e.target.closest('[data-reply-parent]');
      if(!btn) return;
      const parentId = btn.getAttribute('data-reply-parent');
      const input = document.getElementById('reply-parent-id');
      if(input){ input.value = parentId; }
      // เปิด modal ถ้ายังไม่เปิด
      const modal = document.getElementById('cmt-{{ $pid }}');
      if(modal && !$(modal).hasClass('show')){
        $(modal).modal('show');
      }
    });
  </script>
</body>
</html>
