<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_type'])) {
    // Redirect to login page if not authenticated
    header("Location: login.html?error=unauthorized");
    exit;
}

// Helper function to restrict access based on user type
function restrict_access($required_user_type) {
    if ($_SESSION['user_type'] != $required_user_type) {
        // Redirect to an unauthorized page or login page
        header("Location: unauthorized.html");
        exit;
    }
}
?>
