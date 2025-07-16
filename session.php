<?php
// Hindari error "session already active"
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk mengecek login
function check_login() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}
