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
                <a href="#" class="btn btn-success btn-sm">Login</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">

        <!-- News -->
        <div class="mb-4">
            <h4 class="section-title">News</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-news">
                        <img src="https://picsum.photos/400/200" class="card-img-top" alt="">
                        <div class="card-body">
                            <p class="card-text">ข่าวสารด้าน Cyber Security อัปเดตล่าสุด...</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-news">
                        <img src="https://picsum.photos/401/200" class="card-img-top" alt="">
                        <div class="card-body">
                            <p class="card-text">ประกาศ CVE ใหม่ล่าสุดจาก NVD...</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-news">
                        <img src="https://picsum.photos/402/200" class="card-img-top" alt="">
                        <div class="card-body">
                            <p class="card-text">การโจมตี Ransomware กำลังกลับมา...</p>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#" class="btn-more mt-2 d-inline-block">More…</a>
        </div>

        <!-- Popular Tools -->
        <div class="panel mb-4">
            <div class="d-flex justify-content-between">
                <h5 class="section-title">Popular Tools</h5>
                <a href="#" class="btn-more">More…</a>
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
                <div class="col-md-4 mb-3">
                    <div class="community-card">
                        <strong>I need to jack off</strong>
                        <div class="d-flex justify-content-between text-muted small mt-2">
                            <span><i class="fa-regular fa-comment"></i> 8</span>
                            <span><i class="fa-regular fa-heart"></i> 16</span>
                            <span><i class="fa-regular fa-bookmark"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="community-card">
                        <strong>why my c0de work/never</strong>
                        <div class="d-flex justify-content-between text-muted small mt-2">
                            <span><i class="fa-regular fa-comment"></i> 3</span>
                            <span><i class="fa-regular fa-heart"></i> 9</span>
                            <span><i class="fa-regular fa-bookmark"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="community-card">
                        <strong>what is CVE</strong>
                        <div class="d-flex justify-content-between text-muted small mt-2">
                            <span><i class="fa-regular fa-comment"></i> 5</span>
                            <span><i class="fa-regular fa-heart"></i> 12</span>
                            <span><i class="fa-regular fa-bookmark"></i></span>
                        </div>
                    </div>
                </div>
                <!-- เพิ่มการ์ดอื่นได้ตามรูป -->
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
