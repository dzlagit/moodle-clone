<?php
require 'authapi.php';

// Verify user type is staff (0)
if ($_SESSION['user_type'] != 0) {
    header("Location: unauthorised.php");
    exit;
}
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache"); 
header("Expires: 0"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Homepage | ACE Training</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <h1 class="title">Ace Training</h1>
    <h2 class="subtitle">Welcome, Staff Member!</h2>
    <p>This page is accessible only to staff users.</p>
</body>
<nav class="navbar">
        <a href="staffhomepage.php">Home</a>
        <a href="courses.php">Courses</a>
        <a href="create_quiz.php">Quizzes</a>
        <a href="index.php">Logout</a>
        <a href="upload.php">Upload Files</a>
        
        
    </nav>
</html>
