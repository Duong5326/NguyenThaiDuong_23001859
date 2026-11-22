<?php
header("Content-Type: application/json; charset=utf-8");

$host = "localhost";
$dbname = "shop_db";
$dbuser = "root";
$dbpass = "";

function uploadRespond($success, $message, $data = null) {
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    uploadRespond(false, "Phương thức không hợp lệ");
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    uploadRespond(false, "Lỗi kết nối database: " . $e->getMessage());
}

$productId = $_POST["product_id"] ?? "";
if (!preg_match('/^[1-9][0-9]*$/', (string)$productId)) {
    uploadRespond(false, "Thiếu hoặc sai mã sản phẩm");
}

$check = $pdo->prepare("SELECT image FROM products WHERE id = :id");
$check->execute([":id" => (int)$productId]);
$product = $check->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    uploadRespond(false, "Sản phẩm không tồn tại");
}

if (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
    uploadRespond(false, "Chưa chọn file ảnh hoặc file bị lỗi");
}

$file = $_FILES["image"];
if (!isset($file["size"]) || $file["size"] <= 0) {
    uploadRespond(false, "File ảnh không hợp lệ");
}
if ($file["size"] > 2 * 1024 * 1024) {
    uploadRespond(false, "Dung lượng ảnh không được vượt quá 2 MB");
}

$extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
if (!in_array($extension, ["jpg", "jpeg", "png"], true)) {
    uploadRespond(false, "Chỉ chấp nhận file JPG, JPEG hoặc PNG");
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$realMime = $finfo->file($file["tmp_name"]);
$allowedMimes = ["image/jpeg", "image/png"];
if (!in_array($realMime, $allowedMimes, true)) {
    uploadRespond(false, "File không phải ảnh JPG hoặc PNG hợp lệ");
}

$imageInfo = getimagesize($file["tmp_name"]);
if ($imageInfo === false) {
    uploadRespond(false, "File không phải ảnh hợp lệ");
}

$uploadDir = __DIR__ . "/../uploads";
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
    uploadRespond(false, "Không thể tạo thư mục uploads");
}

$storedExtension = $realMime === "image/png" ? "png" : "jpg";
$fileName = "product_" . (int)$productId . "_" . bin2hex(random_bytes(8)) . "." . $storedExtension;
$destination = $uploadDir . "/" . $fileName;

if (!move_uploaded_file($file["tmp_name"], $destination)) {
    uploadRespond(false, "Không thể lưu file ảnh");
}

$relativePath = "uploads/" . $fileName;
$update = $pdo->prepare("UPDATE products SET image = :image WHERE id = :id");
if (!$update->execute([":image" => $relativePath, ":id" => (int)$productId])) {
    unlink($destination);
    uploadRespond(false, "Không thể cập nhật ảnh sản phẩm");
}

if (!empty($product["image"])) {
    $oldFile = __DIR__ . "/../" . $product["image"];
    if (is_file($oldFile) && realpath($oldFile) !== realpath($destination)) {
        unlink($oldFile);
    }
}

uploadRespond(true, "Upload ảnh thành công", ["image" => $relativePath]);
