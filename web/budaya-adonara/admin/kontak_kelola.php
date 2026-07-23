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
    $telepon      = mysqli_real_escape_string($koneksi, $_POST['telepon']);
    $email        = mysqli_real_escape_string($koneksi, $_POST['email']);
    $media_sosial = mysqli_real_escape_string($koneksi, $_POST['media_sosial']);
    $maps_embed   = mysqli_real_escape_string($koneksi, $_POST['maps_embed']); // Script iframe dari Google Maps

    $query_update = "UPDATE tb_kontak_lokasi SET 
                        telepon = '$telepon', 
                        email = '$email', 
                        media_sosial = '$media_sosial', 
                        maps_embed = '$maps_embed' 
                     WHERE id = 1";
    
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data kontak dan lokasi berhasil diperbarui!'); window.location='kontak_kelola.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}

// Mengambil data saat ini
$query = "SELECT * FROM tb_kontak_lokasi WHERE id = 1";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kontak & Lokasi - Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1A362D; 
            --gold: #D4AF37;
            --gold-light: rgba(212, 175, 55, 0.15);
            --bg-color: #F4F7F6;
            --text-main: #2C3E50;
            --text-muted: #7F8C8D;
            --card-bg: #FFFFFF;
            --input-border: #E0E4E8;
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
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        /* --- FORM CARD --- */
        .form-card {
            background: var(--card-bg);
            width: 100%;
            max-width: 700px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.02);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .form-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 4px;
            background: linear-gradient(90deg, var(--gold), #F1C40F);
        }

        .form-header {
            margin-bottom: 30px;
            border-bottom: 1px solid var(--input-border);
            padding-bottom: 15px;
        }

        .form-header h2 {
            font-size: 24px;
            color: var(--primary-dark);
            font-weight: 600;
        }

        .form-header p {
            font-size: 14px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* --- FORM ELEMENTS --- */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--input-border);
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: var(--text-main);
            transition: all 0.3s ease;
            background-color: #FAFCFF;
        }

        .form-control:focus {
            border-color: var(--gold);
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
            background-color: #fff;
        }

        textarea.form-control {
            resize: vertical;
        }

        /* Info Box */
        .info-box {
            background-color: rgba(0, 123, 255, 0.05);
            border-left: 3px solid #007bff;
            padding: 12px 15px;
            border-radius: 4px;
            margin-top: 8px;
            font-size: 12px;
            color: #555;
            line-height: 1.5;
        }

        .info-box strong {
            color: #007bff;
        }

        /* --- BUTTONS --- */
        .btn-submit {
            background-color: var(--primary-dark);
            color: var(--gold);
            padding: 14px 25px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: var(--gold);
            color: #fff;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        /* --- RESPONSIVE MOBILE --- */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .form-card {
                padding: 25px 20px;
            }
            .form-header h2 {
                font-size: 20px;
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
    <div class="form-card">
        <div class="form-header">
            <h2>Manajemen Kontak & Lokasi</h2>
            <p>Perbarui informasi kontak dan peta lokasi yang ditampilkan di halaman depan.</p>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label for="telepon">Nomor Telepon</label>
                <input type="text" name="telepon" id="telepon" class="form-control" value="<?= $data['telepon']; ?>" placeholder="Contoh: +62 812 3456 7890" required>
            </div>
            
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= $data['email']; ?>" placeholder="Contoh: info@adonara.com" required>
            </div>
            
            <div class="form-group">
                <label for="media_sosial">Media Sosial</label>
                <textarea name="media_sosial" id="media_sosial" class="form-control" rows="3" placeholder="Format bebas, misal: Instagram: @budaya.adonara, Facebook: Adonara Culture"><?= $data['media_sosial']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="maps_embed">Google Maps Embed (Iframe)</label>
                <textarea name="maps_embed" id="maps_embed" class="form-control" rows="5" placeholder="Paste kode iframe disini..." required><?= $data['maps_embed']; ?></textarea>
                <div class="info-box">
                    <strong>Cara mendapatkan kode Embed:</strong> Buka Google Maps > Cari Lokasi > Klik tombol "Bagikan" (Share) > Pilih tab "Sematkan peta" (Embed a map) > Klik "Salin HTML" lalu paste di kolom atas.
                </div>
            </div>
            
            <button type="submit" name="update" class="btn-submit">Update Kontak & Lokasi</button>
        </form>
    </div>
</div>

</body>
</html>