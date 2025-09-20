<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProJ3K</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  
    <style>
    
    .modal-content{ background:#0f1626; color:#e8edf5; border-radius:14px; }
    .cmt-head{ padding:12px 16px; border-bottom:1px solid rgba(255,255,255,.08);}
    .cmt-body{ max-height:55vh; overflow:auto; padding:10px 16px;}
    .cmt-item{ display:flex; align-items:flex-start; margin-bottom:12px;}
    .cmt-item img{ width:36px; height:36px; border-radius:50%; object-fit:cover; margin-right:10px; }
    .cmt-bubble{ background:#1a2235; border:1px solid rgba(255,255,255,.06); border-radius:12px; padding:8px 12px; }
    .cmt-meta{ font-size:.8rem; color:#9fb2c3; }
    .cmt-footer{ padding:12px 16px; border-top:1px solid rgba(255,255,255,.08);}
    .cmt-footer input[type=text]{ background:#101827; color:#e8edf5; border:1px solid #293245; border-radius:18px; padding:8px 12px; }
    .action-btn{ cursor:pointer; margin-right:12px; }
  </style>
</head>
<body>

  @include('partials.AfterNav')

  {{-- กล่องโพสต์ด้านบน --}}
  @auth
    <div class="composer-wrap">
      <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf

        <input type="text" name="title" class="composer-input mb-2" placeholder="เพิ่มหัวข้อ" required>
        <input type="text" name="content" class="composer-input" placeholder="เพิ่มเนื้อหา" required>

        <div id="composerPreview" class="composer-preview" style="display:none; margin-top:10px;">
          <img id="composerPreviewImg" src="#" alt="preview" style="max-height:200px; border-radius:12px;">
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
    <div class="post-card" id="post-{{ $post->post_id }}">
      <div class="post-header d-flex align-items-center mb-2">
        <img
          src="{{ $post->user->avatar_url ?? 'https://ui-avatars.com/api/?size=48&name='.urlencode($post->user->username) }}"
          class="rounded-circle mr-2" width="24" height="24" alt="avatar">
        <div class="post-meta">{{ $post->user->username }} · {{ $post->created_at->diffForHumans() }}</div>
      </div>

      <h5 style="font-weight:600; color:#000;">{{ $post->title }}</h5>

      <div class="mb-2" style="white-space: pre-line; color:#111;">
        {{ $post->content }}
      </div>

      @if($post->image_url)
        <img src="{{ $post->image_url }}" class="post-img mb-2" alt="post-image">
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

          {{-- ปุ่มเปิดคอมเมนต์ (Modal) --}}
          <button class="btn btn-sm btn-light action-btn"
                  data-toggle="modal" data-target="#cmt-{{ $post->post_id }}">
            <i class="far fa-comment"></i> {{ $post->comments->count() }}
          </button>
        </div>
      </div>
    </div>

    {{-- ===== Modal แสดงคอมเมนต์ของโพสต์นี้ ===== --}}
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
              <div class="cmt-item">
                <img
                  src="{{ $c->user->avatar_url ?? 'https://ui-avatars.com/api/?size=48&name='.urlencode($c->user->username ?? $c->user->email) }}"
                  alt="avt">
                <div>
                  <div class="cmt-bubble">
                    <strong>{{ $c->user->username }}</strong>
                    <div>{{ $c->content }}</div>
                  </div>
                  <div class="cmt-meta mt-1">{{ $c->created_at->diffForHumans() }}</div>
                </div>
              </div>
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
    {{-- ===== /Modal ===== --}}

  @endforeach

  <div class="pager-wrap mt-3">
    {{ $posts->links() }}
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Preview รูปตอนโพสต์ --}}
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
          if (!file || !file.type.startsWith('image/')) { wrap.style.display='none'; imgEl.removeAttribute('src'); return; }
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
</body>
</html>
