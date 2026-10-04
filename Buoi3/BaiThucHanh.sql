-- BÀI 1
-- Tạo database và bảng
CREATE DATABASE IF NOT EXISTS shopping_cart;
USE shopping_cart;

CREATE TABLE cart_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
);

-- 1. Thêm sản phẩm
INSERT INTO cart_items (name, price, quantity) VALUES
('Book', 3.99, 10),
('Pen', 0.99, 20),
('Laptop', 999.99, 1),
('Shoes', 59.99, 2),
('Balo', 39.99, 1);

-- 2. Hiển thị toàn bộ sản phẩm
SELECT * FROM cart_items;

-- 3. Hiển thị sản phẩm có giá lớn hơn 10
SELECT * FROM cart_items WHERE price > 10;

-- 4. Hiển thị sản phẩm có số lượng lớn hơn 5
SELECT * FROM cart_items WHERE quantity > 5;

-- 5. Sắp xếp sản phẩm theo giá giảm dần
SELECT * FROM cart_items ORDER BY price DESC;

-- 6. Cập nhật giá của một sản phẩm
UPDATE cart_items SET price = 5.99 WHERE id = 1;

-- 7. Cập nhật số lượng của một sản phẩm
UPDATE cart_items SET quantity = 7 WHERE id = 3;

-- 8. Xóa một sản phẩm
DELETE FROM cart_items WHERE id = 4;

-- 9. Hiển thị tên, giá, số lượng và thành tiền
SELECT name, price, quantity, (price * quantity) AS thanh_tien FROM cart_items;

-- 10. Tính tổng tiền của toàn bộ giỏ hàng
SELECT SUM(price * quantity) AS tong_tien_gio_hang FROM cart_items;


-- BÀI 2
-- Tạo bảng movies
CREATE TABLE movies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);

-- 1 Thêm ít nhất 5 bộ phim
INSERT INTO movies (title, price, total_seats, available_seats) VALUES
('Avengers', 100000, 100, 40),
('Avatar', 120000, 80, 60),
('Avatar 2', 120000, 120, 30),
('Dune', 105000, 90, 10),
('Batman', 90000, 120, 80);

-- 2 Hiển thị toàn bộ danh sách phim
SELECT * FROM movies;

-- 3 Phim có giá vé > 100000
SELECT * FROM movies WHERE price > 100000;

-- 4 Phim còn nhiều hơn 50 ghế
SELECT * FROM movies WHERE available_seats > 50;

-- 5 Sắp xếp phim theo giá vé giảm dần
SELECT * FROM movies ORDER BY price DESC;

-- 6 Cập nhật số ghế còn lại của một phim
UPDATE movies SET available_seats = 35 WHERE id = 1;

-- 7 Xóa một phim
DELETE FROM movies WHERE id = 3;

-- 8 Hiển thị số vé đã bán của từng phim
SELECT title, (total_seats - available_seats) AS ve_da_ban FROM movies;

-- 9 Tính doanh thu của từng phim
SELECT title, ((total_seats - available_seats) * price) AS doanh_thu FROM movies;

-- 10 Tính tổng doanh thu tất cả các phim
SELECT SUM((total_seats - available_seats) * price) AS tong_doanh_thu FROM movies;

-- 11 Tìm phim có số vé bán ra nhiều nhất
SELECT title, (total_seats - available_seats) AS ve_da_ban 
FROM movies 
ORDER BY ve_da_ban DESC 
LIMIT 1;