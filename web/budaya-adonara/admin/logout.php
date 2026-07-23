<?php
// admin/logout.php
session_start();

// 1. Kosongkan semua data/variabel session
$_SESSION = [];
session_unset();

// 2. Hapus cookie session di browser (untuk keamanan ekstra)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hancurkan session sepenuhnya di server
session_destroy();

// 4. Arahkan kembali ke halaman login
header("Location: login.php");
exit;
?>