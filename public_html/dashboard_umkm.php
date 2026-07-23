<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/koneksi.php';

// Cek apakah sudah login
if (!isset($_SESSION['umkm_id'])) {
    header("Location: login_umkm.php");
    exit;
}

$id_umkm = $_SESSION['umkm_id'];
$pesan = '';

// Logika Upload Produk
if (isset($_POST['upload'])) {
    $nama_produk = mysqli_real_escape_string($koneksi, trim($_POST['nama_produk']));
    $deskripsi   = mysqli_real_escape_string($koneksi, trim($_POST['deskripsi']));
    $harga       = (int)$_POST['harga'];
    
    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];
    $ext  = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
    
    $nama_foto_baru = time() . '_' . rand(100,999) . '.' . $ext;
    $path = "assets/img/katalog/" . $nama_foto_baru;

    if (move_uploaded_file($tmp, $path)) {
        $query = "INSERT INTO tb_katalog (id_umkm, nama_produk, deskripsi, harga, foto, status) 
                  VALUES ('$id_umkm', '$nama_produk', '$deskripsi', '$harga', '$nama_foto_baru', 'pending')";
        if (mysqli_query($koneksi, $query)) {
            $pesan = "<div class='alert success'>Berhasil upload! Menunggu persetujuan admin.</div>";
        } else {
            $pesan = "<div class='alert error'>Gagal simpan ke database: " . mysqli_error($koneksi) . "</div>";
        }
    } else {
        $pesan = "<div class='alert error'>Gagal upload file gambar. Pastikan folder assets/img/katalog/ sudah ada dan writable.</div>";
    }
}

// Ambil data produk milik UMKM ini
$query_produk = "SELECT * FROM tb_katalog WHERE id_umkm = '$id_umkm' ORDER BY id DESC";
$result_produk = mysqli_query($koneksi, $query_produk);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard UMKM - Budaya Adonara</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #f4f7f6; 
            padding: 20px; 
            margin: 0;
        }
        .container { 
            max-width: 900px; 
            margin: 20px auto; 
            background: #fff; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 2px solid #f0f0f0; 
            padding-bottom: 15px; 
            margin-bottom: 25px; 
        }
        .header h2 { margin: 0; color: #333; font-size: 22px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 500; margin-bottom: 8px; color: #555; font-size: 14px;}
        .form-group input, .form-group textarea { 
            width: 100%; 
            padding: 10px 15px; 
            border: 1px solid #ddd; 
            border-radius: 6px; 
            box-sizing: border-box; 
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #d4af37;
        }
        .btn { 
            padding: 10px 20px; 
            background: #d4af37; 
            color: #fff; 
            border: none; 
            cursor: pointer; 
            border-radius: 6px; 
            font-weight: 600; 
            font-family: 'Poppins', sans-serif;
        }
        .btn:hover { background: #c19b2e; }
        .btn-logout { 
            background: #dc3545; 
            text-decoration: none; 
            padding: 8px 15px; 
            color: white; 
            border-radius: 6px; 
            font-size: 14px;
            font-weight: 500;
        }
        .btn-logout:hover { background: #c82333; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border-bottom: 1px solid #ddd; padding: 12px 15px; text-align: left; font-size: 14px; }
        th { background-color: #f9f9f9; color: #333; font-weight: 600; }
        tr:hover { background-color: #fcfcfc; }
        
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert.error { background-color: #ffeaea; color: #d93025; border: 1px solid #f5c6c6; }
        .alert.success { background-color: #e6f4ea; color: #137333; border: 1px solid #ceead6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Halo, <?= htmlspecialchars($_SESSION['umkm_nama']); ?></h2>
            <a href="logout_umkm.php" class="btn-logout">Logout</a>
        </div>

        <h3 style="margin-top: 0;">Upload Produk Tenun Ikat</h3>
        <?= $pesan; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" placeholder="Contoh: Sarung Tenun Motif X" required>
            </div>
            <div class="form-group">
                <label>Harga (Hanya Angka)</label>
                <input type="number" name="harga" placeholder="Contoh: 500000" required>
            </div>
            <div class="form-group">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" placeholder="Jelaskan ukuran, bahan, dan motif..." required></textarea>
            </div>
            <div class="form-group">
                <label>Foto Produk</label>
                <input type="file" name="foto" accept="image/*" required>
            </div>
            <button type="submit" name="upload" class="btn">Upload Produk</button>
        </form>

        <hr style="margin: 40px 0; border: none; border-top: 1px solid #eee;">

        <h3>Daftar Produk Saya</h3>
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result_produk) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result_produk)): ?>
                    <tr>
                        <td>
                            <img src="assets/img/katalog/<?= $row['foto']; ?>" width="60" style="border-radius: 4px; object-fit: cover; aspect-ratio: 1/1;">
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($row['nama_produk']); ?></strong><br>
                            <small style="color: #777;"><?= htmlspecialchars(substr($row['deskripsi'], 0, 50)) . '...'; ?></small>
                        </td>
                        <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td>
                            <?php 
                                if($row['status'] == 'pending') echo "<span class='status-badge status-pending'>Menunggu Review</span>";
                                elseif($row['status'] == 'approved') echo "<span class='status-badge status-approved'>Tayang</span>";
                                else echo "<span class='status-badge status-rejected'>Ditolak</span>";
                            ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; color: #777;">Belum ada produk yang diupload.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>