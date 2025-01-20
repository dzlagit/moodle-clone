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
    <title>Staff Homepage</title>
</head>
<body>
    <h1>Welcome, Staff Member!</h1>
    <p>This page is accessible only to staff users.</p>
</body>
</html>
