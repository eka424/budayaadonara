<?php
// config/koneksi.php

$host     = "localhost"; 
$username = "u924919069_budayaadonara"; // Sudah ditambah prefix angka
$password = "Adonara123;";              // Sudah diganti menggunakan titik koma (;)
$database = "u924919069_budayaadonara"; 

$koneksi = mysqli_connect($host, $username, $password, $database);

// Validasi Koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset ke UTF-8 untuk mendukung karakter teks khusus jika ada
mysqli_set_charset($koneksi, "utf8");
?>