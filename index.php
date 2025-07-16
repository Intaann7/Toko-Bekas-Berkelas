<?php
require_once 'database.php';
session_start();

// Ambil produk dari database
$stmt = $pdo->query("SELECT * FROM products WHERE status = 'active'");
$products = $stmt->fetchAll();

$name = $_SESSION['user']['name'] ?? null;
$role = $_SESSION['user']['role'] ?? null;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bekas Berkelas - Katalog Produk</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            text-align: center;
            color: #00ffff;
        }

        .top-bar {
            text-align: center;
            margin-bottom: 20px;
        }

        .top-bar a {
            color: #00ffff;
            text-decoration: none;
            margin: 0 10px;
        }

        .top-bar a:hover {
            text-decoration: underline;
        }

        form {
            text-align: center;
            margin: 20px 0;
        }

        input[type="text"] {
            padding: 8px;
            width: 200px;
            border: none;
            border-radius: 5px;
        }

        button {
            padding: 8px 12px;
            background-color: #00bcd4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0097a7;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background-color: #1e1e1e;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,255,255,0.05);
            padding: 15px;
            text-align: center;
            transition: 0.3s;
        }

        .card:hover {
            box-shadow: 0 0 15px rgba(0,255,255,0.1);
        }

        .card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .card h3 {
            margin: 10px 0 5px;
            color: #00ffff;
        }

        .card p {
            margin: 5px 0;
        }

        .card form {
            margin-top: 10px;
        }
        .hero-header {
            background-image: url('assets/latar3.jpeg'); /* Ganti dengan path gambar Anda */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 80px 20px;
            text-align: center;
            color: #00ffff;
            font-size: 3rem;
            font-weight: bold;
            border-radius: 10px;
            margin: 30px auto;
            box-shadow: 0 0 20px rgba(0,255,255,0.1);
            max-width: 900px;         /* Membatasi lebar */
            width: 100%;              /* Responsif */
        }
        .hero-header h1 {
            font-size: 4rem;
        }
        .about-section {
            padding: 60px 0;
            background-image: url('assets/latar.jpeg'); /* Ganti sesuai nama file */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #e0e0e0;
            position: relative;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7); /* Overlay gelap agar teks tetap terbaca */
            z-index: 1;
        }

        .about-section .container {
            position: relative;
            z-index: 2;
        }
        .about-section {
            position: relative;
            padding: 80px 20px;
            background-image: url('assets/latar.jpeg');
            background-size: cover;
            background-position: center;
            color: #e0e0e0;
            overflow: hidden;
        }

        .about-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(18, 18, 18, 0.4); /* lebih cerah */
            z-index: 1;
        }



        .about-section .container {
            position: relative;
            z-index: 2;
            max-width: 1200px;
            margin: auto;
        }

        .about-content {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            align-items: center;
            justify-content: space-between;
        }

        .about-text {
            flex: 1 1 55%;
        }

        .about-text h2 {
            font-size: 2.5rem;
            color: #00ffff;
            margin-bottom: 20px;
        }

        .about-text h2 span {
            color: #ffffff;
        }

        .about-text p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .about-text ul {
            list-style: none;
            padding-left: 0;
        }

        .about-text ul li {
            margin-bottom: 10px;
            font-size: 1rem;
            background: rgba(0, 255, 255, 0.1);
            padding: 10px;
            border-left: 3px solid #00ffff;
            border-radius: 5px;
        }

        .about-visual {
            flex: 1 1 40%;
            text-align: center;
        }

        .about-box {
            background-color: #1e1e1e;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,255,255,0.1);
            transition: transform 0.3s;
        }

        .about-box:hover {
            transform: translateY(-5px);
        }

        .about-icon {
            font-size: 4rem;
            color: #00ffff;
            margin-bottom: 15px;
        }


    </style>
</head>
<body>
    <div class="hero-header">
        <h1>Bekas Berkelas</h1>
    </div>


    <div class="top-bar">
    <?php if ($name): ?>
        <p>Halo, <strong><?= htmlspecialchars($name) ?></strong>!</p>
    <?php endif; ?>

     
    <a href="#about">Tentang</a>|
    <a href="cart.php">Keranjang</a>
    

    <?php if (!$name): ?>
        | <a href="login.php">Login</a>

    <?php elseif ($role === 'admin'): ?>
        | <a href="dashboard.php">Dashboard</a>
        | <a href="logout.php">Logout</a>

    <?php elseif ($role === 'user'): ?>
        | <a href="logout.php">Logout</a>
    <?php endif; ?>
