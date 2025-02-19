<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    die("Error: You must log in to upload files.");
}

$message = ""; // Store success/error messages

// Process the upload only when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['file']['name'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileType = $file['type'];
    $fileError = $file['error'];

    // Ensure an upload directory exists
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique file path
    $filePath = $uploadDir . basename($fileName);

    // Get user details
    $uploadedBy = $_SESSION['username'];
    $user_id = $_SESSION['user_id']; // User ID from session
    $tutor_id = $user_id; // Assuming tutor_id is the same as user_id for staff

    // Check if file uploaded without errors
    if ($fileError === UPLOAD_ERR_OK) {
        if (move_uploaded_file($fileTmpName, $filePath)) {
            // Read file content only if it's a text file
            $fileContent = null;
            if ($fileType === "text/plain") {
                $fileContent = file_get_contents($filePath);
            }

            // Insert file data into database
            $sql = "INSERT INTO uploaded_files 
                (file_name, file_path, file_type, file_size, uploaded_by, file_content, tutor_id, user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("sssissii", $fileName, $filePath, $fileType, $fileSize, $uploadedBy, $fileContent, $tutor_id, $user_id);

            if ($stmt->execute()) {
                $message = "<p style='color: green; font-weight: bold;'>File Uploaded Successfully!</p>";
            } else {
                $message = "<p style='color: red; font-weight: bold;'>Error: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            $message = "<p style='color: red; font-weight: bold;'>Error: Failed to upload file.</p>";
        }
    } else {
        $message = "<p style='color: red; font-weight: bold;'>Error: File upload failed with error code " . $fileError . "</p>";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = "<p style='color: red; font-weight: bold;'>Error: No file uploaded. Please select a file.</p>";
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
    <link rel="stylesheet" href="styles.css"> <!-- Make sure your CSS file is linked -->
</head>
<body>
    <h1 class="title">Upload Files</h1>

    <div class="container">
        <?= $message ?> <!-- Display success/error messages here -->

        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="file_title"><strong>File Title:</strong></label>
            <input type="text" id="file_title" name="file_title" placeholder="Enter file title" required>

            <label for="file"><strong>Select File:</strong></label>
            <input type="file" id="file" name="file" required>

            <button type="submit">Upload</button>
        </form>
    </div>
    <nav class="navbar">
        <a href="staffhomepage.php">Home</a>
        <a href="courses.php">Courses</a>
        <a href="quizzes.php">Quizzes</a>
        <a href="index.php">Logout</a>        
        
    </nav>
</body>
</html>
