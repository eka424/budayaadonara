<?php
// galeri.php
require_once 'config/koneksi.php';

$query_kontak = "SELECT * FROM tb_kontak_lokasi WHERE id = 1";
$data_kontak = mysqli_fetch_assoc(mysqli_query($koneksi, $query_kontak));

// Mengambil data media dari galeri
$query_galeri = "SELECT * FROM tb_galeri ORDER BY id DESC";
$result_galeri = mysqli_query($koneksi, $query_galeri);

// Memisahkan data foto dan video ke dalam dua array berbeda
$fotos = [];
$videos = [];
while($row = mysqli_fetch_assoc($result_galeri)) {
    if($row['tipe'] == 'foto') {
        $fotos[] = $row;
    } else {
        $videos[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - Budaya Adonara</title>
    
    <!-- Favicon / Logo Tab -->
    <link rel="icon" type="image/png" href="adonara.png?v=2">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    
    <!-- Memaksa browser memuat CSS terbaru dengan ?v=2 -->
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

        /* Latar Belakang Foto Transparan */
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
        body.light-mode .bg-overlay { opacity: 0.04; }

        /* --- GALERI GRID --- */
        .gallery-section-title {
            font-family: 'Playfair Display', serif; 
            color: var(--gold); 
            font-size: 24px;
            margin-bottom: 20px; 
            border-bottom: 1px solid rgba(212,175,55,0.3); 
            padding-bottom: 10px;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }

        .gallery-item {
            background-color: var(--bg-panel);
            border-radius: 8px;
            border: 1px solid rgba(212, 175, 55, 0.15);
            overflow: hidden;
            cursor: pointer;
            transition: transform 0.3s ease, border-color 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            border-color: var(--gold);
        }

        .gallery-media {
            width: 100%;
            aspect-ratio: 4/3; 
            overflow: hidden;
            position: relative;
            background: #111;
        }

        .gallery-media img, .gallery-media video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .gallery-item:hover .gallery-media img {
            transform: scale(1.05);
        }

        /* Ikon penanda video */
        .video-indicator {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 0, 0, 0.7);
            color: var(--gold);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: 1px solid var(--gold);
            backdrop-filter: blur(4px);
        }

        .gallery-info {
            padding: 25px 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .gallery-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: var(--gold);
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .gallery-desc {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* --- LIGHTBOX (PRATINJAU) --- */
        .lightbox-modal {
            display: none;
            position: fixed;
            z-index: 99999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(10, 10, 10, 0.95);
            backdrop-filter: blur(10px);
            opacity: 0;
            transition: opacity 0.3s ease;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .lightbox-modal.show {
            display: flex;
            opacity: 1;
        }

        .lightbox-close {
            position: absolute;
            top: 30px;
            right: 40px;
            color: var(--gold);
            font-size: 40px;
            font-weight: 300;
            cursor: pointer;
            transition: 0.3s;
            line-height: 1;
        }

        .lightbox-close:hover {
            color: #fff;
            transform: scale(1.1);
        }

        .lightbox-content {
            max-width: 85%;
            max-height: 75vh;
            border-radius: 4px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            border: 1px solid rgba(212, 175, 55, 0.2);
            object-fit: contain; 
        }

        .lightbox-caption-container {
            margin-top: 25px;
            text-align: center;
            max-width: 800px;
            padding: 0 20px;
        }

        .lightbox-title {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            font-size: 24px;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .lightbox-desc {
            color: #ccc;
            font-size: 13px;
            line-height: 1.8;
        }
    </style>
</head>
<body class="dark-editorial-theme">

    <div class="bg-overlay"></div>

    <!-- Sidebar Kiri -->
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

        <!-- Header Galeri -->
        <section class="about-section animate-on-scroll" style="border-top: none; padding-top: 60px; padding-bottom: 20px;">
            <div class="about-header">
                <h2>GALERI</h2>
            </div>
            <p style="text-align: center; color: var(--text-muted); margin-top: -30px; margin-bottom: 30px; font-size: 14px; letter-spacing: 1px;">Dokumentasi Visual Warisan Leluhur</p>
        </section>

        <!-- Galeri Wrapper -->
        <div class="container" style="margin-top: 0; padding: 0 15px; margin-bottom: 80px;">
            
            <!-- SECTION GALERI FOTO -->
            <div id="foto" style="scroll-margin-top: 130px;" class="animate-on-scroll">
                <h3 class="gallery-section-title">Galeri Foto</h3>
                <?php if(empty($fotos)): ?>
                    <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 40px;">Belum ada foto yang ditambahkan.</p>
                <?php else: ?>
                    <div class="gallery-grid" style="margin-bottom: 60px;">
                        <?php foreach($fotos as $row): ?>
                            <div class="gallery-item" 
                                 data-type="foto" 
                                 data-src="<?= $row['file_path']; ?>" 
                                 data-title="<?= htmlspecialchars($row['judul']); ?>" 
                                 data-desc="<?= htmlspecialchars($row['keterangan']); ?>">
                                
                                <div class="gallery-media">
                                    <img src="<?= $row['file_path']; ?>" alt="<?= htmlspecialchars($row['judul']); ?>">
                                </div>
                                
                                <div class="gallery-info">
                                    <h4 class="gallery-title"><?= $row['judul']; ?></h4>
                                    <p class="gallery-desc"><?= nl2br($row['keterangan']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- SECTION GALERI VIDEO -->
            <div id="video" style="scroll-margin-top: 130px;" class="animate-on-scroll">
                <h3 class="gallery-section-title">Galeri Video</h3>
                <?php if(empty($videos)): ?>
                    <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 40px;">Belum ada video yang ditambahkan.</p>
                <?php else: ?>
                    <div class="gallery-grid">
                        <?php foreach($videos as $row): ?>
                            <div class="gallery-item" 
                                 data-type="video" 
                                 data-src="<?= $row['file_path']; ?>" 
                                 data-title="<?= htmlspecialchars($row['judul']); ?>" 
                                 data-desc="<?= htmlspecialchars($row['keterangan']); ?>">
                                
                                <div class="gallery-media">
                                    <div class="video-indicator">Video</div>
                                    <video muted playsinline>
                                        <source src="<?= $row['file_path']; ?>#t=0.1" type="video/mp4">
                                    </video>
                                </div>
                                
                                <div class="gallery-info">
                                    <h4 class="gallery-title"><?= $row['judul']; ?></h4>
                                    <p class="gallery-desc"><?= nl2br($row['keterangan']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Footer -->
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

    <!-- Struktur HTML Lightbox Modal -->
    <div id="lightbox" class="lightbox-modal">
        <span class="lightbox-close" id="lightbox-close">&times;</span>
        
        <!-- Wadah Media -->
        <img id="lightbox-img" class="lightbox-content" src="" alt="" style="display: none;">
        <video id="lightbox-video" class="lightbox-content" controls style="display: none;"></video>
        
        <!-- Wadah Keterangan -->
        <div class="lightbox-caption-container">
            <h3 id="lightbox-title" class="lightbox-title"></h3>
            <p id="lightbox-desc" class="lightbox-desc"></p>
        </div>
    </div>

    <!-- Script Global -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // 1. ANIMASI SCROLL
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.15 });
            document.querySelectorAll('.animate-on-scroll').forEach((el) => observer.observe(el));

            // 2. FITUR GELAP/TERANG
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

            // 3. LOGIKA LIGHTBOX (PRATINJAU)
            const galleryItems = document.querySelectorAll('.gallery-item');
            const lightbox = document.getElementById('lightbox');
            const lbClose = document.getElementById('lightbox-close');
            const lbImg = document.getElementById('lightbox-img');
            const lbVideo = document.getElementById('lightbox-video');
            const lbTitle = document.getElementById('lightbox-title');
            const lbDesc = document.getElementById('lightbox-desc');

            galleryItems.forEach(item => {
                item.addEventListener('click', function() {
                    const type = this.getAttribute('data-type');
                    const src = this.getAttribute('data-src');
                    const title = this.getAttribute('data-title');
                    const desc = this.getAttribute('data-desc');

                    lbTitle.textContent = title;
                    lbDesc.innerHTML = desc.replace(/\n/g, "<br>");

                    lbImg.style.display = 'none';
                    lbVideo.style.display = 'none';
                    lbVideo.pause();
                    lbVideo.removeAttribute('src');

                    if (type === 'foto') {
                        lbImg.src = src;
                        lbImg.style.display = 'block';
                    } else {
                        lbVideo.src = src;
                        lbVideo.style.display = 'block';
                    }

                    lightbox.classList.add('show');
                    document.body.style.overflow = 'hidden'; 
                });
            });

            lbClose.addEventListener('click', closeLightbox);

            lightbox.addEventListener('click', function(e) {
                if (e.target === lightbox || e.target.classList.contains('lightbox-caption-container')) {
                    closeLightbox();
                }
            });

            function closeLightbox() {
                lightbox.classList.remove('show');
                lbVideo.pause();
                lbVideo.removeAttribute('src');
                document.body.style.overflow = 'auto'; 
            }
            
            // 4. JAVASCRIPT UNTUK MEMAKSA SMOOTH SCROLL DROPDOWN
            document.querySelectorAll('.dropdown-content a').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    let href = this.getAttribute('href');
                    
                    if (href.includes('#') && href.includes('galeri.php')) {
                        let targetId = href.split('#')[1];
                        let targetElement = document.getElementById(targetId);
                        
                        if (targetElement) {
                            e.preventDefault(); 
                            targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }
                });
            });

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