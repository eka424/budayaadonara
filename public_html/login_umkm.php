<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/koneksi.php';

$pesan = '';
$pesan_sukses = '';

// Menangkap pesan sukses dari halaman registrasi
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $pesan_sukses = "<div class='alert success'>Registrasi berhasil! Silakan login.</div>";
}

if (isset($_POST['login'])) {
    // Ubah $koneksi1 menjadi $koneksi
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = $_POST['password'];

    $cek = mysqli_query($koneksi, "SELECT * FROM tb_umkm WHERE username='$username'");
    
    if (mysqli_num_rows($cek) === 1) {
        $row = mysqli_fetch_assoc($cek);
        if (password_verify($password, $row['password'])) {
            $_SESSION['umkm_id'] = $row['id'];
            $_SESSION['umkm_nama'] = $row['nama_usaha'];
            header("Location: dashboard_umkm.php");
            exit;
        } else {
            $pesan = "<div class='alert error'>Password salah!</div>";
        }
    } else {
        $pesan = "<div class='alert error'>Username tidak ditemukan!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login UMKM - Budaya Adonara</title>
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
        .login-box {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            box-sizing: border-box;
        }
        .login-box h2 {
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
        .btn {
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
        .btn:hover {
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
        .alert.success {
            background-color: #e6f4ea;
            color: #137333;
            border: 1px solid #ceead6;
        }
        .link-register {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
            text-decoration: none;
        }
        .link-register:hover {
            color: #d4af37;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login UMKM</h2>
        
        <?= $pesan_sukses; ?>
        <?= $pesan; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" name="login" class="btn">Masuk</button>
        </form>
        
        <a href="register_umkm.php" class="link-register">Belum punya akun? Daftar di sini</a>
    </div>
</body>
</html>