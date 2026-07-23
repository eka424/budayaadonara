<?php
// admin/sejarah_kelola.php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    
    $query_file = "SELECT audio_path FROM tb_sejarah WHERE id = '$id_hapus'";
    $result_file = mysqli_query($koneksi, $query_file);
    $data_file = mysqli_fetch_assoc($result_file);

    $query_hapus = "DELETE FROM tb_sejarah WHERE id = '$id_hapus'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        if (!empty($data_file['audio_path']) && file_exists("../" . $data_file['audio_path'])) {
            unlink("../" . $data_file['audio_path']);
        }
        echo "<script>alert('Data berhasil dihapus!'); window.location='sejarah_kelola.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.');</script>";
    }
}

$query = "SELECT * FROM tb_sejarah ORDER BY id ASC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Sejarah - Admin</title>
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
        .btn-edit { background: rgba(243, 156, 18, 0.1); color: #d68910; }
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
        <h2>Manajemen Data Sejarah</h2>
        <a href="sejarah_tambah.php" class="btn btn-add">＋ Tambah Data</a>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th><th>Kategori</th><th>Judul</th><th>Audio</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= strtoupper($row['kategori']); ?></td>
                    <td><?= $row['judul']; ?></td>
                    <td><?= !empty($row['audio_path']) ? '🎧 Tersedia' : 'Kosong'; ?></td>
                    <td>
                        <a href="sejarah_edit.php?id=<?= $row['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="sejarah_kelola.php?hapus=<?= $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin hapus?');">Hapus</a>
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