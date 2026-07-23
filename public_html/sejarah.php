<?php
// sejarah.php
require_once 'config/koneksi.php';

$query_kontak = "SELECT * FROM tb_kontak_lokasi WHERE id = 1";
$data_kontak = mysqli_fetch_assoc(mysqli_query($koneksi, $query_kontak));

$query_sejarah = "SELECT * FROM tb_sejarah ORDER BY id ASC";
$result_sejarah = mysqli_query($koneksi, $query_sejarah);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sejarah - Budaya Adonara</title>
    
    <link rel="icon" type="image/png" href="adonara.png?v=2">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    
    <style>
        /* === PERBAIKAN BUG SIDEBAR MOBILE (Z-INDEX) === */
        .top-nav {
            z-index: 1002 !important; 
        }
        .mobile-overlay {
            z-index: 1000 !important;
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
        @media (min-width: 769px) {
            .dropdown:hover .dropdown-content {
                display: block;
            }
        }
        @media (max-width: 768px) {
            .dropdown { display: block; width: 100%; }
            .dropdown-content {
                display: block; position: relative; box-shadow: none; border: none;
                border-left: 2px solid var(--gold, #d4af37); background-color: transparent;
                margin-left: 20px; margin-top: 5px; border-radius: 0; min-width: 100%;
            }
            body.light-mode .dropdown-content { background-color: transparent; }
            .dropdown-content a { padding: 10px 15px; font-size: 13px; }
        }

        /* Efek Background Dinding */
        .bg-overlay {
            position: fixed;
            top: 0; left: 0; 
            width: 100%; height: 100vh;
            background-image: url('assets/uploads/foto/bg-hero.jpg');
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

        /* --- MUSEUM GALLERY LAYOUT --- */
        .gallery-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            margin-bottom: 80px;
            scroll-margin-top: 120px; 
        }

        .gallery-row:nth-child(even) {
            flex-direction: row-reverse;
        }

        .artwork-display {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .canvas-frame {
            width: 100%;
            height: auto;
            min-height: 300px;
            max-height: 450px;
            object-fit: cover;
            background: #fff;
            padding: 12px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.1);
            border-radius: 2px;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .canvas-frame:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0,0,0,0.6);
        }

        .artwork-display::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 5%;
            width: 90%;
            height: 20px;
            background: radial-gradient(ellipse at center, rgba(0,0,0,0.4) 0%, transparent 70%);
            z-index: -1;
        }

        .museum-plaque {
            flex: 1;
            min-width: 300px;
            background: rgba(25, 25, 25, 0.7);
            backdrop-filter: blur(12px);
            border-top: 1px solid rgba(255,255,255,0.05);
            border-right: 1px solid rgba(255,255,255,0.05);
            border-left: 4px solid var(--gold, #D4AF37);
            padding: 40px;
            border-radius: 4px;
            box-shadow: 10px 10px 30px rgba(0,0,0,0.3);
            position: relative;
        }

        body.light-mode .museum-plaque {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-top: 1px solid rgba(0,0,0,0.05);
            border-right: 1px solid rgba(0,0,0,0.05);
        }

        .plaque-category {
            display: inline-block;
            color: var(--gold, #D4AF37);
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(212,175,55,0.3);
            padding-bottom: 5px;
        }

        .museum-plaque h3 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin-bottom: 20px;
            color: var(--text-color);
        }

        .museum-plaque p {
            font-size: 14px;
            line-height: 1.8;
            color: var(--text-muted);
            text-align: justify;
        }

        .audio-guide {
            margin-top: 25px;
            padding: 15px;
            background: rgba(0,0,0,0.2);
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        body.light-mode .audio-guide {
            background: rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* --- RESPONSIVE MOBILE --- */
        @media (max-width: 768px) {
            .gallery-row, .gallery-row:nth-child(even) { flex-direction: column; gap: 25px; margin-bottom: 60px; }
            .artwork-display { width: 100%; }
            .museum-plaque { width: 100%; padding: 25px 20px; box-sizing: border-box; }
        }
    </style>
</head>
<body class="dark-editorial-theme">

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

        
        <section class="about-section animate-on-scroll" style="border-top: none; padding-top: 60px; padding-bottom: 40px;">
            <div class="about-header">
                <h2>SEJARAH</h2>
            </div>
            <p style="text-align: center; color: var(--text-muted); margin-top: -30px; margin-bottom: 50px; font-size: 14px; letter-spacing: 1px;">Jejak Kisah Leluhur Pulau Adonara</p>
        </section>

        <div class="container" style="margin-top: 0; padding: 0 15px;">
            <?php while($row = mysqli_fetch_assoc($result_sejarah)): ?>
                <?php 
                $anchor_id = preg_replace('/[^a-z0-9]+/i', '-', strtolower(trim($row['judul'])));
                $anchor_id = trim($anchor_id, '-'); 
                ?>
                
                <div class="gallery-row animate-on-scroll" id="<?= $anchor_id; ?>">
                    <div class="artwork-display">
                        <?php $foto = !empty($row['foto_path']) ? $row['foto_path'] : 'assets/uploads/foto/default-sejarah.jpg'; ?>
                        <img src="<?= $foto; ?>" alt="<?= $row['judul']; ?>" class="canvas-frame">
                    </div>
                    <div class="museum-plaque">
                        <span class="plaque-category">Sejarah Adonara</span>
                        <h3><?= $row['judul']; ?></h3>
                        <p><?= nl2br($row['isi']); ?></p>
                        <?php if(!empty($row['audio_path'])): ?>
                            <div class="audio-guide">
                                <strong>🎧 Narasi Audio Sejarah</strong>
                                <audio controls style="width: 100%; outline: none; height: 35px; border-radius: 4px; margin-top: 10px;">
                                    <source src="<?= $row['audio_path']; ?>" type="audio/mpeg">
                                </audio>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <footer class="footer animate-on-scroll">
            <div class="footer-content">
                <div class="brand-footer">Adonara.</div>
                <div class="footer-links">
                    <span><?= !empty($data_kontak['telepon']) ? $data_kontak['telepon'] : '-'; ?></span>
                    <span><?= !empty($data_kontak['email']) ? $data_kontak['email'] : '-'; ?></span>
                </div>
                <div class="copyright">
                    &copy; <?= date('Y'); ?> Pelestarian Budaya Adonara | <a href="admin/login.php" style="color: var(--text-muted); text-decoration: none;">Admin Panel</a>
                </div>
            </div>
        </footer>
    </main>

    <div class="mobile-overlay" id="mobile-overlay"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // JAVASCRIPT UNTUK MEMAKSA SMOOTH SCROLL DROPDOWN
            document.querySelectorAll('.dropdown-content a').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    let href = this.getAttribute('href');
                    
                    if (href.includes('#') && href.includes('sejarah.php')) {
                        let targetId = href.split('#')[1];
                        let targetElement = document.getElementById(targetId);
                        
                        if (targetElement) {
                            e.preventDefault(); 
                            targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }
                });
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('is-visible');
                });
            }, { threshold: 0.15 });
            document.querySelectorAll('.animate-on-scroll').forEach((el) => observer.observe(el));

            const themeToggleBtn = document.getElementById('theme-toggle');
            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    document.body.classList.toggle('light-mode');
                    localStorage.setItem('theme', document.body.classList.contains('light-mode') ? 'light' : 'dark');
                });
            }

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