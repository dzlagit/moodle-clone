<?php
require 'authapi.php';

// Verify user type is student (1)
if ($_SESSION['user_type'] != 1) {
    header("Location: unauthorised.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Homepage</title>
</head>
<body>
    <h1>Welcome, Student!</h1>
    <p>This page is accessible only to student users.</p>
</body>
</html>
