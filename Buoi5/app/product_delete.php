<?php
require_once __DIR__ . '/model/product.php';
require_once __DIR__ . '/common/auth.php';
requireLogin();

$idInput = $_POST['id'] ?? $_GET['id'] ?? null;
$id = filter_var($idInput, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($id === false || $id === null) {
    die("ID sản phẩm không hợp lệ.");
}

$product = getProductById($id);

if (!$product) {
    die("Sản phẩm không tồn tại.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['confirm'] ?? '') === 'yes') {
        if (deleteProduct($id)) {
            header('Location: product_list.php');
            exit;
        }

        $error = "Không thể xóa sản phẩm. Vui lòng thử lại.";
    } else {
        header('Location: product_list.php');
        exit;
    }
}

include __DIR__ . '/view/header.php';
?>

<h2>Xác nhận xóa sản phẩm</h2>
<p>Bạn có chắc chắn muốn xóa sản phẩm "<strong><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></strong>" không?</p>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
<?php endif; ?>
<form method="POST" action="product_delete.php">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">
    <button type="submit" name="confirm" value="yes">Xác nhận xóa</button>
    <a href="product_list.php">Hủy</a>
</form>

<?php include __DIR__ . '/view/footer.php'; ?>