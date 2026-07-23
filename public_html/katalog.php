<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/koneksi.php';

// Ambil data katalog yang SUDAH DI-APPROVE admin
$query = "SELECT k.*, u.nama_usaha, u.no_wa 
          FROM tb_katalog k 
          JOIN tb_umkm u ON k.id_umkm = u.id 
          WHERE k.status = 'approved' 
          ORDER BY k.id DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Kain Tenun Ikat - Budaya Adonara</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f4f7f6; 
            margin: 0; 
            padding: 0;
        }
        .header-title {
            text-align: center;
            margin-top: 40px;
            color: #333;
            font-weight: 600;
        }
        .katalog-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); 
            gap: 25px; 
            padding: 40px; 
            max-width: 1200px;
            margin: 0 auto;
        }
        .card { 
            background: #fff; 
            border-radius: 10px; 
            overflow: hidden; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .card img { 
            width: 100%; 
            height: 220px; 
            object-fit: cover; 
            border-bottom: 1px solid #eee;
        }
        .card-body { 
            padding: 20px; 
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .card-body h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            color: #333;
        }
        .seller-info {
            color: #777;
            font-size: 13px;
            margin-bottom: 10px;
        }
        .harga { 
            color: #d4af37; 
            font-weight: 600; 
            font-size: 1.2em; 
            margin: 10px 0; 
        }
        .desc {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
            flex-grow: 1;
            line-height: 1.5;
        }
        .btn-wa { 
            display: block; 
            text-align: center;
            background-color: #25D366; 
            color: white; 
            padding: 12px 15px; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: 500; 
            transition: background-color 0.3s;
        }
        .btn-wa:hover {
            background-color: #20b858;
        }
        .nav-back {
            display: inline-block;
            margin: 20px 0 0 40px;
            color: #555;
            text-decoration: none;
            font-weight: 500;
        }
        .nav-back:hover {
            color: #d4af37;
        }
    </style>
</head>
<body>
    <a href="index.php" class="nav-back">&larr; Kembali ke Beranda</a>
    <h1 class="header-title">Katalog Kain Tenun Ikat</h1>
    
    <div class="katalog-grid">
        <?php if($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <?php 
                    $pesan_wa = urlencode("Halo " . $row['nama_usaha'] . ", saya tertarik dengan produk kain tenun: " . $row['nama_produk']);
                    $link_wa  = "https://wa.me/" . preg_replace('/[^0-9]/', '', $row['no_wa']) . "?text=" . $pesan_wa;
                ?>
                <div class="card">
                    <img src="assets/uploads/foto/<?= $row['foto']; ?>" alt="<?= htmlspecialchars($row['nama_produk']); ?>">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($row['nama_produk']); ?></h3>
                        <div class="seller-info">Oleh: <?= htmlspecialchars($row['nama_usaha']); ?></div>
                        <div class="harga">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></div>
                        <div class="desc"><?= htmlspecialchars($row['deskripsi']); ?></div>
                        <a href="<?= $link_wa; ?>" target="_blank" class="btn-wa">Beli via WhatsApp</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; grid-column: 1 / -1; color: #777;">Belum ada produk yang tersedia saat ini.</p>
        <?php endif; ?>
    </div>
</body>
</html>