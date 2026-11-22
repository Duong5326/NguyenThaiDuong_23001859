CREATE DATABASE IF NOT EXISTS shop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE shop_db;

CREATE TABLE IF NOT EXISTS products (
  id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  price INT(11) NOT NULL,
  stock INT(11) NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT chk_price CHECK (price > 0),
  CONSTRAINT chk_stock CHECK (stock >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE products ADD COLUMN IF NOT EXISTS image VARCHAR(255) DEFAULT NULL;

TRUNCATE TABLE products;

INSERT INTO products (name, price, stock, image) VALUES
('Bút bi Thiên Long', 5000, 20, NULL),
('Vở ô ly Campus', 12000, 15, NULL),
('Thước kẻ 30cm', 8000, 0, NULL),
('Tẩy chì cao su', 4000, 25, NULL),
('Bút chì 2B', 6000, 18, NULL),
('Gôm Deli', 7000, 12, NULL),
('Kéo văn phòng', 35000, 8, NULL),
('Bìa hồ sơ A4', 15000, 30, NULL),
('Hộp bút nhựa', 25000, 10, NULL),
('Sổ tay lò xo', 28000, 14, NULL),
('Giấy note 3M', 22000, 22, NULL),
('Balo học sinh', 180000, 5, NULL);
