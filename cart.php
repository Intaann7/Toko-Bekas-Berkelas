<?php
require_once 'database.php';
require_once 'session.php';


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


// Tambah ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;
}

// Hapus item
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
}

// Bersihkan keranjang
if (isset($_GET['clear'])) {
    $_SESSION['cart'] = [];
}

// Ambil info produk
$cart_items = [];
$total = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if ($product) {
        $product['qty'] = $qty;
        $product['subtotal'] = $qty * $product['price'];
        $total += $product['subtotal'];
        $cart_items[] = $product;
    }
}

// Notifikasi
$success = $_SESSION['success_message'] ?? null;
$error = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang - Bekas Berkelas</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 30px;
        }
        h2 {
            color: #00ffff;
            text-align: center;
            margin-bottom: 20px;
        }
        .notification {
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .success {
            background-color: #1b5e20;
            color: #a5d6a7;
        }
        .error {
            background-color: #b71c1c;
            color: #ffcdd2;
        }
        .link-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .link-bar a {
            margin: 0 10px;
            color: #00ffff;
            text-decoration: none;
        }
        .link-bar a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #1e1e1e;
        }
        th, td {
            padding: 12px;
            border: 1px solid #444;
            text-align: center;
        }
        th {
            background-color: #00ffff;
            color: #000;
        }
        tr:nth-child(even) {
            background-color: #222;
        }
        tr:hover {
            background-color: #333;
        }
        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #00bcd4;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #00acc1;
        }
        h3 {
            text-align: right;
            margin-top: 20px;
            color: #00ffff;
        }
    </style>
</head>
<body>

    <h2>Keranjang Belanja</h2>

    <?php if ($success): ?>
        <div class="notification success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="notification error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="link-bar">
        <a href="index.php">← Kembali</a> |
        <a href="cart.php?clear=1" onclick="return confirm('Bersihkan semua?')">Bersihkan Keranjang</a>
    </div>

    <table>
        <tr>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                <td><?= $item['qty'] ?></td>
                <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                <td><a href="?remove=<?= $item['id'] ?>" style="color:red;">Hapus</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

        <h3>Total: Rp <?= number_format($total, 0, ',', '.') ?></h3>

    <?php if ($total > 0): ?>
        <?php if (isset($_SESSION['user'])): ?>
            <div style="text-align: right; margin-top: 20px;">
                <a href="checkout.php" class="btn">Checkout</a>
            </div>
        <?php else: ?>
            <div style="margin-top: 20px; padding: 12px; background-color: #ffe6e6; color: #b30000; border-radius: 6px; text-align: center;">
                Anda belum login. Silakan <a href="login.php" style="color: #00ffff; text-decoration: underline;">login</a> untuk melakukan checkout.
            </div>
        <?php endif; ?>
    <?php endif; ?>




</body>
</html>
