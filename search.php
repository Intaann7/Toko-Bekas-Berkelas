<?php
require_once 'database.php';
$q = $_GET['q'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? AND status = 'active'");
$stmt->execute(['%' . $q . '%']);
$results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cari Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Hasil Pencarian: "<?= htmlspecialchars($q) ?>"</h2>
    <a href="index.php">← Kembali</a>
    <div class="grid">
        <?php foreach ($results as $product): ?>
            <div class="card">
                <img src="assets/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
                <h3><?= $product['name'] ?></h3>
                <p>Rp <?= number_format($product['price']) ?></p>
                <form action="cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" name="add_to_cart">Tambah ke Keranjang</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>