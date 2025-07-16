<?php
require_once 'database.php';
require_once 'session.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];
$message = '';

// Ambil data produk
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Produk tidak ditemukan.");
}

// Proses jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ?, description = ? WHERE id = ?");
    if ($stmt->execute([$name, $price, $stock, $description, $id])) {
        $message = "Produk berhasil diperbarui!";
    } else {
        $message = "Gagal memperbarui produk.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
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
    <h2>Edit Produk</h2>
    <?php if ($message): ?>
        <div class="msg"><?= $message ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
        <input type="number" name="price" value="<?= $product['price'] ?>" required>
        <input type="number" name="stock" value="<?= $product['stock'] ?>" required>
        <textarea name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
        <button type="submit">Simpan Perubahan</button>
    </form>
    <a href="dashboard.php">← Kembali ke Dashboard</a>
</div>
</body>
</html>
