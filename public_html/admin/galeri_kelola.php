<?php
// admin/galeri_kelola.php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Proses Hapus Data dan File Fisik
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    
    $query_file = "SELECT file_path FROM tb_galeri WHERE id = '$id_hapus'";
    $result_file = mysqli_query($koneksi, $query_file);
    $data_file = mysqli_fetch_assoc($result_file);
    
    $query_hapus = "DELETE FROM tb_galeri WHERE id = '$id_hapus'";
    if (mysqli_query($koneksi, $query_hapus)) {
        if (!empty($data_file['file_path']) && file_exists("../" . $data_file['file_path'])) {
            unlink("../" . $data_file['file_path']);
        }
        echo "<script>alert('Data dan file berhasil dihapus!'); window.location='galeri_kelola.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.');</script>";
    }
}

$query = "SELECT * FROM tb_galeri ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Galeri - Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-dark: #1A362D; --gold: #D4AF37; --bg-color: #F4F7F6; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-color); display: flex; }

        /* --- SIDEBAR --- */
        .sidebar { width: 260px; background-color: var(--primary-dark); color: #fff; position: fixed; height: 100vh; z-index: 1000; transition: transform 0.3s ease; }
        .sidebar-header { padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-menu a { display: block; color: rgba(255,255,255,0.7); padding: 15px 20px; text-decoration: none; transition: 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(212, 175, 55, 0.2); color: var(--gold); }
        .btn-logout { color: #ff6b6b !important; margin-top: 20px; }

        /* --- MAIN --- */
        .main-content { flex: 1; margin-left: 260px; padding: 40px; }
        .menu-toggle { display: none; font-size: 24px; cursor: pointer; margin-bottom: 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        
        .table-container { background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; border-bottom: 1px solid #eee; text-align: left; }
        .btn { padding: 8px 15px; text-decoration: none; border-radius: 5px; font-size: 13px; cursor: pointer; }
        .btn-add { background: var(--primary-dark); color: var(--gold); }
        .btn-delete { background: #fee; color: #dc3545; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .menu-toggle { display: block; }
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="menu-toggle" id="menu-toggle">☰ Menu</div>
    <div class="page-header">
        <h2>Manajemen Galeri</h2>
        <a href="galeri_tambah.php" class="btn btn-add">＋ Tambah Media</a>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th><th>Tipe</th><th>Judul</th><th>Preview</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><span class="badge"><?= strtoupper($row['tipe']); ?></span></td>
                    <td><?= $row['judul']; ?></td>
                    <td>
                        <?php if($row['tipe'] == 'foto'): ?>
                            <img src="../<?= $row['file_path']; ?>" width="60">
                        <?php else: ?>
                            Video
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="galeri_kelola.php?hapus=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin hapus?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>

</body>
</html>