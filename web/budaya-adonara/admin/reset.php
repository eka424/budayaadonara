<?php
require_once '../config/koneksi.php';

// Membuat hash baru yang valid untuk password 'admin123'
$password_baru = password_hash('admin123', PASSWORD_DEFAULT);

// Mengupdate database
$query = "UPDATE tb_admin SET password = '$password_baru' WHERE username = 'admin'";

if (mysqli_query($koneksi, $query)) {
    echo "<h3>Sukses! Password admin telah direset menjadi: <b>admin123</b></h3>";
    echo "<p>Silakan kembali ke <a href='login.php'>Halaman Login</a>.</p>";
    echo "<p style='color:red;'>PENTING: Segera hapus file <b>reset.php</b> ini setelah berhasil login.</p>";
} else {
    echo "Gagal mereset password: " . mysqli_error($koneksi);
}
?>