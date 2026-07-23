<?php
// admin/kontak_kelola.php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Proses Update Data
if (isset($_POST['update'])) {
    $telepon     = mysqli_real_escape_string($koneksi, $_POST['telepon']);
    $email       = mysqli_real_escape_string($koneksi, $_POST['email']);
    $media_sosial = mysqli_real_escape_string($koneksi, $_POST['media_sosial']);
    $maps_embed  = mysqli_real_escape_string($koneksi, $_POST['maps_embed']);

    $query_update = "UPDATE tb_kontak_lokasi SET 
                        telepon = '$telepon', 
                        email = '$email', 
                        media_sosial = '$media_sosial', 
                        maps_embed = '$maps_embed' 
                     WHERE id = 1";
    
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='kontak_kelola.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}

$query = "SELECT * FROM tb_kontak_lokasi WHERE id = 1";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kontak - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-dark: #1A362D; --gold: #D4AF37; --bg-color: #F4F7F6; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-color); display: flex; }

        /* --- SIDEBAR --- */
        .sidebar { width: 260px; background-color: var(--primary-dark); color: #fff; position: fixed; height: 100vh; z-index: 1000; transition: transform 0.3s ease; }
        .sidebar-header { padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu a { display: block; color: rgba(255,255,255,0.7); padding: 15px 20px; text-decoration: none; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(212, 175, 55, 0.2); color: var(--gold); }
        .btn-logout { color: #ff6b6b !important; margin-top: 20px; }

        /* --- MAIN --- */
        .main-content { flex: 1; margin-left: 260px; padding: 40px; }
        .menu-toggle { display: none; font-size: 24px; cursor: pointer; margin-bottom: 20px; }
        
        .form-card { background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); max-width: 700px; }
        .form-group { margin-bottom: 20px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-submit { width: 100%; padding: 12px; background: var(--primary-dark); color: var(--gold); border: none; border-radius: 5px; cursor: pointer; font-weight: 600; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .menu-toggle { display: block; }
        }
    </style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header"><h3>ADONARA</h3><p>Admin Panel</p></div>
    <div class="sidebar-menu">
        <a href="dashboard.php">❖ Dashboard</a>
        <a href="sejarah_kelola.php">📄 Kelola Sejarah</a>
        <a href="tradisi_kelola.php">🌿 Kelola Tradisi</a>
        <a href="seni_kelola.php">🎭 Kelola Seni & Budaya</a>
        <a href="galeri_kelola.php">🖼️ Kelola Galeri</a>
        <a href="kontak_kelola.php" class="active">📍 Kontak & Lokasi</a>
        <a href="logout.php" class="btn-logout">⎋ Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="menu-toggle" id="menu-toggle">☰ Menu</div>
    <div class="form-card">
        <h2>Manajemen Kontak</h2>
        <form action="" method="POST" style="margin-top:20px;">
            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="telepon" class="form-control" value="<?= $data['telepon']; ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?= $data['email']; ?>" required>
            </div>
            <div class="form-group">
                <label>Media Sosial</label>
                <textarea name="media_sosial" class="form-control" rows="3"><?= $data['media_sosial']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Google Maps Embed</label>
                <textarea name="maps_embed" class="form-control" rows="4"><?= $data['maps_embed']; ?></textarea>
            </div>
            <button type="submit" name="update" class="btn-submit">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>

</body>
</html>