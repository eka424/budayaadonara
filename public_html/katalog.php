<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/koneksi.php';

// Ambil data kontak untuk footer
$query_kontak = "SELECT * FROM tb_kontak_lokasi WHERE id = 1";
$result_kontak = mysqli_query($koneksi, $query_kontak);
$data_kontak = mysqli_fetch_assoc($result_kontak);

// Ambil data katalog yang SUDAH DI-APPROVE admin
$query = "SELECT k.*, u.nama_usaha, u.no_wa 
          FROM tb_katalog k 
          JOIN tb_umkm u ON k.id_umkm = u.id 
          WHERE k.status = 'approved' 
          ORDER BY k.id DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Kain Tenun Ikat - Budaya Adonara</title>
    
    <link rel="icon" type="image/png" href="logoweb.png">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    
    <style>
        body { cursor: none; }

        /* KURSOR ANIMASI */
        .cursor-dot {
            width: 8px; height: 8px;
            background-color: var(--gold, #d4af37);
            position: fixed;
            top: 0; left: 0; border-radius: 50%;
            z-index: 99999; pointer-events: none;
            transform: translate(-50%, -50%);
        }
        .cursor-outline {
            width: 40px; height: 40px;
            border: 2px solid rgba(212, 175, 55, 0.7);
            position: fixed;
            top: 0; left: 0; border-radius: 50%;
            z-index: 99998; pointer-events: none;
            transform: translate(-50%, -50%);
            transition: width 0.2s, height 0.2s, background-color 0.2s;
        }

        /* Z-INDEX & MENU */
        .top-nav { z-index: 1002 !important; }
        .mobile-overlay { z-index: 1000 !important; }

        /* BACKGROUND OPACITY */
        .bg-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            background-image: url('assets/uploads/foto/bgweb.jpg'); 
            background-size: cover; background-position: center;
            opacity: 0.05; z-index: -1; pointer-events: none; filter: grayscale(100%);
        }
        body.light-mode .bg-overlay { opacity: 0.04; }

        /* DROPDOWN */
        .dropdown { position: relative; display: inline-block; }
        .dropdown > a { display: inline-block; }
        .dropdown-content {
            display: none; position: absolute;
            background-color: #111; min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1005; border-radius: 5px;
            border: 1px solid var(--gold, #d4af37);
            top: 100%; left: 0; overflow: hidden;
        }
        body.light-mode .dropdown-content { background-color: #fff; }
        .dropdown-content a { color: #fff; padding: 12px 16px; text-decoration: none; display: block; font-size: 14px; transition: 0.3s; margin: 0; }
        body.light-mode .dropdown-content a { color: #000; }
        .dropdown-content a:hover { background-color: var(--gold, #d4af37); color: #000 !important; }

        @media (min-width: 769px) { .dropdown:hover .dropdown-content { display: block; } }
        
        /* GAYA KHUSUS KATALOG */
        .katalog-container {
            padding: 100px 5% 50px;
            min-height: 80vh;
        }
        .section-title {
            font-family: 'Playfair Display', serif;
            color: var(--gold, #d4af37);
            text-align: center;
            margin-bottom: 50px;
            font-size: 42px;
            letter-spacing: 2px;
        }
        .katalog-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); 
            gap: 30px; 
        }
        .card { 
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 8px; 
            overflow: hidden; 
            transition: all 0.4s ease;
            display: flex;
            flex-direction: column;
            backdrop-filter: blur(5px);
        }
        body.light-mode .card {
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .card:hover {
            transform: translateY(-10px);
            border-color: var(--gold, #d4af37);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .card img { 
            width: 100%; 
            height: 280px; 
            object-fit: cover; 
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
        }
        .card-body { 
            padding: 25px; 
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .card-body h3 {
            margin: 0 0 10px 0;
            font-size: 22px;
            color: #fff;
            font-family: 'Playfair Display', serif;
            letter-spacing: 1px;
        }
        body.light-mode .card-body h3 { color: #333; }
        
        .seller-info {
            color: #aaa;
            font-size: 13px;
            margin-bottom: 15px;
            font-style: italic;
        }
        .harga { 
            color: var(--gold, #d4af37); 
            font-weight: 600; 
            font-size: 1.4em; 
            margin: 10px 0 20px 0; 
            font-family: 'Playfair Display', serif;
        }
        .desc {
            font-size: 14px;
            color: #ccc;
            margin-bottom: 25px;
            flex-grow: 1;
            line-height: 1.6;
        }
        body.light-mode .desc { color: #555; }

        .btn-wa { 
            display: block; 
            text-align: center;
            background-color: transparent; 
            color: var(--gold, #d4af37); 
            border: 1px solid var(--gold, #d4af37);
            padding: 12px 15px; 
            text-decoration: none; 
            border-radius: 4px; 
            font-weight: 500; 
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 13px;
            transition: all 0.3s;
        }
        .btn-wa:hover {
            background-color: var(--gold, #d4af37);
            color: #000;
        }

        /* MOBILE RESPONSIVE */
        @media (max-width: 768px) {
            body { cursor: auto; }
            .cursor-dot, .cursor-outline { display: none; }
            .section-title { font-size: 32px; }
            .katalog-container { padding-top: 80px; }
            .dropdown { display: block; width: 100%; }
            .dropdown-content {
                display: block; position: relative; box-shadow: none; border: none;
                border-left: 2px solid var(--gold, #d4af37); background-color: transparent;
                margin-left: 20px; margin-top: 5px; border-radius: 0; min-width: 100%;
            }
            body.light-mode .dropdown-content { background-color: transparent; }
            .dropdown-content a { padding: 10px 15px; font-size: 13px; }
        }
    </style>
</head>
<body class="dark-editorial-theme"> 
    
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <div class="bg-overlay"></div>

    <aside class="side-bar">
        <div class="logo-box">
            <div class="logo-icon"></div>
            <span>ADONARA</span>
        </div>
        
        <button id="theme-toggle" class="theme-btn" title="Ubah Tema">🌓</button>
        
        <div class="vertical-text">Cultural Experience</div>
    </aside>

    <main class="main-content">
        <nav class="top-nav">
            <div class="hamburger-menu" id="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="nav-links" id="nav-links">
                <button class="close-menu" id="close-menu">&times;</button>
                <a href="index.php">Beranda</a>
                
                <div class="dropdown">
                    <a href="sejarah.php">Sejarah ▾</a>
                    <div class="dropdown-content">
                        <a href="sejarah.php#sejarah-adonara">Sejarah Adonara</a>
                        <a href="sejarah.php#sejarah-perang-hongi-1904">Perang Hongi</a>
                    </div>
                </div>

                <div class="dropdown">
                    <a href="tradisi.php">Tradisi ▾</a>
                    <div class="dropdown-content">
                        <a href="tradisi.php#talin">Tradisi Talin</a>
                    </div>
                </div>

                <div class="dropdown">
                    <a href="seni_budaya.php">Seni & Budaya ▾</a>
                    <div class="dropdown-content">
                        <a href="seni_budaya.php#seni-tari-hedung">Tari Hedung</a>
                        <a href="seni_budaya.php#budaya-tenun-ikat">Tenun Ikat</a>
                    </div>
                </div>

               <div class="dropdown">
                    <a href="galeri.php">Galeri ▾</a>
                    <div class="dropdown-content">
                        <a href="galeri.php#foto">Galeri Foto</a>
                        <a href="galeri.php#video">Galeri Video</a>
                    </div>
                </div>

                <a href="katalog.php" class="active">Katalog</a>
            
            <a href="kontak.php" class="btn-contact">Kontak</a>
        </nav>

        <section class="katalog-container animate-on-scroll">
            <h1 class="section-title">Katalog Kain Tenun Ikat</h1>
            
            <div class="katalog-grid">
                <?php if($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <?php 
                            $pesan_wa = urlencode("Halo " . $row['nama_usaha'] . ", saya tertarik dengan produk kain tenun: " . $row['nama_produk']);
                            $link_wa  = "https://wa.me/" . preg_replace('/[^0-9]/', '', $row['no_wa']) . "?text=" . $pesan_wa;
                        ?>
                        <div class="card">
                            <img src="assets/uploads/foto/<?= $row['foto']; ?>" alt="<?= htmlspecialchars($row['nama_produk']); ?>">
                            <div class="card-body">
                                <h3><?= htmlspecialchars($row['nama_produk']); ?></h3>
                                <div class="seller-info">Oleh: <?= htmlspecialchars($row['nama_usaha']); ?></div>
                                <div class="desc"><?= htmlspecialchars($row['deskripsi']); ?></div>
                                <div class="harga">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></div>
                                <a href="<?= $link_wa; ?>" target="_blank" class="btn-wa">Beli via WhatsApp</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; grid-column: 1 / -1; color: #777; font-size: 1.2rem;">Belum ada produk yang tersedia saat ini.</p>
                <?php endif; ?>
            </div>
        </section>

        <footer class="footer animate-on-scroll">
            <div class="footer-content">
                <div class="brand-footer">Adonara.</div>
                <div class="footer-links">
                    <span><?= !empty($data_kontak['telepon']) ? $data_kontak['telepon'] : '-'; ?></span>
                    <span><?= !empty($data_kontak['email']) ? $data_kontak['email'] : '-'; ?></span>
                </div>
                
                <div class="copyright">
                    &copy; <?= date('Y'); ?> Pelestarian Budaya Adonara | <a href="admin/login.php" style="color: var(--text-muted); text-decoration: none; transition: 0.3s;">Admin Panel</a>
                </div>
            </div>
        </footer>
    </main>

    <div class="mobile-overlay" id="mobile-overlay"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // LOGIKA KURSOR ANIMASI
            const cursorDot = document.querySelector('.cursor-dot');
            const cursorOutline = document.querySelector('.cursor-outline');

            if (cursorDot && cursorOutline && window.innerWidth > 768) {
                window.addEventListener('mousemove', function(e) {
                    const posX = e.clientX;
                    const posY = e.clientY;

                    cursorDot.style.left = `${posX}px`;
                    cursorDot.style.top = `${posY}px`;

                    cursorOutline.animate({
                        left: `${posX}px`,
                        top: `${posY}px`
                    }, { duration: 500, fill: "forwards" });
                });

                const linksAndButtons = document.querySelectorAll('a, button, .hamburger-menu');
                linksAndButtons.forEach(el => {
                    el.addEventListener('mouseenter', () => {
                        cursorOutline.style.width = '60px';
                        cursorOutline.style.height = '60px';
                        cursorOutline.style.backgroundColor = 'rgba(212, 175, 55, 0.1)';
                    });
                    el.addEventListener('mouseleave', () => {
                        cursorOutline.style.width = '40px';
                        cursorOutline.style.height = '40px';
                        cursorOutline.style.backgroundColor = 'transparent';
                    });
                });
            }

            // LOGIKA ANIMASI SCROLL
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, {
                threshold: 0.15,
                rootMargin: "0px 0px -50px 0px"
            });

            document.querySelectorAll('.animate-on-scroll').forEach((el) => observer.observe(el));

            // LOGIKA TEMA GELAP/TERANG
            const themeToggleBtn = document.getElementById('theme-toggle');
            const body = document.body;

            if (themeToggleBtn) {
                const currentTheme = localStorage.getItem('theme');
                if (currentTheme === 'light') {
                    body.classList.add('light-mode');
                }

                themeToggleBtn.addEventListener('click', function() {
                    body.classList.toggle('light-mode');
                    if (body.classList.contains('light-mode')) {
                        localStorage.setItem('theme', 'light');
                    } else {
                        localStorage.setItem('theme', 'dark');
                    }
                });
            }

            // LOGIKA HAMBURGER MENU
            const hamburgerBtn = document.getElementById('hamburger-menu');
            const closeBtn = document.getElementById('close-menu');
            const navLinks = document.getElementById('nav-links');
            const mobileOverlay = document.getElementById('mobile-overlay');

            function toggleMenu() {
                navLinks.classList.toggle('active');
                mobileOverlay.classList.toggle('active');
            }

            if (hamburgerBtn && closeBtn) {
                hamburgerBtn.addEventListener('click', toggleMenu);
                closeBtn.addEventListener('click', toggleMenu);
                mobileOverlay.addEventListener('click', toggleMenu);
            }
        });
    </script>
</body>
</html>