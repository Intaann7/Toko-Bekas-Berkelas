<?php
require_once 'database.php';
require_once 'session.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Ambil data produk
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();

// Ambil laporan penjualan total
$stmt2 = $pdo->query("SELECT COUNT(*) AS total_transaksi, SUM(total_amount) AS total_pendapatan FROM orders");
$data_penjualan = $stmt2->fetch();

// Ambil detail laporan penjualan
$stmt3 = $pdo->query("
    SELECT 
        o.id AS order_id,
        u.name AS user_name,
        p.name AS product_name,
        oi.quantity,
        o.total_amount
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    ORDER BY o.id DESC
");
$detail_penjualan = $stmt3->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Bekas Berkelas</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 30px;
            background-color: #1e1e1e;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.1);
        }
        h1, h2 {
            text-align: center;
            color: #00ffff;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #292929;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #444;
            text-align: center;
        }
        th {
            background-color: #00ffff;
            color: #000;
        }
        tr:nth-child(even) {
            background-color: #1a1a1a;
        }
        tr:hover {
            background-color: #333;
        }
        .btn {
            padding: 8px 12px;
            background-color: #00bcd4;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #00acc1;
        }
        .info {
            text-align: center;
            margin-bottom: 25px;
        }
        .info strong {
            color: #00ffff;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
            text-align: center;
        }
        .actions a {
            text-decoration: none;
            padding: 12px 24px;
            background-color: #333;
            color: #00ffff;
            border-radius: 8px;
            transition: 0.3s;
            box-shadow: 0 2px 5px rgba(0, 255, 255, 0.1);
        }
        .actions a:hover {
            background-color: #00ffff;
            color: #000;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Dashboard Admin</h1>
    <div class="info">
        Login sebagai: <strong><?= htmlspecialchars($user['name']) ?></strong> (<?= $user['role'] ?>)
    </div>

    <div class="actions">
        <a href="#produk" onclick="showSection('produk')">Detail Produk</a>
        <a href="#laporan" onclick="showSection('laporan')">Kelola Pesanan</a>
        <a href="index.php">Kembali ke Beranda</a>
        <a href="logout.php">Logout</a>
    </div>

    <div id="laporan" style="display: none;">
        <h2>Laporan Penjualan</h2>
        <table>
            <tr>
                <th>Total Transaksi</th>
                <th>Total Pendapatan</th>
            </tr>
            <tr>
                <td><?= $data_penjualan['total_transaksi'] ?? 0 ?></td>
                <td>Rp <?= number_format($data_penjualan['total_pendapatan'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        </table>

        <h2>Detail Transaksi</h2>
        <table>
            <tr>
                <th>ID Pesanan</th>
                <th>Nama User</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Total Pembayaran</th>
            </tr>
            <?php foreach ($detail_penjualan as $item): ?>
            <tr>
                <td>#<?= $item['order_id'] ?></td>
                <td><?= htmlspecialchars($item['user_name']) ?></td>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>Rp <?= number_format($item['total_amount'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div id="produk">
        <h2>Manajemen Produk</h2>
        <a href="tambah_produk.php" class="btn">+ Tambah Produk</a>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                <td><?= $product['stock'] ?></td>
                <td>
                    <a href="edit_produk.php?id=<?= $product['id'] ?>" class="btn">Edit</a>
                    <a href="hapus_produk.php?id=<?= $product['id'] ?>" class="btn" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<script>
    function showSection(id) {
        document.getElementById('produk').style.display = id === 'produk' ? 'block' : 'none';
        document.getElementById('laporan').style.display = id === 'laporan' ? 'block' : 'none';
    }
</script>
</body>
</html>
