<?php
require_once __DIR__ . '/model/product.php';
include __DIR__ . '/view/header.php';

$products = getAllProducts();
?>

<h2>Danh sách sản phẩm</h2>
<a href="product_add.php">Thêm sản phẩm mới</a>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá (VNĐ)</th>
        <th>Số lượng</th>
        <th>Chức năng</th>
    </tr>
    <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo htmlspecialchars($product['id']); ?></td>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td><?php echo htmlspecialchars($product['price']); ?></td>
            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
            <td>
                <a href="product_edit.php?id=<?php echo $product['id']; ?>">Sửa</a> |
                <a href="product_delete.php?id=<?php echo $product['id']; ?>">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include __DIR__ . '/view/footer.php'; ?>