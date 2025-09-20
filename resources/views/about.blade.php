<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About - ProJ3k</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/main.css') }}">
  <style>
    /* กันกรณี body ไม่มีสกรอลล์แล้วเมนูโดนครอบ/ซ้อน */
    .navbar { position: relative; z-index: 1030; }
    .dropdown-menu { z-index: 1040; }
    .about-page { position: relative; z-index: 1; }
  </style>
</head>

{{-- ถ้าอยาก “ไม่มีสกรอลล์” ให้ใส่ class="about-nosroll" (เอาออกได้ถ้าจะให้เลื่อนหน้าได้) --}}
<body class="about-nosroll">

  @include('partials.AfterNav')

  {{-- ห่อทั้งหมดด้วย about-page เพื่อคุมความสูงและจัดกลาง --}}
  <main class="about-page">
    <div class="container text-white">

      {{-- จำกัดความกว้างคอนเทนต์ให้อ่านง่ายและอยู่กึ่งกลาง --}}
      <section class="about-hero mb-5 mx-auto" style="max-width: 900px;">
        <h2 class="mb-4">About us</h2>
        <p>
          พวกเรามีความชื่นชอบในด้าน Cybersecurity และพวกเราก็ชอบเข้าร่วมการแข่งขันที่เกี่ยวข้องกับการทดสอบและป้องกันความปลอดภัยทางไซเบอร์ 
          และทุกครั้งที่พวกเราต้องค้นหา Tools หรือช่องโหว่ (CVE) เพื่อใช้ในการแข่งขันหรือทำงาน 
          เราก็มักต้องเสียเวลาค้นหาจากหลายเว็บไซต์ ที่บางเว็บไซต์นั้นเชื่อมโยงและไม่แน่ชัด 
          กว่าจะเจอข้อมูลที่นำไปใช้งานได้ ซึ่งทำให้การทำงานล่าช้า
        </p>
        <p>
          เพื่อแก้ปัญหานี้ พวกเราจึงมีแนวคิดพัฒนาเว็บไซต์ All-in-One Cybersecurity Platform 
          ที่รวบรวมเครื่องมือด้าน Cybersecurity และฐานข้อมูลช่องโหว่ไว้ด้วยกันและให้อยู่ในที่เดียว 
          เพื่อให้ผู้ใช้งานสามารถค้นหา Tools และ CVE ได้อย่างสะดวก รวดเร็ว 
        </p>
        <p>
          นอกจากนี้ เว็บไซต์ยังมี Web Board / Community สำหรับผู้ที่สนใจด้าน Cybersecurity 
          ได้เข้ามาพูดคุย แลกเปลี่ยนเทคนิค แบ่งปันความรู้ และตั้งกระทู้สอบถามกันได้ 
          ทำให้ที่นี่เป็นศูนย์กลางทั้งด้านข้อมูล เครื่องมือ และเครือข่ายนักทำงานสายความปลอดภัยไซเบอร์
        </p>
      </section>

      {{-- Team --}}
      <section class="mb-4 about-team mx-auto" style="max-width: 1100px;">
        <h2 class="mb-4 text-center">Ours Team</h2>

        <div class="row justify-content-center">
          <div class="col-10 col-sm-6 col-md-4 col-lg-3 d-flex flex-column align-items-center team-col">
            <img src="{{ asset('images/mon.jpg') }}" class="team-avatar mb-2" alt="team1">
            <h6 class="mb-0 text-center">นาย ชยพล อุดมไพบูลย์ลาภ</h6>
            <p class="mb-1 font-weight-bold text-center">2213110212</p>
            <small class="text-muted">Dev Team</small>
          </div>

          <div class="col-10 col-sm-6 col-md-4 col-lg-3 d-flex flex-column align-items-center team-col">
            <img src="{{ asset('images/Jok.jpg') }}" class="team-avatar mb-2" alt="team2">
            <h6 class="mb-0 text-center">นาย ชยานนท์ ผลาชีวะ</h6>
            <p class="mb-1 font-weight-bold text-center">2213111376</p>
            <small class="text-muted">Dev Team</small>
          </div>

          <div class="col-10 col-sm-6 col-md-4 col-lg-3 d-flex flex-column align-items-center team-col">
            <img src="{{ asset('images/front.JPG') }}" class="team-avatar mb-2" alt="team3">
            <h6 class="mb-0 text-center">นาย พลวัฒน์ โกพัฒตา</h6>
            <p class="mb-1 font-weight-bold text-center">2213111426</p>
            <small class="text-muted">Dev Team</small>
          </div>
        </div>
      </section>
    </div>

    <footer class="footer">
      <p>© 2025 ProJ3k - Cyber Security Portal</p>
    </footer>
  </main>

  {{-- จำเป็นสำหรับ dropdown/toggler ของ Bootstrap 4 --}}
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
