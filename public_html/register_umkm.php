<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/koneksi.php';

$pesan = '';

if (isset($_POST['register'])) {
    $nama_lengkap = mysqli_real_escape_string($koneksi, trim($_POST['nama_lengkap']));
    $nama_usaha   = mysqli_real_escape_string($koneksi, trim($_POST['nama_usaha']));
    $no_wa        = mysqli_real_escape_string($koneksi, trim($_POST['no_wa']));
    $username     = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek username
    $cek = mysqli_query($koneksi, "SELECT * FROM tb_umkm WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "<div class='alert error'>Username sudah digunakan, silakan cari yang lain!</div>";
    } else {
        $query = "INSERT INTO tb_umkm (nama_lengkap, nama_usaha, no_wa, username, password) 
                  VALUES ('$nama_lengkap', '$nama_usaha', '$no_wa', '$username', '$password')";
        if (mysqli_query($koneksi, $query)) {
            header("Location: login_umkm.php?status=success");
            exit;
        } else {
            $pesan = "<div class='alert error'>Gagal mendaftar: " . mysqli_error($koneksi) . "</div>";
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .form-container {
            width: 100%;
            max-width: 450px;
            background: #fff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            box-sizing: border-box;
        }
        .form-container h2 {
            text-align: center;
            margin-top: 0;
            margin-bottom: 25px;
            color: #333;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #d4af37;
            box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2);
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #d4af37;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
            font-family: 'Poppins', sans-serif;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: #c19b2e;
        }
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
        .alert.error {
            background-color: #ffeaea;
            color: #d93025;
            border: 1px solid #f5c6c6;
        }
        .link-login {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
            text-decoration: none;
        }
        .link-login:hover {
            color: #d4af37;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Pendaftaran Akun UMKM</h2>
        <?= $pesan; ?>
        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Nama Usaha / Kelompok Tenun</label>
                <input type="text" name="nama_usaha" placeholder="Contoh: Tenun Indah Adonara" required>
            </div>
            <div class="form-group">
                <label>Nomor WhatsApp</label>
                <input type="text" name="no_wa" placeholder="Contoh: 6281234567890" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Buat username untuk login" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat password akun" required>
            </div>
            <button type="submit" name="register" class="btn-submit">Daftar Sekarang</button>
        </form>
        <a href="login_umkm.php" class="link-login">Sudah punya akun? Login di sini</a>
    </div>
</body>
</html>