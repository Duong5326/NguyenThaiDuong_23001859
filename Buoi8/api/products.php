<?php
header("Content-Type: application/json; charset=utf-8");

$host = "localhost";
$dbname = "shop_db";
$dbuser = "root";
$dbpass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Lỗi kết nối database: " . $e->getMessage()
    ]);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];
$input = json_decode(file_get_contents("php://input"), true) ?? [];

function respond($success, $message, $data = null) {
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

function validateProduct($name, $price, $stock) {
    $name = trim($name);
    if (mb_strlen($name) < 1 || mb_strlen($name) > 100) {
        return "Tên sản phẩm phải từ 1 đến 100 ký tự";
    }
    if (!is_numeric($price) || (int)$price <= 0) {
        return "Đơn giá phải là số nguyên lớn hơn 0";
    }
    if (!is_numeric($stock) || (int)$stock < 0) {
        return "Số lượng tồn phải là số nguyên không âm";
    }
    return null;
}

if ($method === "GET") {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    respond(true, "Lấy danh sách thành công", $products);
}

if ($method === "POST") {
    $name = $input["name"] ?? "";
    $price = $input["price"] ?? "";
    $stock = $input["stock"] ?? "";

    $error = validateProduct($name, $price, $stock);
    if ($error) {
        respond(false, $error);
    }

    $sql = "INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":name" => trim($name),
        ":price" => (int)$price,
        ":stock" => (int)$stock
    ]);

    respond(true, "Thêm sản phẩm thành công", [
        "id" => $pdo->lastInsertId()
    ]);
}

if ($method === "PUT") {
    $id = $_GET["id"] ?? null;
    if ($id === null) {
        respond(false, "Thiếu id sản phẩm");
    }

    $name = $input["name"] ?? "";
    $price = $input["price"] ?? "";
    $stock = $input["stock"] ?? "";

    $check = $pdo->prepare("SELECT id FROM products WHERE id = :id");
    $check->execute([":id" => $id]);
    if (!$check->fetch()) {
        respond(false, "Sản phẩm không tồn tại");
    }

    $error = validateProduct($name, $price, $stock);
    if ($error) {
        respond(false, $error);
    }

    $sql = "UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":name" => trim($name),
        ":price" => (int)$price,
        ":stock" => (int)$stock,
        ":id" => $id
    ]);

    respond(true, "Cập nhật sản phẩm thành công");
}

if ($method === "DELETE") {
    $id = $_GET["id"] ?? null;
    if ($id === null) {
        respond(false, "Thiếu id sản phẩm");
    }

    $check = $pdo->prepare("SELECT id FROM products WHERE id = :id");
    $check->execute([":id" => $id]);
    if (!$check->fetch()) {
        respond(false, "Sản phẩm không tồn tại");
    }

    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([":id" => $id]);

    respond(true, "Xóa sản phẩm thành công");
}

respond(false, "Phương thức không hợp lệ");
?>