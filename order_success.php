<?php
require_once 'database.php';
require_once 'session.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['order_id'])) {
    echo "ID pesanan tidak ditemukan.";
    exit;
}

$order_id = $_GET['order_id'];

// Ambil detail order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Pesanan tidak ditemukan.";
    exit;
}

// Ambil item pesanan
$stmt_items = $pdo->prepare("
    SELECT oi.*, p.name 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pembelian - Bekas Berkelas</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', sans-serif;
            padding: 20px;
        }
        .struk {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            max-width: 700px;
            margin: 0 auto;
        }
        h2 {
            color: #00ffff;
            text-align: center;
        }
        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #444;
            text-align: left;
        }
        th {
            background-color: #00ffff;
            color: #121212;
        }
        .total {
            text-align: right;
            font-size: 18px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="struk">
    <h2>Bukti Pembelian</h2>

    <p><strong>Nama:</strong> <?= htmlspecialchars($order['nama']) ?></p>
    <p><strong>Alamat:</strong> <?= nl2br(htmlspecialchars($order['alamat'])) ?></p>
    <p><strong>Telepon:</strong> <?= htmlspecialchars($order['telepon']) ?></p>
    <p><strong>Tanggal:</strong> <?= $order['order_date'] ?></p>

    <h3>Detail Pesanan</h3>
    <table>
        <tr>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
        <?php 
        $grand_total = 0;
        foreach ($items as $item): 
            $subtotal = $item['quantity'] * $item['price'];
            $grand_total += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p class="total"><strong>Total: Rp <?= number_format($grand_total, 0, ',', '.') ?></strong></p>
    <div style="text-align: center; margin-top: 30px;">
    <a href="index.php" style="
        background-color: #00ffff;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
    ">Kembali ke Beranda</a>
</div>

</div>

</body>
</html>
