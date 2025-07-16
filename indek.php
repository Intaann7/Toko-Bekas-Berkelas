<?php
require_once 'database.php';
session_start();

$name = $_SESSION['user']['name'] ?? null;
$role = $_SESSION['user']['role'] ?? null;

// Ambil produk unggulan (6 produk terbaru)
$stmt = $pdo->query("SELECT * FROM products WHERE status = 'active' ORDER BY created_at DESC LIMIT 6");
$products = $stmt->fetchAll();

// Ambil kategori (untuk footer)
$catStmt = $pdo->query("SELECT * FROM categories LIMIT 4");
$categories = $catStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bekas Berkelas - Beranda</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            text-align: center;
            color: #00ffff;
        }

        .top-bar {
            text-align: center;
            padding: 20px;
        }

        .top-bar a {
            color: #00ffff;
            text-decoration: none;
            margin: 0 10px;
        }

        .top-bar a:hover {
            text-decoration: underline;
        }

        .produk-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .produk-card {
            background-color: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,255,255,0.1);
            padding: 15px;
            width: 220px;
            text-align: center;
            transition: 0.3s;
        }

        .produk-card:hover {
            box-shadow: 0 0 15px rgba(0,255,255,0.2);
        }

        .produk-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 5px;
        }

        .produk-card h3 {
            color: #00ffff;
            margin: 10px 0 5px;
        }

        .produk-card p {
            margin: 5px 0;
        }

        .produk-card a {
            display: inline-block;
            background-color: #00ffff;
            color: #000;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }

        /* Statistik */
        .stats-section {
            background-color: #1b1b1b;
            padding: 40px 20px;
            text-align: center;
        }

        .stat-item {
            margin-bottom: 20px;
        }

        .stat-number {
            font-size: 2rem;
            color: #00ffff;
            font-weight: bold;
        }

        /* Tentang */
        .about-section {
            padding: 40px 20px;
            max-width: 900px;
            margin: auto;
        }

        .about-section h2 {
            text-align: left;
        }

        .about-section p {
            margin-bottom: 15px;
        }

        .about-icon {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .about-icon i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        /* Footer */
        footer {
            background-color: #1e1e1e;
            color: #e0e0e0;
            padding: 40px 20px;
            font-size: 14px;
        }

        footer a {
            color: #ccc;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .social-links a {
            color: #00ffff;
            margin-right: 10px;
            font-size: 18px;
        }

        hr {
            border-color: #444;
        }
    </style>
</head>
<body>

    <h1>Bekas Berkelas</h1>

    <div class="top-bar">
        <?php if ($name): ?>
            <p>Halo, <strong><?= htmlspecialchars($name) ?></strong>!</p>
        <?php endif; ?>
        <a href="products.php">Katalog</a> |
        <a href="cart.php">Keranjang</a>
        <?php if (!$name): ?>
            | <a href="login.php">Login</a>
        <?php elseif ($role === 'admin'): ?>
            | <a href="dashboard.php">Dashboard</a> | <a href="logout.php">Logout</a>
        <?php else: ?>
            | <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>

    <h2>Produk Unggulan</h2>
    <div class="produk-grid">
        <?php if ($products): ?>
            <?php foreach ($products as $product): ?>
                <div class="produk-card">
                    <img src="assets/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p>Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                    <a href="product_detail.php?id=<?= $product['id'] ?>">Lihat</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Belum ada produk untuk ditampilkan.</p>
        <?php endif; ?>
    </div>

    <!-- Statistik -->
    <section class="stats-section">
        <h2>Statistik Kami</h2>
        <div class="produk-grid">
            <div class="stat-item">
                <div class="stat-number">500+</div>
                <div>Produk Terjual</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">200+</div>
                <div>Pelanggan Puas</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div>Produk Unik</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100%</div>
                <div>Ramah Lingkungan</div>
            </div>
        </div>
    </section>

    <!-- Tentang -->
    <section class="about-section">
        <h2>Tentang Bekas Berkelas</h2>
        <p>Kami adalah platform yang mengkhususkan diri dalam transformasi barang bekas menjadi produk berkelas tinggi yang cocok untuk gaya hidup mahasiswa modern.</p>
        <p>Setiap produk di Bekas Berkelas telah melalui proses upcycling yang cermat, menghasilkan barang-barang unik yang tidak hanya indah dipandang, tetapi juga ramah lingkungan dan terjangkau.</p>
        <div class="about-icon"><i class="fas fa-leaf text-success"></i>Ramah Lingkungan</div>
        <div class="about-icon"><i class="fas fa-star text-warning"></i>Kualitas Terjamin</div>
        <div class="about-icon"><i class="fas fa-wallet text-info"></i>Harga Terjangkau</div>
        <div class="about-icon"><i class="fas fa-shipping-fast text-primary"></i>Pengiriman Cepat</div>
    </section>

    <!-- Footer -->
    <footer>
        <div>
            <h3><i class="fas fa-recycle"></i> Bekas Berkelas</h3>
            <p>Transformasi barang bekas menjadi karya berkelas untuk gaya hidup mahasiswa yang sustainable dan stylish.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <hr>
        <div>
            <strong>Menu:</strong><br>
            <a href="index.php">Beranda</a> | <a href="products.php">Produk</a> | <a href="#about">Tentang</a>
        </div>
        <div>
            <strong>Kategori:</strong><br>
            <?php foreach ($categories as $category): ?>
                <a href="products.php?category=<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></a><br>
            <?php endforeach; ?>
        </div>
        <div>
            <strong>Kontak:</strong><br>
            <p><i class="fas fa-envelope"></i> info@bekasberkelas.com</p>
            <p><i class="fas fa-phone"></i> +62 857-6301-5103</p>
            <p><i class="fas fa-map-marker-alt"></i> Padang, Sumatera Barat</p>
        </div>
        <p style="text-align:center; margin-top:20px;">&copy; 2024 Bekas Berkelas. Semua hak cipta dilindungi.</p>
    </footer>

</body>
</html>
