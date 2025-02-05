<?php
session_start();

$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Collect login credentials
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    die("Username and password are required.");
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

        // Redirect based on user type
        if ($user['user_type'] == 0) {
            header("Location: staffhomepage.php");
        } else {
            header("Location: studenthomepage.php");
        }
        exit;
    } else {
        die("Incorrect password.");
    }
} else {
    die("User not found.");
}
?>
