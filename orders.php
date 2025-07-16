<?php
require_once 'database.php';
require_once 'session.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil semua pesanan
$stmt = $pdo->query("
    SELECT o.id, o.total_amount, o.order_date, u.name 
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.order_date DESC
");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #1f1f1f;
            color: #e0e0e0;
            padding: 30px;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background-color: #2b2b2b;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(255,255,255,0.05);
        }
        h2 {
            text-align: center;
            color: #fff;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #3a3a3a;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #555;
        }
        th {
            background-color: #444;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #2f2f2f;
        }
        a {
            color: #ccc;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Laporan Transaksi</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Pelanggan</th>
            <th>Total Pembayaran</th>
            <th>Tanggal</th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= htmlspecialchars($order['name']) ?></td>
            <td>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
            <td><?= date("d-m-Y H:i", strtotime($order['order_date'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="dashboard.php">← Kembali ke Dashboard</a>
</div>
</body>
</html>
