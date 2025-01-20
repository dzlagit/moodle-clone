<?php
session_start();
header('Content-Type: application/json');

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Get API key from session or header
$api_key = $_SESSION['api_key'] ?? $_SERVER['HTTP_API_KEY'] ?? null;

if (!$api_key) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. API key missing.']);
    exit;
}

// Validate API key in the database
$sql = "SELECT user_type FROM users WHERE api_key = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $api_key);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'user_type' => $user['user_type']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid API key.']);
    exit;
}
?>
