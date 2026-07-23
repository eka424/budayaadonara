<?php
// kontak.php
require_once 'config/koneksi.php';

$query_kontak = "SELECT * FROM tb_kontak_lokasi WHERE id = 1";
$result_kontak = mysqli_query($koneksi, $query_kontak);
$data_kontak = mysqli_fetch_assoc($result_kontak);

// --- LOGIKA UNTUK LINK OTOMATIS ---
// 1. Membersihkan dan merapikan format nomor WhatsApp (Ubah 0 jadi 62)
$wa_raw = !empty($data_kontak['telepon']) ? $data_kontak['telepon'] : '';
$wa_clean = preg_replace('/[^0-9]/', '', $wa_raw); // Hanya ambil angka
if (strpos($wa_clean, '0') === 0) {
    $wa_clean = '62' . substr($wa_clean, 1);
}
$wa_link = !empty($wa_clean) ? "https://wa.me/{$wa_clean}" : "#";

// 2. Format Link Email
$email_link = !empty($data_kontak['email']) ? "mailto:" . trim($data_kontak['email']) : "#";

// 3. Link Instagram
$ig_link = "https://www.instagram.com/haloadonaraheritage?igsh=a2VreWp4cHpqcWow";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak & Lokasi - Budaya Adonara</title>
    
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

        /* Efek Background Foto dengan Opacity */
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

        /* --- CONTACT CARDS & LAYOUT --- */
        .contact-wrapper {
            display: flex;
            flex-direction: column;
            gap: 50px;
            margin-bottom: 80px;
        }

        .contact-cards-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        a.contact-card {
            background: rgba(30, 30, 30, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-decoration: none;
            display: block;
            color: inherit;
        }

        body.light-mode a.contact-card {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        a.contact-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4);
            border-color: rgba(212,175,55,0.5);
            background: rgba(30, 30, 30, 0.6);
        }

        body.light-mode a.contact-card:hover {
            background: rgba(255, 255, 255, 0.9);
        }

        .card-icon {
            font-size: 30px;
            margin-bottom: 15px;
            color: var(--gold, #D4AF37);
            transition: transform 0.3s ease;
        }

        a.contact-card:hover .card-icon {
            transform: scale(1.1);
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: var(--gold, #D4AF37);
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .card-value {
            font-size: 14px;
            color: var(--text-color);
            line-height: 1.6;
        }

        body.light-mode .card-value {
            color: var(--text-main);
        }

        /* --- MAPS CONTAINER --- */
        .map-section {
            background: rgba(30, 30, 30, 0.45);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .map-container {
            width: 100%;
            height: 450px;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0,0,0,0.2);
        }
        
        .map-container iframe {
            width: 100% !important;
            height: 100% !important;
            border: none;
        }

        /* --- RESPONSIVE MOBILE --- */
        @media (max-width: 900px) {
            .contact-cards-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .contact-cards-container {
                grid-template-columns: 1fr;
            }
            .map-container {
                height: 300px;
            }
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

        <section class="about-section animate-on-scroll" style="border-top: none; padding-top: 60px; padding-bottom: 20px;">
            <div class="about-header">
                <h2>KONTAK & LOKASI</h2>
            </div>
            <p style="text-align: center; color: var(--text-muted); margin-top: -30px; margin-bottom: 50px; font-size: 14px; letter-spacing: 1px;">Hubungi Kami untuk Informasi Pelestarian Budaya</p>
        </section>

        <div class="container" style="margin-top: 0; padding: 0 15px;">
            <div class="contact-wrapper animate-on-scroll">
                
                <!-- CONTACT CARDS (Sudah diubah menjadi Link) -->
                <div class="contact-cards-container">
                    
                    <!-- Kartu WhatsApp -->
                    <a href="<?= $wa_link; ?>" target="_blank" class="contact-card" title="Chat kami via WhatsApp">
                        <div class="card-icon">💬</div>
                        <h4 class="card-title">WhatsApp</h4>
                        <div class="card-value"><?= !empty($data_kontak['telepon']) ? $data_kontak['telepon'] : '-'; ?></div>
                    </a>
                    
                    <!-- Kartu Email -->
                    <a href="<?= $email_link; ?>" class="contact-card" title="Kirim email kepada kami">
                        <div class="card-icon">✉️</div>
                        <h4 class="card-title">Email</h4>
                        <div class="card-value"><?= !empty($data_kontak['email']) ? $data_kontak['email'] : '-'; ?></div>
                    </a>
                    
                    <!-- Kartu Instagram -->
                    <a href="<?= $ig_link; ?>" target="_blank" class="contact-card" title="Kunjungi Instagram kami">
                        <div class="card-icon">📸</div>
                        <h4 class="card-title">Instagram</h4>
                        <div class="card-value">@haloadonaraheritage</div>
                    </a>
                    
                </div>

                <div class="map-section">
                    <div class="map-header">
                        <h3 style="font-family: 'Playfair Display', serif; color: var(--gold); margin-bottom: 15px;">Peta Pulau Adonara</h3>
                    </div>
                    <div class="map-container">
                        <?php 
                            $maps = $data_kontak['maps_embed'];
                            if (strpos($maps, '<iframe') !== false) {
                                echo $maps;
                            } else {
                                echo "<p style='color:var(--text-muted);'>⚠️ Peta belum dikonfigurasi.</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer animate-on-scroll">
            <div class="footer-content">
                <div class="brand-footer">Adonara.</div>
                <div class="footer-links">
                    <span><?= !empty($data_kontak['telepon']) ? $data_kontak['telepon'] : '-'; ?></span>
                    <span><?= !empty($data_kontak['email']) ? $data_kontak['email'] : '-'; ?></span>
                </div>
                <!-- Link Admin Panel -->
                <div class="copyright">
                    &copy; <?= date('Y'); ?> Pelestarian Budaya Adonara | <a href="admin/login.php" style="color: var(--text-muted); text-decoration: none;">Admin Panel</a>
                </div>
            </div>
        </footer>
    </main>

    <div class="mobile-overlay" id="mobile-overlay"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // JAVASCRIPT UNTUK MEMAKSA SMOOTH SCROLL DROPDOWN (Kalau diklik dari Kontak)
            document.querySelectorAll('.dropdown-content a').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    let href = this.getAttribute('href');
                    
                    if (href.includes('#') && href.includes('kontak.php')) {
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
            const body = document.body;

            if (themeToggleBtn) {
                themeToggleBtn.addEventListener('click', () => {
                    body.classList.toggle('light-mode');
                    localStorage.setItem('theme', body.classList.contains('light-mode') ? 'light' : 'dark');
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