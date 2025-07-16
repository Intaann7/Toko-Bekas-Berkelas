<?php
session_start();
require_once 'database.php';

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    header("Location: index.php");
    exit;
}

// Ambil data produk
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit;
}

// Inisialisasi keranjang
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Tambah ke keranjang
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity']++;
} else {
    $_SESSION['cart'][$product_id] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1
    ];
}

$_SESSION['success_message'] = "Produk ditambahkan ke keranjang.";
header("Location: cart.php");
exit;
?>
