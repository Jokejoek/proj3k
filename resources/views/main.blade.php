<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('Images/Proj3k.png') }}" alt="Logo" height="30" class="mr-2">
                <span>ProJ3K</span>
            </a>
            <div>
                <a href="#">Home</a>
                <a href="#">Tools</a>
                <a href="#">Vulnerability</a>
                <a href="#">Community</a>
                <a href="#">About</a>
                <!--<a href="#" class="btn-auth">Login</a>-->
                <a href="#" class="fontLog"fontLog>Login</a>
                <a href="#" class="btn-auth">Sign up</a>
            </div>
        </div>
    </nav>

    
    <div class="container py-4">

        <!-- News -->
        <div class="mb-4">
        <h4 class="section-title text-white">News</h4>
        <div class="row">
            {{-- News 1 --}}
            <div class="col-md-4 mb-3">
            <article class="news-card">
                <div class="news-thumb">
                <img src="https://picsum.photos/seed/ios/640/360" alt="apple zero-day">
                </div>
                <div class="news-body">
                <div class="news-meta">
                    <span><i class="fa-regular fa-clock"></i> สิงหาคม 22, 2025</span>
                    <span class="news-cat">IT</span>
                </div>
                <h6 class="news-title">Apple ออกแพตช์แก้ไขช่องโหว่ Zero‑Day CVE‑2025‑43300 ใน iOS, iPadOS และ macOS</h6>
                <p class="news-excerpt">Apple ได้ปล่อยอัปเดตความปลอดภัยเพื่ออุดช่องโหว่สำคัญที่อาจถูกโจมตีแบบ active อยู่แล้ว […]</p>
                <a href="#" class="news-more">อ่านต่อ</a>
                </div>
            </article>
            </div>

            {{-- News 2 --}}
            <div class="col-md-4 mb-3">
            <article class="news-card">
                <div class="news-thumb">
                <img src="https://picsum.photos/seed/dom/640/360" alt="dom clickjacking">
                </div>
                <div class="news-body">
                <div class="news-meta">
                    <span><i class="fa-regular fa-clock"></i> สิงหาคม 22, 2025</span>
                    <span class="news-cat">Security</span>
                </div>
                <h6 class="news-title">ผู้เชี่ยวชาญพบช่องโหว่ “DOM‑Based Extension Clickjacking” เสี่ยงขโมยรหัสผ่านจากตัวจัดการรหัสยอดนิยม</h6>
                <p class="news-excerpt">นักวิจัยเผยวิธีโจมตีรูปแบบใหม่ที่อาศัย DOM manipulation เพื่อหลอกผู้ใช้ให้คลิกยืนยันโดยไม่ตั้งใจ […]</p>
                <a href="#" class="news-more">อ่านต่อ</a>
                </div>
            </article>
            </div>

            {{-- News 3 --}}
            <div class="col-md-4 mb-3">
            <article class="news-card">
                <div class="news-thumb">
                <img src="https://picsum.photos/seed/cisa/640/360" alt="cisa kev">
                </div>
                <div class="news-body">
                <div class="news-meta">
                    <span><i class="fa-regular fa-clock"></i> สิงหาคม 21, 2025</span>
                    <span class="news-cat">Advisory</span>
                </div>
                <h6 class="news-title">CISA เพิ่มช่องโหว่ Trend Micro Apex One ลงใน Known Exploited Vulnerabilities Catalog</h6>
                <p class="news-excerpt">หน่วยงานภาครัฐสหรัฐแนะนำให้องค์กรเร่งแพตช์เพื่อลดความเสี่ยงจากการถูกโจมตีที่พบการเอ็กซ์พลอยต์จริงแล้ว […]</p>
                <a href="#" class="news-more">อ่านต่อ</a>
                </div>
            </article>
            </div>
        </div>
        <!-- More but -->
            <div class="text-center mt-3">
                <a href="#" class="btn-more">More…</a>
            </div>
        </div>

        <!-- Popular Tools -->
        <div class="panel mb-4">
            <div class="d-flex justify-content-between">
                <h5 class="section-title">Popular Tools</h5>
            </div>
            <canvas id="toolsChart" height="120"></canvas>
        </div>

        <!-- Recent CVE -->
        <div class="mb-4">
            <h5 class="section-title">Recent CVE</h5>
            <div class="table-responsive">
                <table class="table table-darkish text-white">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>CVE-2025-99990</td><td>Dolder is hacker</td></tr>
                        <tr><td>CVE-2025-99989</td><td>...</td></tr>
                        <tr><td>CVE-2025-99988</td><td>...</td></tr>
                        <tr><td>CVE-2025-99987</td><td>...</td></tr>
                        <tr><td>CVE-2025-99986</td><td>...</td></tr>
                        <tr><td>CVE-2025-99985</td><td>...</td></tr>
                        <tr><td>CVE-2025-99984</td><td>...</td></tr>
                        <tr><td>CVE-2025-99983</td><td>...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Community Post -->
        <div>
        <h5 class="section-title">Community Post</h5>
        <div class="row">
            {{-- Card 1 --}}
            <div class="col-md-4 mb-3">
            <div class="community-card">
                <div class="community-title">I need to jerk off</div>
                <div class="author-row">
                <img class="author-avatar" src="{{ asset('images/users/jokec.jpg') }}"
                    onerror="this.src='https://i.pravatar.cc/64?u=jokec'">
                <div>
                    <div class="author-name">Jokec</div>
                    <div class="author-meta">1d</div>
                </div>
                <div class="post-actions">
                    <i class="fa-regular fa-comment"></i><span>8</span>
                </div>
                </div>
            </div>
            </div>

            {{-- Card 2 --}}
            <div class="col-md-4 mb-3">
            <div class="community-card">
                <div class="community-title">why i run code and it error</div>
                <div class="author-row">
                <img class="author-avatar" src="{{ asset('images/users/dabbydoo.jpg') }}"
                    onerror="this.src='https://i.pravatar.cc/64?u=dabbydoo'">
                <div>
                    <div class="author-name">DabbyDoo</div>
                    <div class="author-meta">21h</div>
                </div>
                <div class="post-actions">
                    <i class="fa-regular fa-comment"></i><span>5</span>
                </div>
                </div>
            </div>
            </div>

            {{-- Card 3 --}}
            <div class="col-md-4 mb-3">
            <div class="community-card">
                <div class="community-title">what is CVE</div>
                <div class="author-row">
                <img class="author-avatar" src="{{ asset('images/users/pakin.jpg') }}"
                    onerror="this.src='https://i.pravatar.cc/64?u=pakin'">
                <div>
                    <div class="author-name">Pakin</div>
                    <div class="author-meta">60d</div>
                </div>
                <div class="post-actions">
                    <i class="fa-regular fa-comment"></i><span>9</span>
                </div>
                </div>
            </div>
            </div>

            {{-- Card 4 --}}
            <div class="col-md-4 mb-3">
            <div class="community-card">
                <div class="community-title">why im so smart</div>
                <div class="author-row">
                <img class="author-avatar" src="{{ asset('images/users/domon.jpg') }}"
                    onerror="this.src='https://i.pravatar.cc/64?u=domon'">
                <div>
                    <div class="author-name">Domon</div>
                    <div class="author-meta">4d</div>
                </div>
                <div class="post-actions">
                    <i class="fa-regular fa-comment"></i><span>4</span>
                </div>
                </div>
            </div>
            </div>

            {{-- Card 5 --}}
            <div class="col-md-4 mb-3">
            <div class="community-card">
                <div class="community-title">Jokec pen gay</div>
                <div class="author-row">
                <img class="author-avatar" src="{{ asset('images/users/domon.jpg') }}"
                    onerror="this.src='https://i.pravatar.cc/64?u=domon2'">
                <div>
                    <div class="author-name">doMon</div>
                    <div class="author-meta">5h</div>
                </div>
                <div class="post-actions">
                    <i class="fa-regular fa-comment"></i><span>3</span>
                </div>
                </div>
            </div>
            </div>

            {{-- Card 6 --}}
            <div class="col-md-4 mb-3">
            <div class="community-card">
                <div class="community-title">i have 100,000 salay</div>
                <div class="author-row">
                <img class="author-avatar" src="{{ asset('images/users/domon.jpg') }}"
                    onerror="this.src='https://i.pravatar.cc/64?u=domon3'">
                <div>
                    <div class="author-name">dOmOn</div>
                    <div class="author-meta">60d</div>
                </div>
                <div class="post-actions">
                    <i class="fa-regular fa-comment"></i><span>1</span>
                </div>
                </div>
            </div>
            </div>

        </div>
        </div>


    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© 2025 Cyber Security Portal</p>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('toolsChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['BurpSuite','Nmap','Metasploit','Hydra','ffuf','Gobuster','dirb','sqlmap','nikto'],
                datasets: [{
                    label: 'Usage',
                    data: [90,85,74,61,53,41,33,28,24],
                    backgroundColor: '#7CFC00'
                }]
            },
            options: {
                plugins:{ legend:{display:false} },
                scales:{ x:{ticks:{color:'#fff'}}, y:{ticks:{color:'#fff'}} }
            }
        });
    </script>

</body>
</html>
