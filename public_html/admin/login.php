<?php
// admin/login.php
session_start();
require_once '../config/koneksi.php';

// Jika admin sudah login, langsung dialihkan ke dashboard
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

// ========================================================================
// SCRIPT OTOMATIS: Membuat akun admin "albert" dengan password "iniadmin123"
// Jika akun sudah ada, script ini akan dilewati.
// ========================================================================
$cek_admin = mysqli_query($koneksi, "SELECT * FROM tb_admin WHERE username = 'albert'");
if(mysqli_num_rows($cek_admin) == 0) {
    // Enkripsi password menggunakan BCRYPT standar PHP
    $hashed_password = password_hash('iniadmin123', PASSWORD_DEFAULT);
    // Masukkan ke database
    mysqli_query($koneksi, "INSERT INTO tb_admin (username, password, nama_lengkap) VALUES ('albert', '$hashed_password', 'Administrator')");
}
// ========================================================================

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Cari data admin berdasarkan username
        $query  = "SELECT * FROM tb_admin WHERE username = '$username'";
        $result = mysqli_query($koneksi, $query);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            
            // Verifikasi password dengan hash yang ada di database
            if (password_verify($password, $row['password'])) {
                // Set session admin
                $_SESSION['admin'] = $row['username'];
                $_SESSION['nama_admin'] = $row['nama_lengkap'];
                
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Password yang Anda masukkan salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    } else {
        $error = "Username dan password harus diisi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Budaya Adonara</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-dark: #1A362D; 
            --gold: #D4AF37;
            --bg-color: #F4F7F6;
            --text-main: #2C3E50;
            --input-border: #E0E4E8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--primary-dark); /* Tema gelap untuk login */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            /* Efek background pattern halus (opsional) */
            background-image: radial-gradient(circle at center, #264a3f 0%, #1A362D 100%);
        }

        .login-card {
            background-color: #fff;
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            padding: 40px 35px;
            position: relative;
            overflow: hidden;
        }

        /* Garis emas di atas kartu login */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 5px;
            background: linear-gradient(90deg, var(--gold), #F1C40F);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h2 {
            color: var(--primary-dark);
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .login-header p {
            color: #7F8C8D;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px;
            border: 1px solid var(--input-border);
            border-radius: 6px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: var(--text-main);
            transition: all 0.3s ease;
            background-color: #FAFCFF;
        }

        .form-control:focus {
            border-color: var(--gold);
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15);
            background-color: #fff;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: var(--gold);
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #c4a132;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
            transform: translateY(-2px);
        }

        .error-message {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: center;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        /* Copyright text */
        .login-footer {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #A0AAB2;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <h2>ADONARA</h2>
        <p>Login Portal Administrator</p>
    </div>
    
    <?php if (!empty($error)): ?>
        <div class="error-message">
            ⚠️ <?= $error; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="username">USERNAME</label>
            <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan username..." required autocomplete="off">
        </div>
        
        <div class="form-group">
            <label for="password">PASSWORD</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password..." required>
        </div>
        
        <button type="submit" name="login" class="btn-login">MASUK</button>
    </form>

    <div class="login-footer">
        &copy; <?= date('Y'); ?> Pelestarian Budaya Adonara
    </div>
</div>

</body>
</html>