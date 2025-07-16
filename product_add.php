<?php
require_once 'database.php';
require_once 'session.php';
check_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $image = $_FILES['image']['name'];
    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], "assets/$image");
    }
    $stmt = $pdo->prepare("INSERT INTO products (name, price, image, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $price, $image, $status]);
    header("Location: dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Tambah Produk</h2>
    <a href="dashboard.php">← Kembali</a>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Nama Produk" required><br>
        <input type="number" name="price" placeholder="Harga" required><br>
        <input type="file" name="image" required><br>
        <select name="status">
            <option value="active">Aktif</option>
            <option value="inactive">Nonaktif</option>
        </select><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>