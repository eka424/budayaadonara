<?php
// Tampilkan error agar tidak blank putih jika ada salah ketik
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../config/koneksi.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $tipe = $_POST['tipe'];
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    // Penanganan File Upload
    if (!empty($_FILES['file_media']['name'])) {
        $nama_file = $_FILES['file_media']['name'];
        $tmp_file = $_FILES['file_media']['tmp_name'];
        $error_upload = $_FILES['file_media']['error'];
        
        if ($error_upload === 0) {
            // Menentukan folder berdasarkan tipe (foto atau video)
            $folder_relatif = ($tipe == 'foto') ? 'assets/uploads/foto/' : 'assets/uploads/video/';
            $folder_fisik = "../" . $folder_relatif;
            
            // AUTO-CREATE FOLDER: Bikin folder otomatis kalau belum ada
            if (!is_dir($folder_fisik)) {
                mkdir($folder_fisik, 0777, true);
            }

            $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
            $nama_file_baru = uniqid() . '.' . $ekstensi;
            $path_tujuan = $folder_fisik . $nama_file_baru;

            // Memindahkan file
            if (move_uploaded_file($tmp_file, $path_tujuan)) {
                // Simpan path relatif ke database
                $path_simpan = $folder_relatif . $nama_file_baru;
                $query = "INSERT INTO tb_galeri (tipe, judul, file_path, keterangan) VALUES ('$tipe', '$judul', '$path_simpan', '$keterangan')";
                
                if (mysqli_query($koneksi, $query)) {
                    echo "<script>alert('Media berhasil diunggah!'); window.location='galeri_kelola.php';</script>";
                } else {
                    echo "<script>alert('Gagal menyimpan data ke database.');</script>";
                }
            } else {
                echo "<script>alert('Gagal memindahkan file. Cek hak akses folder XAMPP.');</script>";
            }
        } else {
            echo "<script>alert('Terjadi kesalahan saat memilih file! Kode Error PHP: " . $error_upload . "');</script>";
        }
    } else {
        echo "<script>alert('Harap pilih file terlebih dahulu.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Galeri - Admin</title>
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
            max-width: 600px;
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
            min-height: 100px;
        }

        /* File Input Styling */
        input[type="file"] {
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px dashed #ced4da;
            cursor: pointer;
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
        <a href="sejarah_kelola.php">📄 Kelola Sejarah</a>
        <a href="tradisi_kelola.php">🌿 Kelola Tradisi</a>
        <a href="seni_kelola.php">🎭 Kelola Seni & Budaya</a>
        <a href="galeri_kelola.php" class="active">🖼️ Kelola Galeri</a>
        <a href="kontak_kelola.php">📍 Kontak & Lokasi</a>
        <a href="logout.php" class="btn-logout">⎋ Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="form-card">
        <div class="form-header">
            <h3>Tambah Media Galeri</h3>
            <p>Unggah foto atau video baru untuk ditampilkan pada halaman galeri pengunjung.</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label for="tipe">Tipe Media</label>
                <select name="tipe" id="tipe" class="form-control" required>
                    <option value="" disabled selected>-- Pilih Tipe Media --</option>
                    <option value="foto">📷 Foto</option>
                    <option value="video">🎥 Video</option>
                </select>
            </div>

            <div class="form-group">
                <label for="judul">Judul Media</label>
                <input type="text" name="judul" id="judul" class="form-control" placeholder="Contoh: Tarian Hedung" required>
            </div>

            <div class="form-group">
                <label for="file_media">Pilih File (Foto / Video)</label>
                <input type="file" name="file_media" id="file_media" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan Tambahan</label>
                <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Tuliskan deskripsi singkat mengenai media ini..."></textarea>
            </div>

            <div class="btn-container">
                <a href="galeri_kelola.php" class="btn btn-cancel">Batal</a>
                <button type="submit" name="simpan" class="btn btn-submit">Unggah Media</button>
            </div>
            
        </form>
    </div>
</div>

</body>
</html>