</div>


    <h2>Katalog Produk</h2>

    <form action="search.php" method="get">
        <input type="text" name="q" placeholder="Cari produk...">
        <button type="submit">Cari</button>
    </form>

    <div class="grid">
        <?php foreach ($products as $product): ?>
            <div class="card">
                <img src="assets/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                <p><strong>Rp <?= number_format($product['price'], 0, ',', '.') ?></strong></p>
                <form action="cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" name="add_to_cart">Tambah ke Keranjang</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
        </div> <!-- akhir grid -->

    <!-- Stats Section -->
    <section style="padding: 60px 0; background-color: #1a1a1a; color: #00ffff; text-align: center;">
        <div class="container">
            <h2>Statistik Kami</h2>
            <div style="display: flex; flex-wrap: wrap; justify-content: center; margin-top: 40px;">
                <div style="flex: 1 1 200px; margin: 10px;">
                    <h3>500+</h3>
                    <p>Produk Terjual</p>
                </div>
                <div style="flex: 1 1 200px; margin: 10px;">
                    <h3>200+</h3>
                    <p>Pelanggan Puas</p>
                </div>
                <div style="flex: 1 1 200px; margin: 10px;">
                    <h3>50+</h3>
                    <p>Produk Unik</p>
                </div>
                <div style="flex: 1 1 200px; margin: 10px;">
                    <h3>100%</h3>
                    <p>Ramah Lingkungan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
    <div class="about-overlay"></div>
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2>Tentang <span>Bekas Berkelas</span></h2>
                <p>
                    <strong>Bekas Berkelas</strong> adalah platform yang menyulap barang bekas menjadi produk unik berkualitas tinggi. Didesain khusus untuk mahasiswa modern yang peduli lingkungan, bergaya, dan cerdas berbelanja.
                </p>
                <ul>
                    <li>♻️ Ramah Lingkungan & Upcycled</li>
                    <li>✔️ Kualitas Terjamin</li>
                    <li>💸 Harga Terjangkau</li>
                    <li>⚡ Pengiriman Cepat</li>
                </ul>
            </div>
            <div class="about-visual">
                <div class="about-box">
                    <div class="about-icon">🌿</div>
                    <h3>Cinta Lingkungan</h3>
                    <p>Setiap produk membantu mengurangi limbah dan mendukung masa depan yang lebih hijau.</p>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Footer -->
    <footer style="background-color: #1a1a1a; color: #aaa; padding: 40px 20px;">
        <div class="container" style="max-width: 1200px; margin: auto;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                <div style="flex: 1 1 250px; margin-bottom: 20px;">
                    <h3 style="color: #00ffff;">Bekas Berkelas</h3>
                    <p>Transformasi barang bekas menjadi karya berkelas untuk gaya hidup mahasiswa yang sustainable dan stylish.</p>
                    <div>
                        <a href="#" style="margin-right:10px; color:#00ffff;">Instagram</a>
                        <a href="#" style="margin-right:10px; color:#00ffff;">Facebook</a>
                        <a href="#" style="margin-right:10px; color:#00ffff;">Twitter</a>
                        <a href="#" style="color:#00ffff;">WhatsApp</a>
                    </div>
                </div>
                <div style="flex: 1 1 150px; margin-bottom: 20px;">
                    <h4 style="color: #00ffff;">Menu</h4>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="index.php" style="color:#aaa;">Beranda</a></li>
                        <li><a href="index.php" style="color:#aaa;">Produk</a></li>
                        <li><a href="#about" style="color:#aaa;">Tentang</a></li>
                    </ul>
                </div>
                <div style="flex: 1 1 150px; margin-bottom: 20px;">
                    <h4 style="color: #00ffff;">Kontak</h4>
                    <p>Email: info@bekasberkelas.com</p>
                    <p>Telp: +62 857-6301-5103</p>
                    <p>Alamat: Padang, Sumatera Barat</p>
                </div>
            </div>
            <hr style="border-color: #444;">
            <p style="text-align: center;">&copy; 2025 Bekas Berkelas. Semua hak cipta dilindungi.</p>
        </div>
    </footer>

</body>
</html>
