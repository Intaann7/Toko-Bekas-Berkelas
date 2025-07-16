<?php
require_once 'database.php';
require_once 'session.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, description) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $price, $stock, $description])) {
        $message = "Produk berhasil ditambahkan!";
    } else {
        $message = "Gagal menambahkan produk.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <style>
        body {
            background-color: #1f1f1f;
            color: #e0e0e0;
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .form-container {
            background-color: #2b2b2b;
            padding: 25px;
            max-width: 500px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(255,255,255,0.05);
        }
        h2 {
            text-align: center;
            color: #fff;
        }
        form input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #444;
            color: #fff;
            border: none;
            border-radius: 5px;
        }
        button {
            background-color: #555;
            color: #fff;
            padding: 10px 20px;
            margin-top: 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #777;
        }
        .msg {
            text-align: center;
            margin-top: 15px;
        }
        a {
            color: #ccc;
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Tambah Produk Baru</h2>
    <?php if ($message): ?>
        <div class="msg"><?= $message ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="name" placeholder="Nama Produk" required>
        <input type="number" name="price" placeholder="Harga" required>
        <input type="number" name="stock" placeholder="Stok" required>
        <textarea name="description" placeholder="Deskripsi Produk" rows="4"></textarea>
        <button type="submit">Simpan Produk</button>
    </form>
    <a href="dashboard.php">← Kembali ke Dashboard</a>
</div>
</body>
</html>
