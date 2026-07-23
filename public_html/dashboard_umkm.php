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
    $path = "assets/uploads/foto/" . $nama_foto_baru;

    if (move_uploaded_file($tmp, $path)) {
        $query = "INSERT INTO tb_katalog (id_umkm, nama_produk, deskripsi, harga, foto, status) 
                  VALUES ('$id_umkm', '$nama_produk', '$deskripsi', '$harga', '$nama_foto_baru', 'pending')";
        if (mysqli_query($koneksi, $query)) {
            $pesan = "<div class='alert success'>Berhasil upload! Menunggu persetujuan admin.</div>";
        } else {
            $pesan = "<div class='alert error'>Gagal simpan ke database: " . mysqli_error($koneksi) . "</div>";
        }
    } else {
        $pesan = "<div class='alert error'>Gagal upload file gambar. Pastikan folder assets/uploads/foto/ sudah ada dan writable.</div>";
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #d4af37;
            --primary-hover: #c19b2e;
            --bg-body: #f4f7f6;
            --bg-card: #ffffff;
            --text-main: #333333;
            --text-muted: #777777;
            --border-color: #eaeaea;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background: var(--bg-body); 
            color: var(--text-main);
            line-height: 1.6;
        }

        /* Layout Utama */
        .dashboard-wrapper {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            align-items: start;
        }

        /* Card Style Umum */
        .card {
            background: var(--bg-card);
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.03);
            padding: 30px;
            border: 1px solid var(--border-color);
        }

        /* Profile Sidebar */
        .profile-sidebar {
            text-align: center;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 15px;
            background-color: #eee;
        }
        .profile-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--text-main);
        }
        .profile-role {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-logout {
            display: block;
            width: 100%;
            padding: 12px;
            background: #fff;
            color: #dc3545;
            border: 1px solid #dc3545;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            background: #dc3545;
            color: #fff;
        }

        /* Main Content Area */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        /* Form Upload */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .form-group { margin-bottom: 20px; }
        .form-group.full-width { grid-column: 1 / -1; }
        .form-group label { 
            display: block; 
            font-weight: 500; 
            margin-bottom: 8px; 
            font-size: 14px;
        }
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: border-color 0.3s;
            background-color: #fafafa;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background-color: #fff;
        }
        textarea.form-control { resize: vertical; min-height: 100px; }
        
        .btn-submit {
            padding: 12px 25px;
            background: var(--primary);
            color: #000;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-block;
        }
        .btn-submit:hover { background: var(--primary-hover); }

        /* Table Produk */
        .table-responsive {
            overflow-x: auto;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th, td { 
            padding: 15px; 
            text-align: left; 
            border-bottom: 1px solid var(--border-color); 
            font-size: 14px; 
        }
        th { 
            background-color: #f9f9f9; 
            color: var(--text-muted); 
            font-weight: 500; 
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        tr:hover td { background-color: #fcfcfc; }
        
        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .product-img {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid #eee;
        }
        .product-name {
            font-weight: 600;
            color: var(--text-main);
            display: block;
        }
        .product-desc {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Status Badges */
        .status-badge { 
            padding: 6px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: 500; 
            display: inline-block; 
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }

        /* Alerts */
        .alert { 
            padding: 15px; 
            border-radius: 8px; 
            margin-bottom: 25px; 
            font-size: 14px; 
            font-weight: 500;
        }
        .alert.error { background-color: #ffeaea; color: #d93025; border: 1px solid #f5c6c6; }
        .alert.success { background-color: #e6f4ea; color: #137333; border: 1px solid #ceead6; }

        /* Responsive */
        @media (max-width: 992px) {
            .dashboard-wrapper {
                grid-template-columns: 1fr;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        
        <!-- Sidebar Profile -->
        <aside class="profile-sidebar card">
            <!-- Gunakan placeholder image untuk foto profil sementara -->
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['umkm_nama']); ?>&background=d4af37&color=000" alt="Profile" class="profile-avatar">
            
            <h2 class="profile-name"><?= htmlspecialchars($_SESSION['umkm_nama']); ?></h2>
            <div class="profile-role">Mitra UMKM Adonara</div>
            
            <a href="logout_umkm.php" class="btn-logout">Logout</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            
            <?= $pesan; ?>

            <!-- Form Upload Card -->
            <div class="card">
                <h3 class="section-title">Upload Produk Tenun Ikat</h3>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Sarung Tenun Motif X" required>
                        </div>
                        <div class="form-group">
                            <label>Harga (Hanya Angka)</label>
                            <input type="number" name="harga" class="form-control" placeholder="Contoh: 500000" required>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Deskripsi Singkat</label>
                        <textarea name="deskripsi" class="form-control" placeholder="Jelaskan ukuran, bahan, dan motif..." required></textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Foto Produk</label>
                        <input type="file" name="foto" class="form-control" accept="image/*" required style="padding: 9px 15px;">
                    </div>
                    
                    <button type="submit" name="upload" class="btn-submit">Upload Produk</button>
                </form>
            </div>

            <!-- Table Card -->
            <div class="card">
                <h3 class="section-title">Daftar Produk Saya</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result_produk) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result_produk)): ?>
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <img src="assets/uploads/foto/<?= $row['foto']; ?>" class="product-img" alt="Foto Produk">
                                            <div>
                                                <span class="product-name"><?= htmlspecialchars($row['nama_produk']); ?></span>
                                                <span class="product-desc"><?= htmlspecialchars(substr($row['deskripsi'], 0, 50)) . '...'; ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-weight: 500;">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
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
                                    <td colspan="3" style="text-align: center; color: #777; padding: 30px;">Belum ada produk yang diupload.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</body>
</html>