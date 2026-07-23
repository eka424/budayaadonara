<?php
require_once 'config/koneksi.php';

$pesan = '';

if (isset($_POST['register'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi1, $_POST['nama_lengkap']);
    $nama_usaha   = mysqli_real_escape_string($koneksi1, $_POST['nama_usaha']);
    $no_wa        = mysqli_real_escape_string($koneksi1, $_POST['no_wa']);
    $username     = mysqli_real_escape_string($koneksi1, $_POST['username']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek username
    $cek = mysqli_query($koneksi1, "SELECT * FROM tb_umkm WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Username sudah digunakan!";
    } else {
        $query = "INSERT INTO tb_umkm (nama_lengkap, nama_usaha, no_wa, username, password) 
                  VALUES ('$nama_lengkap', '$nama_usaha', '$no_wa', '$username', '$password')";
        if (mysqli_query($koneksi1, $query)) {
            header("Location: login_umkm.php?status=success");
            exit;
        } else {
            $pesan = "Gagal mendaftar!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pelaku UMKM</title>
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <style>
        .form-container { max-width: 450px; margin: 50px auto; padding: 25px; background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { width: 100%; padding: 10px; background: #d4af37; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .alert { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Pendaftaran Akun UMKM</h2>
        <?php if($pesan): ?><p class="alert"><?= $pesan; ?></p><?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required>
            </div>
            <div class="form-group">
                <label>Nama Usaha / Kelompok Tenun</label>
                <input type="text" name="nama_usaha" required>
            </div>
            <div class="form-group">
                <label>Nomor WhatsApp (Contoh: 6281234567890)</label>
                <input type="text" name="no_wa" placeholder="628..." required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="register" class="btn-submit">Daftar Sekarang</button>
        </form>
    </div>
</body>
</html>