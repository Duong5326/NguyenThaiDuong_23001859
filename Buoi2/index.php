<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bài Tập Thực Hành Buổi 2</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        .nav { margin-bottom: 20px; }
        .nav a { padding: 10px 15px; background: #007BFF; color: #fff; text-decoration: none; border-radius: 4px; margin-right: 10px;}
        .nav a:hover { background: #0056b3; }
        .container { border: 1px solid #ccc; padding: 20px; border-radius: 5px; }
        h1, h2 { color: #333; }
        h1 { border-bottom: 2px solid #007BFF; padding-bottom: 5px; }
    </style>
</head>
<body>

    <div class="nav">
        <a href="index.php?bai=1">Bài 1 - Giỏ Hàng</a>
        <a href="index.php?bai=2">Bài 2 - Quản Lý Phim</a>
    </div>

    <div class="container">
        <?php
        $bai = isset($_GET['bai']) ? $_GET['bai'] : '';

        if ($bai == '1') {
            require_once 'shoppingcart.php';

            $cart = new ShoppingCart();

            $item1 = new CartItem("Book", 3.99, 2);
            $item2 = new CartItem("Pen", 0.99, 20);
            $item3 = new CartItem("Laptop", 999.99, 1);
            $item4 = new CartItem("Shoes", 59.99, 1);

            $cart->addItem($item1);
            $cart->addItem($item2);
            $cart->addItem($item3);
            $cart->addItem($item4);
            ?>
            
            <h1>Shopping Cart</h1>
            <h2>Danh sách sản phẩm</h2>
            
            <?php
            $cart->displayCart(false);
            $total = $cart->calculateTotal();
            echo "<h3>Total Amount: $" . number_format($total, 2) . "</h3>";
            ?>

            <hr>

            <?php
            $cart->removeItem("Pen");
            ?>

            <h2>Giỏ hàng sau khi xóa Pen</h2>
            
            <?php
            $cart->displayCart(false);
            $newTotal = $cart->calculateTotal();
            echo "<h3>New Total Amount: $" . number_format($newTotal, 2) . "</h3>";

        } elseif ($bai == '2') {
            require_once 'movie.php';

            echo "<h1>Quản Lý Vé Xem Phim</h1>";

            $movies = [
                new Movie(1, "Avengers", 100000, 100),
                new Movie(2, "Avatar", 120000, 80),
                new Movie(3, "Batman", 90000, 120)
            ];

            echo "<h2>Đặt vé cho phim Avengers.</h2>";
            $movies[0]->bookTicket(50);
            echo "<h2>Đặt vé cho phim Avatar.</h2>";
            $movies[1]->bookTicket(60);
            echo "<h2>Hủy một số vé đã đặt của phim Avengers. </h2>";
            $movies[0]->cancelTicket(20);

            echo "<h2>Hiển thị thông tin của tất cả các phim.</h2>";
            foreach ($movies as $movie) {
                $movie->displayInfo();
            }

            echo "<h2>Tổng Doanh Thu</h2>";
            echo "<p>Tổng doanh thu tất cả các phim: <strong>" . number_format(getTotalRevenue($movies), 0, ',', '.') . " VNĐ</strong></p>";

            echo "<h2>Phim Bán Chạy Nhất</h2>";
            $bestSelling = getBestSellingMovie($movies);
            if ($bestSelling) {
                echo "<p>Phim: <strong>{$bestSelling->title}</strong> ({$bestSelling->getSoldSeats()} vé)</p>";
            }

            echo "<h2>Xử lý ngoại lệ </h2>";
            echo "movies[0]->bookTicket(-5)<br>";
            $movies[0]->bookTicket(-5);
            echo "movies[0]->bookTicket(150)<br>";
            $movies[0]->bookTicket(150);
            echo "movies[0]->cancelTicket(0)<br>";
            $movies[0]->cancelTicket(0);
            echo "movies[0]->cancelTicket(100)<br>";
            $movies[0]->cancelTicket(100);

            $notFound = findMovieById($movies, 99);
            echo "<h2>Test tìm kiếm phim không tồn tại</h2>";
            if ($notFound === null) {
                echo "<p style='color:blue;'>Không tìm thấy phim có ID = 99.</p>";
            }

        } else {
            echo "<h1>Tiêu đề ... gì đó</h1>";
            echo "<p>Nút chọn bài ở phía trên</p>";
        }
        ?>
    </div>

</body>
</html>