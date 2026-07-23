<?php
// Dapatkan nama file yang sedang diakses untuk menentukan class 'active'
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3>ADONARA</h3>
        <p>Admin Panel</p>
    </div>
    <div class="sidebar-menu">
        <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">❖ Dashboard</a>
        <a href="sejarah_kelola.php" class="<?= strpos($current_page, 'sejarah') !== false ? 'active' : '' ?>">📄 Sejarah</a>
        <a href="tradisi_kelola.php" class="<?= strpos($current_page, 'tradisi') !== false ? 'active' : '' ?>">🌿 Tradisi</a>
        <a href="seni_kelola.php" class="<?= strpos($current_page, 'seni') !== false ? 'active' : '' ?>">🎭 Seni & Budaya</a>
        <a href="galeri_kelola.php" class="<?= strpos($current_page, 'galeri') !== false ? 'active' : '' ?>">🖼️ Galeri</a>
        <a href="kontak_kelola.php" class="<?= strpos($current_page, 'kontak') !== false ? 'active' : '' ?>">📍 Kontak</a>
        <a href="katalog_approval.php" class="<?= $current_page == 'katalog_approval.php' ? 'active' : '' ?>">🛒 Approval Produk</a>
        <a href="logout.php" class="btn-logout">⎋ Logout</a>
    </div>
</div>