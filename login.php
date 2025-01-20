<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Collect login credentials
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Username and password are required.']);
    exit;
}

// Fetch user from database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        // Generate unique API key
        $api_key = bin2hex(random_bytes(16));
        $update_sql = "UPDATE users SET api_key = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $api_key, $user['id']);
        $update_stmt->execute();

        // Store API key and user info in session
        $_SESSION['api_key'] = $api_key;
        $_SESSION['user_type'] = $user['user_type'];


        header("Location: " . ($user['user_type'] == 0 ? 'staffhomepage.html' : 'studenthomepage.html'));
        //echo json_encode(['status' => 'success', 'redirect' => $user['user_type'] == 0 ? 'staffhomepage.html' : 'studenthomepage.html']);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect password.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found.']);
    exit;
}
?>
