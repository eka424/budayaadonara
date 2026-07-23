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
    <title>Login Mitra UMKM - Budaya Adonara</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
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
            background-color: var(--bg-body);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        .back-link {
            position: absolute;
            top: 30px;
            left: 40px;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-box {
            background: var(--bg-card);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid var(--border-color);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .brand-header h1 {
            font-family: 'Playfair Display', serif;
            color: var(--primary);
            font-size: 28px;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .brand-header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-main);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            background-color: #fafafa;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: #000;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            margin-top: 10px;
            font-family: 'Poppins', sans-serif;
            transition: background 0.3s, transform 0.1s;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
        }
        
        .btn-submit:active {
            transform: scale(0.98);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: center;
            font-weight: 500;
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

        .auth-links {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .auth-links a {
            color: var(--text-main);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .auth-links a:hover {
            color: var(--primary);
        }

        @media (max-width: 576px) {
            .back-link {
                top: 20px;
                left: 20px;
            }
            .login-box {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <a href="index.php" class="back-link">&larr; Kembali ke Beranda</a>

    <div class="login-wrapper">
        <div class="login-box">
            
            <div class="brand-header">
                <h1>Mitra UMKM</h1>
                <p>Login untuk mengelola katalog tenun ikat</p>
            </div>
            
            <?= $pesan_sukses; ?>
            <?= $pesan; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required autocomplete="current-password">
                </div>
                <button type="submit" name="login" class="btn-submit">Masuk ke Dashboard</button>
            </form>
            
            <div class="auth-links">
                Belum memiliki akun mitra? <br>
                <a href="register_umkm.php">Daftar sekarang</a>
            </div>

        </div>
    </div>

</body>
</html>