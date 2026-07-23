<?php
// Sesuaikan path koneksi dengan struktur folder admin kamu
require_once '../config/koneksi.php'; 

// Logika Approve / Reject
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $aksi = $_GET['aksi'];
    
    if ($aksi == 'approve') {
        mysqli_query($koneksi1, "UPDATE tb_katalog SET status='approved' WHERE id='$id'");
    } elseif ($aksi == 'reject') {
        mysqli_query($koneksi1, "UPDATE tb_katalog SET status='rejected' WHERE id='$id'");
    }
    
    header("Location: katalog_approval.php");
    exit;
}

// Ambil semua data produk dari semua UMKM
$query = "SELECT k.*, u.nama_usaha, u.no_wa 
          FROM tb_katalog k 
          JOIN tb_umkm u ON k.id_umkm = u.id 
          ORDER BY k.id DESC";
$result = mysqli_query($koneksi1, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Approval Katalog UMKM</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #333; }
        th, td { padding: 10px; text-align: left; }
        .btn-approve { background: green; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-reject { background: red; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>
    <h2>Approval Produk Tenun Ikat UMKM</h2>
    <a href="index.php" style="margin-bottom: 20px; display: inline-block;">&larr; Kembali ke Dashboard Admin</a>
    
    <table>
        <tr>
            <th>No</th>
            <th>Nama Usaha</th>
            <th>Foto</th>
            <th>Nama Produk & Harga</th>
            <th>Status Saat Ini</th>
            <th>Aksi</th>
        </tr>
        <?php $no=1; while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama_usaha']); ?><br><small><?= htmlspecialchars($row['no_wa']); ?></small></td>
            <td><img src="../assets/img/katalog/<?= $row['foto']; ?>" width="100"></td>
            <td>
                <strong><?= htmlspecialchars($row['nama_produk']); ?></strong><br>
                Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
            </td>
            <td>
                <b><?= strtoupper($row['status']); ?></b>
            </td>
            <td>
                <?php if($row['status'] == 'pending' || $row['status'] == 'rejected'): ?>
                    <a href="?aksi=approve&id=<?= $row['id']; ?>" class="btn-approve">Approve</a>
                <?php endif; ?>
                
                <?php if($row['status'] == 'pending' || $row['status'] == 'approved'): ?>
                    <a href="?aksi=reject&id=<?= $row['id']; ?>" class="btn-reject" onclick="return confirm('Tolak produk ini?');">Reject</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>