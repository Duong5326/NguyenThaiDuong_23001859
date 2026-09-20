<?php

class student {
    public $name;
    public $age;
    public $score;

    public function __construct($name, $age, $score) {
        $this->name = $name;
        $this->age = $age;
        $this->score = $score;
    }

    public function getRank() { 
        if ($this->score >= 8) { 
            return "Giỏi"; 
        } elseif ($this->score >= 6.5) { 
            return "Khá"; 
        } elseif ($this->score >= 5) { 
            return "Trung bình"; 
        } else { 
            return "Yếu"; 
        } 
    }
    public function isPass() {
        return $this->score >= 5;
    }
    public function display() {
        echo "Name: " . $this->name . "<br>";
        echo "Age: " . $this->age . "<br>";
        echo "Score: " . $this->score . "<br>";
        echo "Rank: " . $this->getRank() . "<br>";
        if ($this->isPass()) {
            echo "Đạt <br>";
        } else {
            echo "Không đạt <br>";
        }
    }
}
$student1 = new Student("Nguyen Van An", 20, 8.5); 
$student2 = new Student("Tran Thi Binh", 21, 6.5); 
$student3 = new Student("Le Van Cuong", 19, 4.5); 
$student4 = new Student("Pham Thi Dung", 20, 7.5);
$students = [ $student1, $student2, $student3, $student4 ];

function displayStudents($students) {
    foreach ($students as $student) {
        $student->display();
        echo "<br>";
    }
}

function findHighestScoreStudent($students) {
    $highestStudent = $students[0];
    foreach ($students as $student) {
        if ($student->score > $highestStudent->score) {
            $highestStudent = $student;
        }
    }
    return $highestStudent;
}

function countPassedStudents($students) {
    $count = 0;
    foreach ($students as $student) {
        if ($student->isPass()) {
            $count++;
        }
    }
    return $count;
}

function calculateAverageScore($students) {
    $totalScore = 0;
    foreach ($students as $student) {
        $totalScore += $student->score;
    }
    return $totalScore / count($students);
}

// Main 
echo "<h2>Danh sách sinh viên</h2>"; 
displayStudents($students);

$highestStudent = findHighestScoreStudent($students); 
echo "<h2>Sinh viên có điểm cao nhất</h2>"; 
echo "Họ tên: " . $highestStudent->name . "<br>"; 
echo "Điểm: " . $highestStudent->score . "<br>"; 

$passedCount = countPassedStudents($students); 
echo "Số sinh viên đạt: " . $passedCount . "<br>"; 

$averageScore = calculateAverageScore($students); 
echo "Điểm trung bình của lớp: " . $averageScore; 
?>