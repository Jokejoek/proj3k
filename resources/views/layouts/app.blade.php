<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Community' }}</title>

  {{-- ใช้ Tailwind CDN ง่าย ๆ สำหรับ Dev (ภายหลังค่อยย้ายไป Vite ก็ได้) --}}
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body { background: #0b1220; } /* โทนมืดเหมือน mockup */
  </style>
</head>
<body class="text-white min-h-screen">
  {{-- Top Bar --}}
  <nav class="bg-[#0e1a2f] border-b border-white/10">
    <div class="container mx-auto px-4 py-3 flex items-center justify-between">
      <a href="{{ url('/') }}" class="font-semibold">ProJ3k</a>
      <ul class="flex gap-4 text-sm">
        <li><a href="{{ route('community.index') }}" class="hover:underline">Community</a></li>
        @auth
          <li class="opacity-75">{{ auth()->user()->username }}</li>
          {{-- ปุ่ม logout ใส่ภายหลังได้ --}}
        @else
          <li><a href="{{ url('/login') }}" class="hover:underline">Login</a></li>
        @endauth
      </ul>
    </div>
  </nav>

  {{-- Main --}}
  <main class="container mx-auto px-4 py-6">
    @yield('content')
  </main>
</body>
</html>
