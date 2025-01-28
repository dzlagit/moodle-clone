<?php
session_start();
if (!isset($_SESSION['api_key']) || $_SESSION['user_type'] != 0) {
    header("Location: index.php");
    exit;
}

// Database connection
$conn = new mysqli('localhost:3307', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name']);
    $course_description = trim($_POST['course_description']);
    $api_key = $_SESSION['api_key'];

    if (empty($course_name) || empty($course_description)) {
        echo "All fields are required.";
        exit;
    }

    // Fetch the user ID based on the API key
    $sql = "SELECT id FROM user_management.users WHERE api_key = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $api_key);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Invalid API key.";
        exit;
    }

    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    // Insert the new course
    $sql = "INSERT INTO user_management.courses (name, description, user_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $course_name, $course_description, $user_id);

    if ($stmt->execute()) {
        echo "Course created successfully.";
        header("Location: /courses.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
