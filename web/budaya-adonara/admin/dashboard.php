<?php
// admin/dashboard.php
session_start();
require_once '../config/koneksi.php';

// Validasi sesi: jika tidak ada session admin, kembalikan ke halaman login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Mengambil jumlah data untuk ditampilkan di ringkasan dashboard
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1A362D; /* Hijau gelap elegan */
            --gold: #D4AF37;
            --gold-light: rgba(212, 175, 55, 0.15);
            --bg-color: #F4F7F6;
            --text-main: #2C3E50;
            --text-muted: #7F8C8D;
            --card-bg: #FFFFFF;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px;
            background-color: var(--primary-dark);
            color: #fff;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 20px;
        }

        .sidebar-header h3 {
            font-weight: 600;
            font-size: 20px;
            letter-spacing: 1px;
            color: var(--gold);
        }

        .sidebar-header p {
            font-size: 12px;
            color: rgba(255,255,255,0.6);
            margin-top: 5px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 0 15px;
        }

        .sidebar-menu a {
            color: rgba(255,255,255,0.7);
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: var(--gold-light);
            color: var(--gold);
        }

        .btn-logout {
            margin-top: 40px;
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #ff6b6b !important;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .btn-logout:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 40px 50px;
        }

        .page-header {
            margin-bottom: 40px;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 15px;
        }

        /* --- CARDS GRID --- */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }

        .card {
            background-color: var(--card-bg);
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.02);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }

        /* Garis aksen emas di atas card */
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 4px;
            background: linear-gradient(90deg, var(--gold), #F1C40F);
        }

        .card h4 {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .card p {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-dark);
            line-height: 1;
        }

        /* Ornamen icon background abstrak */
        .card::after {
            content: '✦';
            position: absolute;
            bottom: -15px;
            right: -5px;
            font-size: 80px;
            color: rgba(212, 175, 55, 0.05);
            pointer-events: none;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header">
        <h3>ADONARA</h3>
        <p>Admin Panel</p>
    </div>
    
    <div class="sidebar-menu">
        <a href="dashboard.php" class="active">❖ Dashboard</a>
        <a href="sejarah_kelola.php">📄 Kelola Sejarah</a>
        <a href="tradisi_kelola.php">🌿 Kelola Tradisi</a>
        <a href="seni_kelola.php">🎭 Kelola Seni & Budaya</a>
        <a href="galeri_kelola.php">🖼️ Kelola Galeri</a>
        <a href="kontak_kelola.php">📍 Kontak & Lokasi</a>
        <a href="logout.php" class="btn-logout">⎋ Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="page-header">
        <h2>Selamat Datang, <?= $_SESSION['nama_admin']; ?> 👋</h2>
        <p>Ini adalah pusat kendali untuk mengelola konten website Kebudayaan Adonara.</p>
    </div>
    
    <div class="card-container">
        <div class="card">
            <h4>Total Sejarah</h4>
            <p><?= $d_sejarah['total']; ?></p>
        </div>
        <div class="card">
            <h4>Total Tradisi</h4>
            <p><?= $d_tradisi['total']; ?></p>
        </div>
        <div class="card">
            <h4>Seni & Budaya</h4>
            <p><?= $d_seni['total']; ?></p>
        </div>
        <div class="card">
            <h4>Multimedia</h4>
            <p><?= $d_galeri['total']; ?></p>
        </div>
    </div>
</div>

</body>
</html>