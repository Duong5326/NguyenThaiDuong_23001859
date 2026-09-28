<?php
    class CartItem {
        public $name;
        public $price;
        public $quantity;

        public function __construct($name, $price, $quantity) {
            $this->name = $name;
            $this->price = $price;
            $this->quantity = $quantity;
        }

        public function getTotal() {
            return $this->price * $this->quantity;
        }
    }

    class ShoppingCart {
        private $items = [];

        public function addItem($item) {
            $this->items[] = $item;
        }
        public function removeItem($name) {
            foreach ($this->items as $key => $cartItem) {
                if ($cartItem->name === $name) {
                    unset($this->items[$key]);
                    break;
                }
            }
        }
        public function calculateTotal() {
            $total = 0;
            foreach ($this->items as $item) {
                $total += $item->getTotal();
            }
            return $total;
        }
        public function displayCart($showTotal = true) {
            foreach ($this->items as $item) {
                echo "Name: " . $item->name . "<br>";
                echo "Price: " . $item->price . "<br>";
                echo "Quantity: " . $item->quantity . "<br>";
                echo "Total: " . $item->getTotal() . "<hr>";
            }
            if ($showTotal) {
                echo "<h3>Total Amount: $" . number_format($this->calculateTotal(), 2) . "<br></h3>";
            }
        }
    }