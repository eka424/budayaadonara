<?php
// admin/sejarah_edit.php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$query_tampil = "SELECT * FROM tb_sejarah WHERE id = '$id'";
$result = mysqli_query($koneksi, $query_tampil);
$data = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $kategori = $_POST['kategori'];
    $judul    = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi      = mysqli_real_escape_string($koneksi, $_POST['isi']);
    
    // Simpan path audio lama sebagai default
    $audio_path = $data['audio_path'];

    // Cek apakah admin mengunggah file audio baru
    if (!empty($_FILES['audio_file']['name'])) {
        $nama_audio = $_FILES['audio_file']['name'];
        $tmp_audio = $_FILES['audio_file']['tmp_name'];
        $error_upload = $_FILES['audio_file']['error'];
        $ekstensi = strtolower(pathinfo($nama_audio, PATHINFO_EXTENSION)); 
        
        if ($error_upload === 0) {
            if ($ekstensi == 'mp3') {
                $nama_audio_baru = uniqid() . "." . $ekstensi;
                $folder_relatif = "assets/uploads/audio/";
                $folder_fisik = "../" . $folder_relatif;
                
                if (!is_dir($folder_fisik)) {
                    mkdir($folder_fisik, 0777, true);
                }

                $path_tujuan = $folder_fisik . $nama_audio_baru;
                
                if (move_uploaded_file($tmp_audio, $path_tujuan)) {
                    // Hapus file audio lama secara fisik dari server jika ada
                    if (!empty($data['audio_path']) && file_exists("../" . $data['audio_path'])) {
                        unlink("../" . $data['audio_path']);
                    }
                    
                    // Update dengan path audio yang baru diupload
                    $audio_path = $folder_relatif . $nama_audio_baru;
                } else {
                    echo "<script>alert('Gagal memindahkan file audio baru.');</script>";
                }
            } else {
                echo "<script>alert('Format file wajib .mp3');</script>";
            }
        }
    }

    $query_update = "UPDATE tb_sejarah SET kategori='$kategori', judul='$judul', isi='$isi', audio_path='$audio_path' WHERE id='$id'";
    
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='sejarah_kelola.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sejarah - Admin</title>
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

        .form-header h3 {
            font-size: 22px;
            color: var(--primary-dark);
            font-weight: 600;
        }

        .form-header p {
            font-size: 13px;
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
        
        input[type="file"].form-control {
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            cursor: pointer;
        }

        /* Info Box */
        .info-box {
            background-color: rgba(212, 175, 55, 0.1);
            border-left: 3px solid var(--gold);
            padding: 12px 15px;
            border-radius: 4px;
            margin-top: 8px;
            font-size: 12px;
            color: #665200;
            line-height: 1.5;
        }

        /* --- BUTTONS --- */
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-submit {
            background-color: var(--primary-dark);
            color: var(--gold);
            flex: 1;
        }

        .btn-submit:hover {
            background-color: var(--gold);
            color: #fff;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .btn-cancel {
            background-color: #f1f3f5;
            color: #495057;
            text-decoration: none;
            padding: 12px 25px;
            display: inline-block;
        }

        .btn-cancel:hover {
            background-color: #e9ecef;
            color: #212529;
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
            .btn-container {
                flex-direction: column-reverse;
            }
            .btn-cancel {
                width: 100%;
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
        <a href="sejarah_kelola.php" class="active">📄 Kelola Sejarah</a>
        <a href="tradisi_kelola.php">🌿 Kelola Tradisi</a>
        <a href="seni_kelola.php">🎭 Kelola Seni & Budaya</a>
        <a href="galeri_kelola.php">🖼️ Kelola Galeri</a>
        <a href="kontak_kelola.php">📍 Kontak & Lokasi</a>
        <a href="logout.php" class="btn-logout">⎋ Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="form-card">
        <div class="form-header">
            <h3>Edit Data Sejarah</h3>
            <p>Perbarui informasi materi sejarah leluhur Pulau Adonara.</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select name="kategori" id="kategori" class="form-control" required>
                    <option value="umum" <?= ($data['kategori'] == 'umum') ? 'selected' : ''; ?>>Sejarah Umum</option>
                    <option value="perang" <?= ($data['kategori'] == 'perang') ? 'selected' : ''; ?>>Sejarah Perang</option>
                </select>
            </div>

            <div class="form-group">
                <label for="judul">Judul Sejarah</label>
                <input type="text" name="judul" id="judul" class="form-control" value="<?= $data['judul']; ?>" required>
            </div>

            <div class="form-group">
                <label for="isi">Isi Narasi Sejarah</label>
                <textarea name="isi" id="isi" class="form-control" rows="12" required><?= $data['isi']; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="audio_file">Update Audio Dubbing (.mp3)</label>
                <input type="file" name="audio_file" id="audio_file" class="form-control" accept=".mp3">
                
                <?php if(!empty($data['audio_path'])): ?>
                    <div class="info-box" style="margin-top: 10px; background-color: rgba(40, 167, 69, 0.1); border-color: #28a745; color: #155724;">
                        <strong>Audio Saat Ini:</strong> Sudah ada file audio yang tersimpan. Biarkan kosong jika tidak ingin mengubah audio tersebut.
                    </div>
                <?php else: ?>
                    <div class="info-box">
                        <strong>Audio Belum Tersedia.</strong> Unggah file .mp3 jika ingin menambahkan narasi suara untuk materi ini.
                    </div>
                <?php endif; ?>
            </div>

            <div class="btn-container">
                <a href="sejarah_kelola.php" class="btn btn-cancel">Batal</a>
                <button type="submit" name="update" class="btn btn-submit">Update Data</button>
            </div>
            
        </form>
    </div>
</div>

</body>
</html>