<?php
require_once 'database.php';
require_once 'session.php';
check_login();
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);
header("Location: dashboard.php");