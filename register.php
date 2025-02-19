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
    //echo "First Name: $first_name, Last Name: $last_name, Username: $username, Password: $password, User Type: $user_type<br>";

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
        header("Location: index.php"); // Redirect to your index page
        exit; // Ensure no further code is executed
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Register here:</h2>
        <form id="staff-register" action="register.php" method="POST">
            <!-- First Name Field -->
            <label for="first_name">Enter First Name:</label>
            <input 
                type="text" 
                id="first_name" 
                name="first_name" 
                placeholder="Enter first name" 
                required>
            
            <!-- Last Name Field -->
            <label for="last_name">Enter Last Name:</label>
            <input 
                type="text" 
                id="last_name" 
                name="last_name" 
                placeholder="Enter last name" 
                required>
            
            <!-- Username Field -->
            <label for="username">Enter Username:</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                placeholder="Enter a unique username" 
                required>
            
            <!-- Password Field -->
            <label for="password">Enter Strong Password:</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" 
                title="Password must be at least 8 characters long, with at least one uppercase letter, one lowercase letter, one number, and one special character."
                placeholder="Create a strong password">
            
            <!-- User Type: Staff or Student -->
            <label for="user_type">Select User Type:</label>
            <select id="user_type" name="user_type" required>
                <option value="0">Staff</option>
                <option value="1">Student</option>
            </select>
            
            <!-- Submit Button -->
            <button type="submit">Register</button>            
        </form>
    </div>
    <nav class="navbar">
        <a href="index.php">Home</a>
    </nav>
</body>
</html>
