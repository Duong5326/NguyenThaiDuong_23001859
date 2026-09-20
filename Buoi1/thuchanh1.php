<?php

$students = [
    [
        "name" => "Nguyen Van An",
        "age" => 20,
        "score" => 8.5
    ],
    [
        "name" => "Tran Thi Binh",
        "age" => 21,
        "score" => 6.5
    ],
    [
        "name" => "Le Van Cuong",
        "age" => 19,
        "score" => 4.5
    ],
    [
        "name" => "Pham Thi Dung",
        "age" => 20,
        "score" => 7.5
    ]
];

function calculateAverageScore($students) {
    $totalScore = 0;
    foreach ($students as $student) {
        $totalScore += $student["score"];
    }
    return $totalScore / count($students);
}

function getRank($score) {
    if ($score >= 8) {
        return "Giỏi";
    } elseif ($score >= 6.5) {
        return "Khá";
    } elseif ($score >= 5) {
        return "Trung bình";
    } else {
        return "Yếu";
    }
}

function displayStudent($student) {
    echo "Name: " . $student["name"] . "<br>";
    echo "Age: " . $student["age"] . "<br>";
    echo "Score: " . $student["score"] . "<br>";
    echo "Rank: " . getRank($student["score"]) . "<br>";
    echo "<hr>";
}

function findBestStudent($students) {
    $bestStudent = null;
    foreach ($students as $student) {
        if ($bestStudent === null || $student["score"] > $bestStudent["score"]) {
            $bestStudent = $student;
        }
    }
    return $bestStudent;
}

function findWorstStudent($students) {
    $worstStudent = null;
    foreach ($students as $student) {
        if ($worstStudent === null || $student["score"] < $worstStudent["score"]) {
            $worstStudent = $student;
        }
    }
    return $worstStudent;
}

function countPassedStudents($students) {
    $count = 0;
    foreach ($students as $student) {
        if ($student["score"] >= 5) {
            $count++;
        }
    }
    return $count;
}

function findStudentByName($students, $name) {
    foreach ($students as $student) {
        if ($student["name"] === $name) {
            return $student;
        }
    }
    return null;
}

// Main
foreach ($students as $student) { 
    displayStudent($student); 
}

$averageScore = calculateAverageScore($students); 
echo "Điểm trung bình: " . $averageScore . "<hr>";

echo "Học sinh có điểm cao nhất: <br>";
$bestStudent = findBestStudent($students);
displayStudent($bestStudent);

echo "Học sinh có điểm thấp nhất: <br>";
$worstStudent = findWorstStudent($students);
displayStudent($worstStudent);

echo "Số lượng học sinh đạt yêu cầu: " . countPassedStudents($students) . "<hr>";

echo "Nhập tên học sinh cần tìm: " ."<br>";
$searchName = "Nguyễn Thái Dương"; // Thay đổi tên học sinh cần tìm
$searchResult = findStudentByName($students, $searchName);
if ($searchResult) {
    echo "Kết quả tìm kiếm:<br>";
    displayStudent($searchResult);
} else {
    echo "Không tìm thấy học sinh có tên: " . $searchName;
}

?>