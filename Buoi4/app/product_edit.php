<?php
require_once __DIR__ . '/model/product.php';
include __DIR__ . '/view/header.php';
$id = $_GET['id'] ?? 0;
$product = getProductById($id);

if (!$product) {
    die("Sản phẩm không tồn tại.");
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');

    if (empty($name)) {
        $error = 'Tên sản phẩm không được rỗng.';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Giá phải lớn hơn 0.';
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $error = 'Số lượng phải lớn hơn hoặc bằng 0.';
    } else {
        updateProduct($id, $name, $price, $quantity);
        header('Location: product_list.php');
        exit;
    }
}
?>

<h2>Sửa sản phẩm</h2>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<form method="POST">
    Tên sản phẩm: <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>"><br><br>
    Giá: <input type="text" name="price" value="<?= $product['price'] ?>"><br><br>
    Số lượng: <input type="text" name="quantity" value="<?= $product['quantity'] ?>"><br><br>
    <button type="submit">Cập nhật</button>
</form>

<?php include __DIR__ . '/view/footer.php'; ?>