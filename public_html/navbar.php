<?php
// Dapatkan nama file yang sedang diakses untuk class active
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Navigasi Top -->
<nav class="top-nav">
    <!-- Tombol Hamburger Menu untuk Mobile -->
    <div class="hamburger-menu" id="hamburger-menu">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="nav-links" id="nav-links">
        <button class="close-menu" id="close-menu">&times;</button>
        
        <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Beranda</a>
        
        <div class="dropdown">
            <a href="sejarah.php" class="<?= $current_page == 'sejarah.php' ? 'active' : '' ?>">Sejarah ▾</a>
            <div class="dropdown-content">
                <a href="sejarah.php#sejarah-adonara">Sejarah Adonara</a>
                <a href="sejarah.php#sejarah-perang-hongi-1904">Perang Hongi</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="tradisi.php" class="<?= $current_page == 'tradisi.php' ? 'active' : '' ?>">Tradisi ▾</a>
            <div class="dropdown-content">
                <a href="tradisi.php#talin">Tradisi Talin</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="seni_budaya.php" class="<?= $current_page == 'seni_budaya.php' ? 'active' : '' ?>">Seni & Budaya ▾</a>
            <div class="dropdown-content">
                <a href="seni_budaya.php#seni-tari-hedung">Seni Tari Hedung</a>
                <a href="seni_budaya.php#budaya-tenun-ikat">Budaya Tenun Ikat</a>
            </div>
        </div>

        <div class="dropdown">
            <a href="galeri.php" class="<?= $current_page == 'galeri.php' ? 'active' : '' ?>">Galeri ▾</a>
            <div class="dropdown-content">
                <a href="galeri.php#foto">Galeri Foto</a>
                <a href="galeri.php#video">Galeri Video</a>
            </div>
        </div>
        
        <!-- INI TOMBOL TAMBAHAN UNTUK KATALOG -->
        <a href="katalog.php" class="<?= $current_page == 'katalog.php' ? 'active' : '' ?>">Katalog UMKM</a>
    </div>
    
    <a href="kontak.php" class="btn-contact <?= $current_page == 'kontak.php' ? 'active' : '' ?>">Kontak</a>
</nav>