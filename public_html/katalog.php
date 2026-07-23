<?php
require_once 'config/koneksi.php';

// Ambil data katalog yang SUDAH DI-APPROVE admin
$query = "SELECT k.*, u.nama_usaha, u.no_wa 
          FROM tb_katalog k 
          JOIN tb_umkm u ON k.id_umkm = u.id 
          WHERE k.status = 'approved' 
          ORDER BY k.id DESC";
$result = mysqli_query($koneksi1, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Kain Tenun Ikat - Budaya Adonara</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <style>
        .katalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 40px; }
        .card { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background: #fff; text-align: center; }
        .card img { width: 100%; height: 200px; object-fit: cover; }
        .card-body { padding: 15px; }
        .harga { color: #d4af37; font-weight: bold; font-size: 1.1em; margin: 10px 0; }
        .btn-wa { display: inline-block; background-color: #25D366; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <h1 style="text-align: center; margin-top: 30px;">Katalog Kain Tenun Ikat</h1>
    
    <div class="katalog-grid">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <?php 
                $pesan_wa = urlencode("Halo " . $row['nama_usaha'] . ", saya tertarik dengan produk kain tenun: " . $row['nama_produk']);
                $link_wa  = "https://wa.me/" . preg_replace('/[^0-9]/', '', $row['no_wa']) . "?text=" . $pesan_wa;
            ?>
            <div class="card">
                <img src="assets/img/katalog/<?= $row['foto']; ?>" alt="<?= $row['nama_produk']; ?>">
                <div class="card-body">
                    <h3><?= htmlspecialchars($row['nama_produk']); ?></h3>
                    <p><small>Oleh: <?= htmlspecialchars($row['nama_usaha']); ?></small></p>
                    <p class="harga">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                    <p><?= htmlspecialchars($row['deskripsi']); ?></p>
                    <a href="<?= $link_wa; ?>" target="_blank" class="btn-wa">Beli via WhatsApp</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>