<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProJ3K</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>

    <!-- Navbar -->
    @include('partials.AfterNav')
    
    {{-- กล่องโพสต์ด้านบน --}}
    @auth
      <div class="composer-wrap">
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
          @csrf

          {{-- ช่องกรอกหัวข้อ --}}
          <input type="text" name="title" class="composer-input mb-2"
                 placeholder="เพิ่มหัวข้อ" required>

          {{-- ช่องกรอกเนื้อหา --}}
          <input type="text" name="content" class="composer-input"
                 placeholder="เพิ่มเนื้อหา" required>

          {{-- Preview รูป --}}
          <div id="composerPreview" class="composer-preview" style="display:none; margin-top:10px;">
            <img id="composerPreviewImg" src="#" alt="preview"
                 style="max-height:200px; border-radius:12px;">
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
      <div class="composer-wrap text-center">
        <span class="text-muted">กรุณาเข้าสู่ระบบเพื่อโพสต์</span>
      </div>
    @endauth

    {{-- รายการโพสต์ --}}
    @foreach($posts as $post)
      <div class="post-card">
        <div class="post-header d-flex align-items-center mb-2">
          <img src="{{ $post->user->avatar_url ?? 'https://ui-avatars.com/api/?size=48&name='.urlencode($post->user->username) }}"
               class="rounded-circle mr-2" width="24" height="24" alt="avatar">
          <div>
            <div class="post-meta">{{ $post->user->username }} · {{ $post->created_at->diffForHumans() }}</div>
          </div>
        </div>

        {{-- แสดงหัวข้อ --}}
        <h5 style="font-weight:600; color:#000;">{{ $post->title }}</h5>

        {{-- แสดงเนื้อหา --}}
        <div class="mb-2" style="white-space: pre-line; color:#111;">
          {{ $post->content }}
        </div>

        {{-- แสดงรูปถ้ามี --}}
        @if($post->image_url)
          <img src="{{ $post->image_url }}" class="post-img mb-2" alt="post-image">
        @endif

        <div class="post-footer">
          <div class="d-flex align-items-center">
            <form method="POST" action="{{ route('posts.vote', $post->post_id) }}" class="mr-2">
              @csrf
              <input type="hidden" name="value" value="1">
              <button class="action-btn"><i class="far fa-thumbs-up"></i> {{ $post->upvotes }}</button>
            </form>
            <form method="POST" action="{{ route('posts.vote', $post->post_id) }}" class="mr-3">
              @csrf
              <input type="hidden" name="value" value="-1">
              <button class="action-btn"><i class="far fa-thumbs-down"></i> {{ $post->downvotes }}</button>
            </form>
            <span class="text-muted"><i class="far fa-comment"></i> {{ $post->comments->count() }}</span>
          </div>

          @auth
            <form method="POST" action="{{ route('posts.comments.store', $post->post_id) }}" class="mt-2">
              @csrf
              <input type="text" name="content" class="comment-input" placeholder="Comment" required>
            </form>
          @endauth
        </div>
      </div>
    @endforeach

    <div class="pager-wrap mt-3">
      {{ $posts->links() }}
    </div>

    {{-- Bootstrap JS (จำเป็นสำหรับ dropdown / navbar toggler) --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script Preview รูป --}}
    <script>
      (function () {
        const input   = document.getElementById('composerImage');
        const wrap    = document.getElementById('composerPreview');
        const imgEl   = document.getElementById('composerPreviewImg');
        let objectUrl = null;

        if (!input) return;

        input.addEventListener('change', function () {
          const file = this.files && this.files[0] ? this.files[0] : null;

          if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = null; }

          if (!file || !file.type.startsWith('image/')) {
            wrap.style.display = 'none';
            imgEl.removeAttribute('src');
            return;
          }

          objectUrl = URL.createObjectURL(file);
          imgEl.src = objectUrl;
          wrap.style.display = 'block';
        });
      })();
    </script>
</body>
</html>
