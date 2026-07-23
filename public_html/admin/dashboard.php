<?php
// admin/dashboard.php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$q_sejarah = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_sejarah");
$d_sejarah = mysqli_fetch_assoc($q_sejarah);

$q_tradisi = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_tradisi");
$d_tradisi = mysqli_fetch_assoc($q_tradisi);

$q_seni = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_seni_budaya");
$d_seni = mysqli_fetch_assoc($q_seni);

$q_galeri = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_galeri");
$d_galeri = mysqli_fetch_assoc($q_galeri);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Budaya Adonara</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1A362D;
            --gold: #D4AF37;
            --bg-color: #F4F7F6;
            --card-bg: #FFFFFF;
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-color); display: flex; }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px; background-color: var(--primary-dark); color: #fff;
            position: fixed; height: 100vh; z-index: 1000;
            transition: transform 0.3s ease;
        }
        .sidebar-header { padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu a { display: block; color: rgba(255,255,255,0.7); padding: 15px 20px; text-decoration: none; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(212, 175, 55, 0.2); color: var(--gold); }
        .btn-logout { color: #ff6b6b !important; margin-top: 20px; }

        /* --- MAIN CONTENT --- */
        .main-content { flex: 1; margin-left: 260px; padding: 40px; }
        
        /* Hamburger Mobile */
        .menu-toggle { display: none; font-size: 24px; cursor: pointer; margin-bottom: 20px; }

        .card-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .card { background: var(--card-bg); padding: 25px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-left: 4px solid var(--gold); }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .menu-toggle { display: block; }
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="menu-toggle" id="menu-toggle">☰ Menu</div>
    <h2>Halo, <?= $_SESSION['nama_admin']; ?>!</h2>
    <p style="margin-bottom: 30px;">Selamat datang di Dashboard Pengelola.</p>
    
    <div class="card-container">
        <div class="card"><h4>Sejarah</h4><p><?= $d_sejarah['total']; ?></p></div>
        <div class="card"><h4>Tradisi</h4><p><?= $d_tradisi['total']; ?></p></div>
        <div class="card"><h4>Seni</h4><p><?= $d_seni['total']; ?></p></div>
        <div class="card"><h4>Galeri</h4><p><?= $d_galeri['total']; ?></p></div>
    </div>
</div>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>

</body>
</html>