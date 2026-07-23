<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pastikan admin sudah login
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php'; 

// Logika Approve / Reject
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $aksi = $_GET['aksi'];
    
    if ($aksi == 'approve') {
        mysqli_query($koneksi, "UPDATE tb_katalog SET status='approved' WHERE id='$id'");
    } elseif ($aksi == 'reject') {
        mysqli_query($koneksi, "UPDATE tb_katalog SET status='rejected' WHERE id='$id'");
    }
    
    header("Location: katalog_approval.php");
    exit;
}

// Ambil semua data produk dari semua UMKM
$query = "SELECT k.*, u.nama_usaha, u.no_wa 
          FROM tb_katalog k 
          JOIN tb_umkm u ON k.id_umkm = u.id 
          ORDER BY k.id DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Katalog UMKM - Admin Budaya Adonara</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1A362D;
            --gold: #D4AF37;
            --bg-color: #F4F7F6;
            --card-bg: #FFFFFF;
            --text-main: #333333;
            --text-muted: #777777;
            --border-color: #eaeaea;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-color); display: flex; color: var(--text-main); }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px; background-color: var(--primary-dark); color: #fff;
            position: fixed; height: 100vh; z-index: 1000;
            transition: transform 0.3s ease;
        }
        .sidebar-header { padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu a { display: block; color: rgba(255,255,255,0.7); padding: 15px 20px; text-decoration: none; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(212, 175, 55, 0.2); color: var(--gold); }
        .btn-logout { color: #ff6b6b !important; margin-top: 20px; }

        /* --- MAIN CONTENT --- */
        .main-content { flex: 1; margin-left: 260px; padding: 40px; }
        
        /* Hamburger Mobile */
        .menu-toggle { display: none; font-size: 24px; cursor: pointer; margin-bottom: 20px; }

        .page-title {
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-dark);
        }

        /* --- TABLE STYLES --- */
        .table-card {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        .table-responsive { overflow-x: auto; padding: 20px;}
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border-color); font-size: 14px; }
        th { background-color: #f9f9f9; color: var(--text-muted); font-weight: 500; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px; }
        tr:hover td { background-color: #fcfcfc; }

        /* --- ELEMENTS --- */
        .umkm-info { display: flex; flex-direction: column; gap: 4px; }
        .umkm-name { font-weight: 600; color: var(--text-main); }
        .umkm-wa { font-size: 12px; color: var(--text-muted); }

        .product-thumb { 
            width: 60px; 
            height: 60px; 
            border-radius: 8px; 
            object-fit: cover; 
            border: 1px solid #eee; 
            cursor: pointer;
            transition: transform 0.2s;
        }
        .product-thumb:hover {
            transform: scale(1.05);
        }
        
        .product-detail { display: flex; flex-direction: column; gap: 4px; }
        .product-name { font-weight: 600; }
        .product-price { color: var(--gold); font-weight: 500; }

        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; display: inline-block; }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-approved { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }

        .action-buttons { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn-action { padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 500; text-decoration: none; transition: 0.3s; text-align: center; }
        .btn-approve { background: #e6f4ea; color: #137333; border: 1px solid #ceead6; }
        .btn-approve:hover { background: #137333; color: #fff; }
        .btn-reject { background: #ffeaea; color: #d93025; border: 1px solid #f5c6c6; }
        .btn-reject:hover { background: #d93025; color: #fff; }

        /* --- MODAL IMAGE --- */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1001; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            background-color: rgba(0,0,0,0.85); 
            align-items: center; 
            justify-content: center;
        }
        .modal-content {
            max-width: 90%; 
            max-height: 90%; 
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }
        .close-modal {
            position: absolute; 
            top: 20px; 
            right: 35px; 
            color: #f1f1f1; 
            font-size: 40px; 
            font-weight: bold; 
            cursor: pointer;
            transition: 0.3s;
        }
        .close-modal:hover {
            color: var(--gold);
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .menu-toggle { display: block; }
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="menu-toggle" id="menu-toggle">☰ Menu</div>
    
    <h2 class="page-title">Approval Katalog UMKM</h2>
    
    <div class="table-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Data Mitra UMKM</th>
                        <th>Foto</th>
                        <th>Produk & Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result && mysqli_num_rows($result) > 0): ?>
                        <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <div class="umkm-info">
                                    <span class="umkm-name"><?= htmlspecialchars($row['nama_usaha']); ?></span>
                                    <span class="umkm-wa">WA: <?= htmlspecialchars($row['no_wa']); ?></span>
                                </div>
                            </td>
                            <td>
                                <!-- Tambahkan event onclick pada foto -->
                                <img src="../assets/uploads/foto/<?= htmlspecialchars($row['foto']); ?>" class="product-thumb" alt="Foto Produk" onclick="openModal(this.src)" title="Klik untuk memperbesar">
                            </td>
                            <td>
                                <div class="product-detail">
                                    <span class="product-name"><?= htmlspecialchars($row['nama_produk']); ?></span>
                                    <span class="product-price">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></span>
                                </div>
                            </td>
                            <td>
                                <?php 
                                    if($row['status'] == 'pending') echo "<span class='status-badge status-pending'>Pending Review</span>";
                                    elseif($row['status'] == 'approved') echo "<span class='status-badge status-approved'>Approved</span>";
                                    else echo "<span class='status-badge status-rejected'>Rejected</span>";
                                ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?php if($row['status'] == 'pending' || $row['status'] == 'rejected'): ?>
                                        <a href="?aksi=approve&id=<?= $row['id']; ?>" class="btn-action btn-approve" onclick="return confirm('Setujui produk ini tayang ke katalog publik?');">Approve</a>
                                    <?php endif; ?>
                                    
                                    <?php if($row['status'] == 'pending' || $row['status'] == 'approved'): ?>
                                        <a href="?aksi=reject&id=<?= $row['id']; ?>" class="btn-action btn-reject" onclick="return confirm('Tolak produk ini?');">Reject</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" style="text-align: center; padding: 40px; color: #777;">Belum ada pengajuan produk dari UMKM.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Container untuk Gambar -->
<div id="imageModal" class="modal">
    <span class="close-modal" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script>
    // Script Sidebar
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });

    // Script Modal Lightbox
    function openModal(imageSrc) {
        document.getElementById('imageModal').style.display = 'flex';
        document.getElementById('modalImage').src = imageSrc;
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    // Menutup modal jika area luar gambar diklik
    window.onclick = function(event) {
        var modal = document.getElementById('imageModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

</body>
</html>p