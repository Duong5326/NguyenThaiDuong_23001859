<?php
require_once __DIR__ . '/common/auth.php';
require_once __DIR__ . '/common/dbConnect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập tên đăng nhập và mật khẩu.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);

        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $error = 'Tên đăng nhập đã tồn tại.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
                'INSERT INTO users (username, password) VALUES (?, ?)'
            );
            $stmt->execute([$username, $hash]);

            header('Location: login.php');
            exit;
        }
    }
}
?>

<?php include __DIR__ . '/view/header.php'; ?>

<h2>Đăng ký</h2>

<?php if ($error !== ''): ?>
    <p style="color: red;">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="POST" action="">
    <label for="username">Tên đăng nhập:</label>
    <input
        type="text"
        id="username"
        name="username"
        value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        required
    >
    <br><br>

    <label for="password">Mật khẩu:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <button type="submit">Đăng ký</button>
</form>

<p>Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>

<?php include __DIR__ . '/view/footer.php'; ?>
