<?php
require_once 'database.php';
require_once 'session.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $user_id = $_SESSION['user']['id'];
    $cart = $_SESSION['cart'] ?? [];

    if (empty($cart)) {
        header("Location: cart.php");
        exit;
    }

    // Ambil detail produk dari cart
    $product_ids = implode(',', array_keys($cart));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($product_ids)");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

   // Hitung total dan periksa stok
    $total = 0;
    foreach ($products as $product) {
        $jumlah = $cart[$product['id']];

        if ($jumlah > $product['stock']) {
            $_SESSION['error_message'] = "Stok produk '{$product['name']}' tidak mencukupi atau habis. Silakan periksa keranjang Anda.";
            header("Location: cart.php");
            exit;
        }

        $total += $product['price'] * $jumlah;
    }   


    // Simpan ke tabel orders
    $insertOrder = $pdo->prepare("INSERT INTO orders (user_id, nama, alamat, telepon, total_amount) VALUES (?, ?, ?, ?, ?)");
    $insertOrder->execute([$user_id, $nama, $alamat, $telepon, $total]);
    $order_id = $pdo->lastInsertId();

    // Simpan item satu per satu ke order_items
    $insertItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $updateStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

    foreach ($products as $product) {
        $jumlah = $cart[$product['id']];
        $insertItem->execute([$order_id, $product['id'], $jumlah, $product['price']]);

        // Kurangi stok
        $updateStock->execute([$jumlah, $product['id']]);
    }


    // Kosongkan keranjang
    unset($_SESSION['cart']);

    // Redirect ke halaman sukses
    header("Location: order_success.php?order_id=" . $order_id);
    exit;
}
?>
