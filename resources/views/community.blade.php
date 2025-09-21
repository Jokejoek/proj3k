<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ProJ3K</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <link rel="stylesheet" href="{{ asset('css/community.css') }}">
  <link rel="stylesheet" href="{{ asset('css/Tools.css') }}">
</head>
<body>
  @include('partials.AfterNav')

  {{-- กล่องโพสต์ --}}
  @auth
    <div class="composer-wrap mb-4">
      <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" class="composer-input mb-2"
               placeholder="เพิ่มหัวข้อ" value="{{ old('title') }}" required>
        <textarea name="content" class="composer-input" rows="3"
                  placeholder="เพิ่มเนื้อหา" required>{{ old('content') }}</textarea>

        {{-- error messages --}}
        @error('title')   <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        @error('image')   <div class="text-danger small mt-1">{{ $message }}</div> @enderror

        {{-- preview --}}
        <div id="composerPreview" class="composer-preview" style="display:none; margin-top:10px;">
          <img id="composerPreviewImg" alt="preview" style="max-height:200px; border-radius:12px;">
        </div>

        <div class="composer-actions mt-2">
          <label class="btn-lime btn-file">
            แนบรูป
            <input id="composerImage" type="file" name="image" accept="image/*">
          </label>
          <button type="submit" class="btn-lime">Jek</button>
        </div>
      </form>
    </div>
  @else
    <div class="composer-wrap text-center mb-4">
      <span class="text-muted">กรุณาเข้าสู่ระบบเพื่อโพสต์</span>
    </div>
  @endauth

  {{-- รายการโพสต์ --}}
  @foreach($posts as $post)
    <div class="post-card mb-4" id="post-{{ $post->post_id }}">
      <div class="post-header d-flex align-items-center mb-2">
        @php
          $avatarPath = $post->user->avatar_url;
          $avatar = $avatarPath
              ? (\Illuminate\Support\Str::startsWith($avatarPath, ['http://','https://'])
                  ? $avatarPath
                  : asset($avatarPath))
              : 'https://ui-avatars.com/api/?size=48&name='.urlencode($post->user->username ?? $post->user->email);

          // ถ้ามี updated_at ให้ใช้ timestamp เป็นเวอร์ชัน
          $version = optional($post->user->updated_at)->timestamp ?? time();
          $avatar .= (str_contains($avatar, '?') ? '&' : '?')."v={$version}";
        @endphp

        <img src="{{ $avatar }}" class="rounded-circle mr-2" width="36" height="36" alt="avatar">
        <div class="post-meta">{{ $post->user->username }} · {{ $post->created_at->diffForHumans() }}</div>
      </div>

      <h5 class="post-title">{{ $post->title }}</h5>

      <div class="mb-2" style="white-space: pre-line; color:#111;">
        {{ $post->content }}
      </div>

      @if($post->image_url)
        <img src="{{ asset('storage/'.$post->image_url) }}" class="post-img mb-2" alt="post-image">
      @endif

      <div class="post-footer">
        <div class="d-flex align-items-center">
          {{-- โหวต --}}
          <form method="POST" action="{{ route('posts.vote', $post->post_id) }}" class="mr-2">
            @csrf
            <input type="hidden" name="value" value="1">
            <button type="submit" class="btn btn-sm btn-light action-btn">
              <i class="far fa-thumbs-up"></i> {{ $post->upvotes }}
            </button>
          </form>
          <form method="POST" action="{{ route('posts.vote', $post->post_id) }}" class="mr-3">
            @csrf
            <input type="hidden" name="value" value="-1">
            <button type="submit" class="btn btn-sm btn-light action-btn">
              <i class="far fa-thumbs-down"></i> {{ $post->downvotes }}
            </button>
          </form>

          {{-- ปุ่มเปิดคอมเมนต์ --}}
          <button class="btn btn-sm btn-light action-btn"
                  data-toggle="modal" data-target="#cmt-{{ $post->post_id }}">
            <i class="far fa-comment"></i> {{ $post->comment_count }}
          </button>
        </div>
      </div>
    </div>

    {{-- Modal คอมเมนต์ --}}
    <div class="modal fade" id="cmt-{{ $post->post_id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="cmt-head d-flex align-items-center justify-content-between">
            <div class="font-weight-bold">
              <i class="far fa-comments mr-2"></i> Comments · <span class="text-muted">#{{ $post->post_id }}</span>
            </div>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="cmt-body" id="cmt-body-{{ $post->post_id }}">
            @forelse($post->comments as $c)
              @include('partials.comment_item', ['c' => $c, 'level' => 0])
            @empty
              <div class="text-center text-muted py-4">ยังไม่มีคอมเมนต์</div>
            @endforelse
          </div>


          @auth
            <div class="cmt-footer">
              <form method="POST" action="{{ route('posts.comments.store', $post->post_id) }}" class="d-flex">
                @csrf
                <input type="text" name="content" class="form-control mr-2" placeholder="เขียนคอมเมนต์..." required>
                <button class="btn btn-lime" type="submit">ส่ง</button>
              </form>
            </div>
          @endauth
        </div>
      </div>
    </div>
  @endforeach

  <div class="pager-wrap mt-3">
    {{ $posts->links() }}
  </div>

  {{-- JS --}}
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function () {
      const input = document.getElementById('composerImage');
      const wrap  = document.getElementById('composerPreview');
      const imgEl = document.getElementById('composerPreviewImg');
      let objectUrl = null;

      if (input){
        input.addEventListener('change', function () {
          const file = this.files && this.files[0] ? this.files[0] : null;
          if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = null; }
          if (!file || !file.type.startsWith('image/')) {
            wrap.style.display='none'; imgEl.removeAttribute('src'); return;
          }
          objectUrl = URL.createObjectURL(file);
          imgEl.src = objectUrl;
          wrap.style.display = 'block';
        });
      }

      // เวลาเปิด modal ให้เลื่อนไปท้ายสุดของรายการคอมเมนต์
      $('.modal').on('shown.bs.modal', function(){
        const body = this.querySelector('.cmt-body');
        if(body){ body.scrollTop = body.scrollHeight; }
      });
    })();
  </script>
  <script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".vote-btn").forEach(btn => {
    btn.addEventListener("click", function () {
      const wrap = this.closest(".vote-buttons");
      const postId = wrap.dataset.post;
      const value = this.dataset.value;

      fetch(`/posts/${postId}/vote`, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
          "Accept": "application/json",
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ value })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          wrap.querySelector(".up-count").textContent = data.upvotes;
          wrap.querySelector(".down-count").textContent = data.downvotes;
        }
      })
      .catch(err => console.error(err));
    });
  });
});
</script>
</body>
</html>
