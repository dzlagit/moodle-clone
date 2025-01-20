<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect data from the form
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $password = $_POST['password']; 
    $user_type = $_POST['user_type']; // User type: staff or student

    // Debugging: Output the collected values
    // Uncomment these lines for debugging
    echo "First Name: $first_name, Last Name: $last_name, Username: $username, Password: $password, User Type: $user_type<br>";

    // Validate input
    if (empty($first_name) || empty($last_name) || empty($username) || empty($password)) {
        die("All fields are required!");
    }

    // Securely hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $conn = new mysqli('localhost', 'root', 'password', 'user_management');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the user into the database
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password, user_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $first_name, $last_name, $username, $hashed_password, $user_type);

    // Execute the query and check for success
    if ($stmt->execute()) {
        // Registration successful, redirect to index page
        header("Location: index.html"); // Redirect to your index page
        exit; // Ensure no further code is executed
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
