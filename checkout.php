<?php
require_once 'database.php';
require_once 'session.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    $_SESSION['error_message'] = "Keranjang kosong!";
    header("Location: cart.php");
    exit;
}

// Ambil detail produk berdasarkan cart
$product_ids = implode(',', array_keys($cart));
$stmt = $pdo->query("SELECT * FROM products WHERE id IN ($product_ids)");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hitung total
$total = 0;
foreach ($products as $product) {
    $jumlah = $cart[$product['id']];
    $total += $product['price'] * $jumlah;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Bekas Berkelas</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }
        h2 {
            color: #00ffff;
            text-align: center;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
        }
        button {
            background-color: #00bcd4;
            border: none;
            padding: 10px 15px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        table {
            width: 100%;
            margin-top: 20px;
            background-color: #1a1a1a;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #333;
            text-align: center;
        }
        th {
            background-color: #00ffff;
            color: #121212;
        }
    </style>
</head>
<body>

    <h2>Konfirmasi Pesanan</h2>

    <form action="process_order.php" method="post">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" required>

        <label>Alamat Lengkap</label>
        <textarea name="alamat" required></textarea>

        <label>No. Telepon / WhatsApp</label>
        <input type="text" name="telepon" required>

        <h3>Detail Pesanan:</h3>
        <table>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($products as $product): 
                $qty = $cart[$product['id']];
                $subtotal = $product['price'] * $qty;
            ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= $qty ?></td>
                <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="3">Total</th>
                <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
            </tr>
        </table>

        <br>
        <button type="submit">Konfirmasi Pesanan</button>
    </form>

</body>
</html>
