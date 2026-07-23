<?php
session_start();

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Arahkan pengguna kembali ke halaman login (atau beranda)
header("Location: login_umkm.php");
exit;
?>