<?php
// index.php
require_once 'config/koneksi.php';

$query_kontak = "SELECT * FROM tb_kontak_lokasi WHERE id = 1";
$result_kontak = mysqli_query($koneksi, $query_kontak);
$data_kontak = mysqli_fetch_assoc($result_kontak);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Budaya Adonara</title>
    
    <!-- Logo Website -->
    <link rel="icon" type="image/png" href="logoweb.png">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    
    <style>
        body {
            cursor: none; /* Menyembunyikan kursor bawaan */
        }

        /* === KURSOR ANIMASI (CUSTOM CURSOR) === */
        .cursor-dot {
            width: 8px;
            height: 8px;
            background-color: var(--gold, #d4af37);
            position: fixed;
            top: 0; left: 0;
            border-radius: 50%;
            z-index: 99999;
            pointer-events: none;
            transform: translate(-50%, -50%);
        }
        
        .cursor-outline {
            width: 40px;
            height: 40px;
            border: 2px solid rgba(212, 175, 55, 0.7);
            position: fixed;
            top: 0; left: 0;
            border-radius: 50%;
            z-index: 99998;
            pointer-events: none;
            transform: translate(-50%, -50%);
            transition: width 0.2s, height 0.2s, background-color 0.2s;
        }

        /* === PERBAIKAN BUG SIDEBAR MOBILE (Z-INDEX) === */
        .top-nav {
            z-index: 1002 !important; 
        }
        .mobile-overlay {
            z-index: 1000 !important;
        }

        /* === EFEK BACKGROUND OPACITY === */
        .bg-overlay {
            position: fixed;
            top: 0; left: 0; 
            width: 100%; height: 100vh;
            background-image: url('assets/uploads/foto/bgweb.jpg'); 
            background-size: cover;
            background-position: center;
            opacity: 0.05; 
            z-index: -1;
            pointer-events: none; 
            filter: grayscale(100%); 
        }
        body.light-mode .bg-overlay {
            opacity: 0.04;
        }

        /* === FOTO BLOCK BACKGROUND (bgweb.jpg) === */
        .image-box {
            width: 100%;
            height: 100%;
            min-height: 400px;
            background-image: url('assets/uploads/foto/aboutus.jpg'); 
            background-size: cover;
            background-position: center;
            border-radius: 8px;
            position: relative;
        }

        /* Aksen bingkai pada foto */
        .image-box::before {
            content: '';
            position: absolute;
            top: -15px; left: -15px;
            right: 15px; bottom: 15px;
            border: 1px solid var(--gold, #d4af37);
            border-radius: 8px;
            z-index: -1;
        }

        /* === OPENER / INTRO ANIMASI === */
        .intro-opener {
            position: fixed;
            top: 0; left: 0; 
            width: 100%; height: 100vh;
            background: #0a0a0a;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 1.2s cubic-bezier(0.77, 0, 0.175, 1);
        }
        
        .intro-opener.slide-up {
            transform: translateY(-100%);
        }

        .intro-bg {
            position: absolute;
            top: 0; left: 0; 
            width: 100%; height: 100%;
            background-image: url('assets/uploads/foto/bgweb.jpg'); 
            background-size: cover;
            background-position: center;
            opacity: 0.4;
            animation: kenburns 7s ease-out forwards;
        }

        @keyframes kenburns {
            0% { transform: scale(1); }
            100% { transform: scale(1.15); }
        }

        .intro-content {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 100%;
            height: 100px;
        }

        .intro-text {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            color: var(--gold, #d4af37);
            letter-spacing: 5px;
            opacity: 0;
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) translateY(30px);
            width: 100%;
            transition: all 1s ease;
            text-transform: uppercase;
        }

        .intro-text.active {
            opacity: 1;
            transform: translate(-50%, -50%) translateY(0);
        }

        .intro-text.fade-out {
            opacity: 0;
            transform: translate(-50%, -50%) translateY(-30px);
        }

        /* === MENU DROPDOWN LIST === */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown > a {
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #111;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1005;
            border-radius: 5px;
            border: 1px solid var(--gold, #d4af37);
            top: 100%;
            left: 0;
            overflow: hidden;
        }

        body.light-mode .dropdown-content {
            background-color: #fff;
        }

        .dropdown-content a {
            color: #fff;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            transition: 0.3s;
            margin: 0;
        }

        body.light-mode .dropdown-content a {
            color: #000;
        }

        .dropdown-content a:hover {
            background-color: var(--gold, #d4af37);
            color: #000 !important;
        }

        /* Tampilkan dropdown saat hover di desktop */
        @media (min-width: 769px) {
            .dropdown:hover .dropdown-content {
                display: block;
            }
        }
        
        @media (max-width: 768px) {
            .intro-text { font-size: 24px; letter-spacing: 3px; }
            body { cursor: auto; } /* Matikan custom cursor di mobile */
            .cursor-dot, .cursor-outline { display: none; }
            
            /* Penyesuaian Dropdown di Mobile */
            .dropdown {
                display: block;
                width: 100%;
            }
            .dropdown-content {
                display: block; 
                position: relative;
                box-shadow: none;
                border: none;
                border-left: 2px solid var(--gold, #d4af37);
                background-color: transparent;
                margin-left: 20px;
                margin-top: 5px;
                border-radius: 0;
                min-width: 100%;
            }
            body.light-mode .dropdown-content {
                background-color: transparent;
            }
            .dropdown-content a {
                padding: 10px 15px;
                font-size: 13px;
            }

            /* Sembunyikan tombol X (close menu) di tampilan desktop */
.close-menu {
    display: none;
}

/* Tampilkan tombol X hanya di tampilan mobile (HP) */
@media (max-width: 768px) {
    .close-menu {
        display: block;
        position: absolute;
        top: 20px;
        right: 20px;
        background: transparent;
        color: var(--gold, #d4af37);
        border: none;
        font-size: 30px;
        cursor: pointer;
    }
}
        }
    </style>
</head>
<body class="dark-editorial-theme" style="overflow: hidden;"> 
    
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <div id="intro-opener" class="intro-opener">
        <div class="intro-bg"></div>
        <div class="intro-content">
            <h1 class="intro-text" id="intro-1">Selamat Datang</h1>
            <h1 class="intro-text" id="intro-2">Mari Jelajahi Adonara</h1>
        </div>
    </div>

    <div class="bg-overlay"></div>

    <aside class="side-bar">
        <div class="logo-box">
            <div class="logo-icon"></div>
            <span>ADONARA</span>
        </div>
        
        <button id="theme-toggle" class="theme-btn" title="Ubah Tema">🌓</button>
        
        <div class="vertical-text">Cultural Experience</div>
    </aside>

    <!-- Konten Utama Kanan -->
    <main class="main-content">
        
        <?php include 'navbar.php'; ?>

        <section class="hero-section animate-on-scroll">
            <h1 class="hero-title">SEJARAH & BUDAYA<br><span>ADONARA</span></h1>
            <p class="hero-subtitle">Warisan Leluhur di Flores Timur, Indonesia</p>
            
            <div class="hero-meta">
                <div class="meta-item">
                    <span class="meta-label">Location</span>
                    <span class="meta-value">Adonara, Flores Timur, Nusa Tenggara Timur</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Ethnicity</span>
                    <span class="meta-value">Lamaholot</span>
                </div>
            </div>
        </section>

        <section class="about-section animate-on-scroll">
            <div class="about-header">
                <h2>ABOUT US</h2>
            </div>
            
            <div class="about-grid">
                <div class="about-image-placeholder">
                    <div class="image-box"></div>
                </div>
                <div class="about-text">
                    <!-- Deskripsi tambahan yang diminta diletakkan di bagian atas -->
                    <p class="highlight-text">Media edukasi dan promosi seputaran warisan budaya dan jejak historis seputaran Adonara.</p>
                    <p>Masyarakat Adonara merupakan bagian dari etnis Lamaholot yang memiliki kisah historis yang panjang dan warisan budaya yang sarat akan makna.</p>
                    <p>Jelajahi sejarah, filosofi, adat istiadat dan tradisi seperti tradisi talin, hingga kesenian khas daerah seperti tari Hedung dan tenun ikat. Sebagai bagian dari perwujudan pelestarian budaya dan bentuk rasa cinta terhadap keanekaragaman budaya Nusantara.</p>
                </div>
                <div class="about-stats">
                    <div class="stat-item">
                        <h3>100+</h3>
                        <p>Tahun Sejarah Leluhur</p>
                    </div>
                    <div class="stat-item">
                        <h3>05</h3>
                        <p>Kategori Pelestarian</p>
                    </div>
                </div>
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
            
            // Script untuk memeriksa URL hash dan scroll otomatis saat halaman baru dimuat
            if (window.location.hash) {
                setTimeout(function() {
                    const id = window.location.hash.substring(1);
                    const el = document.getElementById(id);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }, 100); 
            }

            // 1. LOGIKA KURSOR ANIMASI (CUSTOM CURSOR)
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

            // 2. LOGIKA OPENER (INTRO ANIMATION)
            const text1 = document.getElementById('intro-1');
            const text2 = document.getElementById('intro-2');
            const opener = document.getElementById('intro-opener');
            
            if (!sessionStorage.getItem('introPlayed')) {
                setTimeout(() => text1.classList.add('active'), 500);
                setTimeout(() => {
                    text1.classList.remove('active');
                    text1.classList.add('fade-out');
                }, 2500);
                
                setTimeout(() => text2.classList.add('active'), 3200);
                setTimeout(() => {
                    text2.classList.remove('active');
                    text2.classList.add('fade-out');
                }, 5200);
                
                setTimeout(() => {
                    opener.classList.add('slide-up');
                    document.body.style.overflow = 'auto'; 
                    sessionStorage.setItem('introPlayed', 'true');
                    setTimeout(() => { opener.style.display = 'none'; }, 1200);
                }, 6000);
            } else {
                opener.style.display = 'none';
                document.body.style.overflow = 'auto';
            }

            // 3. LOGIKA ANIMASI SCROLL KONTEN
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

            // 4. LOGIKA TEMA GELAP/TERANG
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

            // 5. LOGIKA HAMBURGER MENU / SIDEBAR MOBILE
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