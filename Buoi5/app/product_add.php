<?php 
require_once __DIR__ . '/model/product.php';
require_once __DIR__ . '/common/auth.php';
requireLogin();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';

    if (empty($name)) {
        $error = 'Tên sản phẩm không được rỗng.';
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = 'Giá phải lớn hơn 0.';
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $error = 'Số lượng phải lớn hơn hoặc bằng 0.';
    } else {
        if (addProduct($name, $price, $quantity)) {
            header('Location: product_list.php');
            exit();
        } else {
            $error = 'Lỗi khi thêm sản phẩm. Vui lòng thử lại.';
        }
    }
}

include __DIR__ . '/view/header.php';
?>

<h2>Thêm sản phẩm mới</h2>
<?php if (!empty($error)): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<form method="POST" action="">
    Tên sản phẩm: <input type="text" name="name"><br><br>
    Giá: <input type="text" name="price"><br><br>
    Số lượng: <input type="text" name="quantity"><br><br>
    <button type="submit">Thêm</button>
</form>

<?php include __DIR__ . '/view/footer.php'; ?>