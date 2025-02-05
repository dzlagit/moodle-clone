<?php
require 'authapi.php';

// Verify user type is staff (0)
if ($_SESSION['user_type'] != 0) {
    header("Location: unauthorized.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files | ACE Training</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <a href="staffhomepage.php">Home</a>
        <a href="courses.php">Courses</a>
        <a href="quizzes.php">Quizzes</a>
        <a href="index.php" class="logout-link">Logout</a>
    </nav>

    <h1 class="title">Upload Files</h1>
    <form id="upload-form" action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="file-name">File Name:</label>
        <input type="text" id="file-name" name="file_name" required>
        
        <label for="file-upload">Select File:</label>
        <input type="file" id="file-upload" name="file" required>
        
        <button type="submit">Upload</button>
    </form>
    <h1 class="title">Ace Training</h1>
    <h2 class="subtitle">Welcome, Staff Member!</h2>
    <p>This page is accessible only to staff users.</p>
</body>
</html>
