<?php
session_start();
require_once 'config/koneksi.php';

$pesan = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi1, $_POST['username']);
    $password = $_POST['password'];

    $cek = mysqli_query($koneksi1, "SELECT * FROM tb_umkm WHERE username='$username'");
    
    if (mysqli_num_rows($cek) === 1) {
        $row = mysqli_fetch_assoc($cek);
        if (password_verify($password, $row['password'])) {
            $_SESSION['umkm_id'] = $row['id'];
            $_SESSION['umkm_nama'] = $row['nama_usaha'];
            header("Location: dashboard_umkm.php");
            exit;
        } else {
            $pesan = "Password salah!";
        }
    } else {
        $pesan = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login UMKM - Budaya Adonara</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn { width: 100%; padding: 10px; background: #d4af37; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .alert { color: red; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2 style="text-align: center;">Login UMKM</h2>
        <?php if($pesan): ?><div class="alert"><?= $pesan; ?></div><?php endif; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn">Masuk</button>
            <p style="text-align: center; margin-top: 15px;"><a href="register_umkm.php">Belum punya akun? Daftar</a></p>
        </form>
    </div>
</body>
</html>