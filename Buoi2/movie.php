<?php
class Movie {
    public $id;
    public $title;
    public $price;
    public $totalSeats;
    public $availableSeats;

    public function __construct($id, $title, $price, $totalSeats) {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->totalSeats = $totalSeats;
        $this->availableSeats = $totalSeats;
    }
    public function bookTicket($quantity) {
        if ($quantity <= 0) {
            echo "<p style='color: red;'>Lỗi: Số lượng vé đặt cho phim '{$this->title}' phải lớn hơn 0.</p>" . "<br>";
            return false;
        }
        if ($quantity > $this->availableSeats) {
            echo "<p style='color: red;'>Lỗi: Không đủ ghế trống cho phim '{$this->title}'. (Yêu cầu: $quantity, Còn lại: {$this->availableSeats})</p>" . "<br>";
            return false;
        }
        
        $this->availableSeats -= $quantity;
        echo "<p style='color: green;'>Thành công: Đã đặt $quantity vé cho phim '{$this->title}'.</p>" . "<br>";
        return true;
    }
    public function cancelTicket($quantity) {
        if ($quantity <= 0) {
            echo "<p style='color: red;'>Lỗi: Số lượng vé hủy của phim '{$this->title}' phải lớn hơn 0.</p>" . "<br>";
            return false;
        }
        $soldSeats = $this->getSoldSeats();
        if ($quantity > $soldSeats) {
            echo "<p style='color: red;'>Lỗi: Không thể hủy nhiều hơn số vé đã bán của phim '{$this->title}'. (Yêu cầu: $quantity, Đã bán: $soldSeats)</p>" . "<br>";
            return false;
        }

        $this->availableSeats += $quantity;
        echo "<p style='color: green;'>Thành công: Đã hủy $quantity vé của phim '{$this->title}'.</p>" . "<br>";
        return true;
    }
    public function getSoldSeats() {
        return $this->totalSeats - $this->availableSeats;
    }

    public function getRevenue() {
        return $this->getSoldSeats() * $this->price;
    }

    public function displayInfo() {
        echo "Mã phim: " . $this->id . "<br>";
        echo "Tên phim: " . $this->title . "<br>";
        echo "Giá vé: " . number_format($this->price, 0, ',', '.') . " VNĐ<br>";
        echo "Tổng số ghế: " . $this->totalSeats . "<br>";
        echo "Số ghế còn lại: " . $this->availableSeats . "<br>";
        echo "Số vé đã bán: " . $this->getSoldSeats() . "<br>";
        echo "Doanh thu: " . number_format($this->getRevenue(), 0, ',', '.') . " VNĐ<hr>";
    }
}

function findMovieById($movies, $id) {
    if (empty($movies)) {
        return null;
    }
    foreach ($movies as $movie) {
        if ($movie->id === $id) {
            return $movie;
        }
    }
    return null;
}

function getTotalRevenue($movies) {
    if (empty($movies)) {
        return 0;
    }
    $total = 0;
    foreach ($movies as $movie) {
        $total += $movie->getRevenue();
    }
    return $total;
}

function getBestSellingMovie($movies) {
    if (empty($movies)) {
        return null;
    }
    $bestMovie = $movies[0];
    foreach ($movies as $movie) {
        if ($movie->getSoldSeats() > $bestMovie->getSoldSeats()) {
            $bestMovie = $movie;
        }
    }
    return $bestMovie;
}
?>