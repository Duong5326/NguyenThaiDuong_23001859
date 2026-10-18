<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý giỏ hàng</title>
</head>
<body>
    <h1>Hệ thống Quản lý Giỏ hàng</h1>
    <hr>
<?php if (!empty($_SESSION['username'])): ?>
    <p>
        Xin chào, <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?> |
        <a href="logout.php">Đăng xuất</a>
    </p>
<?php endif; ?>