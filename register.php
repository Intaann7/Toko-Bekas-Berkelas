<?php
require_once 'database.php';

$name = $email = $password = $confirm = $role = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];
    $role     = $_POST['role'];

    // Validasi
    if ($password !== $confirm) {
        $error = "Password tidak cocok.";
    } else {
        // Cek apakah email sudah digunakan
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Email sudah terdaftar.";
        } else {
            // Simpan user
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $hash, $role]);
            header("Location: login.php?success=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi - Bekas Berkelas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #444, #888);
            color: #fff;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-box {
            background-color: #2c2c2c;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            width: 320px;
        }

        .register-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .register-box form input,
        .register-box form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
        }

        .register-box form button {
            width: 100%;
            background-color: #00ffff;
            border: none;
            padding: 10px;
            color: black;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        .register-box form button:hover {
            background-color: #00dddd;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }

        .back-link {
            text-align: center;
            margin-bottom: 15px;
        }

        .back-link a {
            color: #00ffff;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Registrasi Akun</h2>
        <div class="back-link"><a href="login.php">← Kembali ke Login</a></div>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="name" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm" placeholder="Ulangi Password" required>
            <select name="role" required>
                <option value="">Pilih Peran</option>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Daftar</button>
        </form>
    </div>
</body>
</html>
