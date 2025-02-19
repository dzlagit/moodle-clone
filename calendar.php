<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$conn = new mysqli("localhost", "root", "password", "user_management");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure user is logged in and has a user ID
if (!isset($_SESSION['user_id'])) {
    die("Error: User ID not found. Please log in again.");
}

$user_id = $_SESSION['user_id']; // Assign user_id from session

// Handle event submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Check if required fields are filled
    if (!empty($title) && !empty($start_date) && !empty($end_date)) {
        $sql = "INSERT INTO events (user_id, title, description, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $title, $description, $start_date, $end_date);
        $stmt->execute();

        // Redirect to refresh the page and avoid resubmitting form
        header("Location: calendar.php");
        exit;
    } else {
        echo "<p style='color:red;'>Error: Please fill in all required fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar | ACE Training</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <a href="staffhomepage.php">Home</a>
        <a href="courses.php">Courses</a>
        <a href="student_quizzes.php">Quizzes</a>
        <a href="index.php">Logout</a>
    </nav>

    <h1 class="title">Calendar Dashboard</h1>
    <div id="calendar-container">
        <?php include "load_calendar.php"; ?>
    </div>

    <h2>Add New Event</h2>
    <form method="POST" action="calendar.php">
        <input type="text" name="title" placeholder="Event Title" required>
        <textarea name="description" placeholder="Event Description"></textarea>
        <input type="datetime-local" name="start_date" required>
        <input type="datetime-local" name="end_date" required>
        <button type="submit">Add Event</button>
    </form>
</body>
</html>
