<?php
session_start();
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
    $nama_produk = mysqli_real_escape_string($koneksi1, $_POST['nama_produk']);
    $deskripsi   = mysqli_real_escape_string($koneksi1, $_POST['deskripsi']);
    $harga       = (int)$_POST['harga'];
    
    $foto = $_FILES['foto']['name'];
    $tmp  = $_FILES['foto']['tmp_name'];
    $ext  = pathinfo($foto, PATHINFO_EXTENSION);
    
    $nama_foto_baru = time() . '_' . rand(100,999) . '.' . $ext;
    $path = "assets/img/katalog/" . $nama_foto_baru;

    if (move_uploaded_file($tmp, $path)) {
        $query = "INSERT INTO tb_katalog (id_umkm, nama_produk, deskripsi, harga, foto, status) 
                  VALUES ('$id_umkm', '$nama_produk', '$deskripsi', '$harga', '$nama_foto_baru', 'pending')";
        if (mysqli_query($koneksi1, $query)) {
            $pesan = "<span style='color:green;'>Berhasil upload! Menunggu persetujuan admin.</span>";
        } else {
            $pesan = "<span style='color:red;'>Gagal simpan ke database.</span>";
        }
    } else {
        $pesan = "<span style='color:red;'>Gagal upload file gambar. Pastikan folder assets/img/katalog/ sudah ada.</span>";
    }
}

// Ambil data produk milik UMKM ini
$query_produk = "SELECT * FROM tb_katalog WHERE id_umkm = '$id_umkm' ORDER BY id DESC";
$result_produk = mysqli_query($koneksi1, $query_produk);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard UMKM</title>
    <style>
        body { font-family: sans-serif; background: #f9f9f9; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 8px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn { padding: 10px 15px; background: #d4af37; color: #fff; border: none; cursor: pointer; border-radius: 4px; }
        .btn-logout { background: #dc3545; text-decoration: none; padding: 8px 15px; color: white; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        .status-pending { color: orange; font-weight: bold; }
        .status-approved { color: green; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Halo, <?= htmlspecialchars($_SESSION['umkm_nama']); ?></h2>
            <a href="logout_umkm.php" class="btn-logout">Logout</a>
        </div>

        <h3>Upload Produk Tenun Ikat</h3>
        <?php if($pesan) echo "<p>$pesan</p>"; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" required>
            </div>
            <div class="form-group">
                <label>Harga (Hanya Angka, Contoh: 500000)</label>
                <input type="number" name="harga" required>
            </div>
            <div class="form-group">
                <label>Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Foto Produk</label>
                <input type="file" name="foto" accept="image/*" required>
            </div>
            <button type="submit" name="upload" class="btn">Upload Produk</button>
        </form>

        <hr style="margin: 40px 0;">

        <h3>Daftar Produk Saya</h3>
        <table>
            <tr>
                <th>Foto</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Status</th>
            </tr>
            <?php while($row = mysqli_fetch_assoc($result_produk)): ?>
            <tr>
                <td><img src="assets/img/katalog/<?= $row['foto']; ?>" width="80"></td>
                <td><?= htmlspecialchars($row['nama_produk']); ?></td>
                <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                <td>
                    <?php 
                        if($row['status'] == 'pending') echo "<span class='status-pending'>Menunggu Review</span>";
                        elseif($row['status'] == 'approved') echo "<span class='status-approved'>Tayang</span>";
                        else echo "<span class='status-rejected'>Ditolak</span>";
                    ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>