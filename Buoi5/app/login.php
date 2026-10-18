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
        $stmt = $conn->prepare(
            'SELECT id, username, password FROM users WHERE username = ?'
        );
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: product_list.php');
            exit;
        }

        $error = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
    }
}
?>

<?php include __DIR__ . '/view/header.php'; ?>

<h2>Đăng nhập</h2>

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

    <button type="submit">Đăng nhập</button>
</form>

<p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>

<?php include __DIR__ . '/view/footer.php'; ?>