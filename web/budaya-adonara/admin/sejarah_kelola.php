<?php
// admin/sejarah_kelola.php
session_start();
require_once '../config/koneksi.php';

// Validasi sesi
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    
    // Ambil path file audio sebelum dihapus dari database
    $query_file = "SELECT audio_path FROM tb_sejarah WHERE id = '$id_hapus'";
    $result_file = mysqli_query($koneksi, $query_file);
    $data_file = mysqli_fetch_assoc($result_file);

    $query_hapus = "DELETE FROM tb_sejarah WHERE id = '$id_hapus'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        // Hapus file fisik audio jika ada
        if (!empty($data_file['audio_path']) && file_exists("../" . $data_file['audio_path'])) {
            unlink("../" . $data_file['audio_path']);
        }
        echo "<script>alert('Data dan file terkait berhasil dihapus!'); window.location='sejarah_kelola.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.');</script>";
    }
}

// Mengambil data dari database
$query = "SELECT * FROM tb_sejarah ORDER BY id ASC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Sejarah - Admin Budaya Adonara</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-dark);
        }

        /* --- BUTTONS --- */
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-add {
            background-color: var(--primary-dark);
            color: var(--gold);
            border: 1px solid var(--gold);
            padding: 10px 20px;
            font-size: 14px;
        }

        .btn-add:hover {
            background-color: var(--gold);
            color: #fff;
            box-shadow: 0 4px 10px rgba(212, 175, 55, 0.3);
        }

        .btn-edit {
            background-color: rgba(243, 156, 18, 0.1);
            color: #d68910;
            margin-right: 5px;
        }

        .btn-edit:hover {
            background-color: #f39c12;
            color: #fff;
        }

        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #dc3545;
            color: #fff;
        }

        /* --- TABLE STYLING --- */
        .table-container {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.02);
            overflow: hidden;
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: rgba(26, 54, 45, 0.03);
            color: var(--primary-dark);
            text-transform: uppercase;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            padding: 18px 20px;
            border-bottom: 2px solid rgba(0,0,0,0.05);
            text-align: left;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(0,0,0,0.03);
            color: var(--text-main);
            font-size: 14px;
            vertical-align: middle;
        }

        tbody tr:hover {
            background-color: rgba(0,0,0,0.01);
        }

        /* --- BADGES --- */
        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .badge-umum {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        .badge-perang {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .badge-audio {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .badge-no-audio {
            background: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .main-content {
                margin-left: 0;
                padding: 20px;
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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
    <div class="page-header">
        <h2>Manajemen Data Sejarah</h2>
        <a href="sejarah_tambah.php" class="btn btn-add">＋ Tambah Data Sejarah</a>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kategori</th>
                    <th width="20%">Judul</th>
                    <th width="30%">Isi (Cuplikan)</th>
                    <th width="15%">Audio</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) { 
                    // Memotong teks isi agar tidak terlalu panjang di tabel
                    $cuplikan = substr(strip_tags($row['isi']), 0, 80) . '...';
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <?php if($row['kategori'] == 'umum'): ?>
                            <span class="badge badge-umum">SEJARAH UMUM</span>
                        <?php else: ?>
                            <span class="badge badge-perang">SEJARAH PERANG</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-weight: 500;"><?= $row['judul']; ?></td>
                    <td style="color: var(--text-muted); font-size: 13px; line-height: 1.5;"><?= $cuplikan; ?></td>
                    <td>
                        <?php if(!empty($row['audio_path'])): ?>
                            <span class="badge badge-audio">🎧 Tersedia</span>
                        <?php else: ?>
                            <span class="badge badge-no-audio">Kosong</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="sejarah_edit.php?id=<?= $row['id']; ?>" class="btn btn-edit">✎ Edit</a>
                        <a href="sejarah_kelola.php?hapus=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus data sejarah ini? File audio juga akan ikut terhapus.');">🗑 Hapus</a>
                    </td>
                </tr>
                <?php } ?>
                
                <?php if(mysqli_num_rows($result) == 0): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 30px; color: var(--text-muted);">
                        Belum ada data sejarah. Silakan tambahkan data baru.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>