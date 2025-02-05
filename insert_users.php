<?php
$servername = "localhost";
$username = "root";
$password = "password"; // Change if needed
$dbname = "user_management";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Array of random students
$students = [
    ["James", "Smith"],
    ["Emma", "Johnson"],
    ["Oliver", "Brown"],
    ["Sophia", "Davis"],
    ["Liam", "Miller"],
    ["Ava", "Wilson"],
    ["Noah", "Moore"],
    ["Isabella", "Taylor"],
    ["Ethan", "Anderson"],
    ["Mia", "Thomas"],
    ["Lucas", "Martinez"],
    ["Charlotte", "White"],
    ["Mason", "Harris"],
    ["Amelia", "Clark"],
    ["Elijah", "Lewis"],
    ["Harper", "Walker"],
    ["Benjamin", "Hall"],
    ["Evelyn", "Allen"],
    ["Henry", "Young"],
    ["Abigail", "King"]
];

$stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password, user_type) VALUES (?, ?, ?, ?, 1)");

foreach ($students as $student) {
    $first_name = $student[0];
    $last_name = $student[1];
    $random_number = rand(100, 999); // Ensures unique usernames
    $username = strtolower($first_name . $last_name . $random_number);
    $hashed_password = password_hash("SecurePass123", PASSWORD_DEFAULT); // Securely hash password

    $stmt->bind_param("ssss", $first_name, $last_name, $username, $hashed_password);
    $stmt->execute();
}

echo "✅ 20 students inserted successfully with hashed passwords!";
$stmt->close();
$conn->close();
?>
