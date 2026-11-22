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
    respond(false, "Lỗi kết nối database: " . $e->getMessage());
}

function respond($success, $message, $data = null, $extra = []) {
    $result = [
        "success" => $success,
        "message" => $message,
        "data" => $data
    ];
    echo json_encode(array_merge($result, $extra), JSON_UNESCAPED_UNICODE);
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

$method = $_SERVER["REQUEST_METHOD"];
$input = json_decode(file_get_contents("php://input"), true) ?? [];

if ($method === "GET") {
    $page = isset($_GET["page"]) ? $_GET["page"] : "1";
    $limit = isset($_GET["limit"]) ? $_GET["limit"] : "5";

    if (!preg_match('/^[1-9][0-9]*$/', (string)$page)) {
        respond(false, "Tham số page phải là số nguyên dương");
    }
    if (!preg_match('/^[1-9][0-9]*$/', (string)$limit) || (int)$limit !== 5) {
        respond(false, "Tham số limit phải bằng 5");
    }

    $page = (int)$page;
    $limit = (int)$limit;

    $total = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalPages = max(1, (int)ceil($total / $limit));
    $actualPage = min($page, $totalPages);
    $offset = ($actualPage - 1) * $limit;

    $stmt = $pdo->prepare("SELECT id, name, price, stock, image FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    respond(true, "Lấy danh sách thành công", $products, [
        "pagination" => [
            "page" => $actualPage,
            "limit" => $limit,
            "total" => $total,
            "total_pages" => $totalPages
        ]
    ]);
}

if ($method === "POST") {
    $name = $input["name"] ?? "";
    $price = $input["price"] ?? "";
    $stock = $input["stock"] ?? "";

    $error = validateProduct($name, $price, $stock);
    if ($error) {
        respond(false, $error);
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, price, stock) VALUES (:name, :price, :stock)");
    $stmt->execute([
        ":name" => trim($name),
        ":price" => (int)$price,
        ":stock" => (int)$stock
    ]);

    respond(true, "Thêm sản phẩm thành công", [
        "id" => (int)$pdo->lastInsertId()
    ]);
}

if ($method === "PUT") {
    $id = $_GET["id"] ?? null;
    if ($id === null || !preg_match('/^[1-9][0-9]*$/', (string)$id)) {
        respond(false, "Thiếu hoặc sai id sản phẩm");
    }

    $check = $pdo->prepare("SELECT id FROM products WHERE id = :id");
    $check->execute([":id" => $id]);
    if (!$check->fetch()) {
        respond(false, "Sản phẩm không tồn tại");
    }

    $name = $input["name"] ?? "";
    $price = $input["price"] ?? "";
    $stock = $input["stock"] ?? "";
    $error = validateProduct($name, $price, $stock);
    if ($error) {
        respond(false, $error);
    }

    $stmt = $pdo->prepare("UPDATE products SET name = :name, price = :price, stock = :stock WHERE id = :id");
    $stmt->execute([
        ":name" => trim($name),
        ":price" => (int)$price,
        ":stock" => (int)$stock,
        ":id" => (int)$id
    ]);

    respond(true, "Cập nhật sản phẩm thành công", ["id" => (int)$id]);
}

if ($method === "DELETE") {
    $id = $_GET["id"] ?? null;
    if ($id === null || !preg_match('/^[1-9][0-9]*$/', (string)$id)) {
        respond(false, "Thiếu hoặc sai id sản phẩm");
    }

    $check = $pdo->prepare("SELECT image FROM products WHERE id = :id");
    $check->execute([":id" => $id]);
    $product = $check->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        respond(false, "Sản phẩm không tồn tại");
    }

    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute([":id" => (int)$id]);

    if (!empty($product["image"])) {
        $file = __DIR__ . "/../" . $product["image"];
        if (is_file($file)) {
            unlink($file);
        }
    }

    respond(true, "Xóa sản phẩm thành công");
}

respond(false, "Phương thức không hợp lệ");
