<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 0) {
    die("Access denied: Only staff can create quizzes.");
}

$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $created_by = $_SESSION['user_id'];

    if (empty($title)) {
        die("Error: Quiz title is required.");
    }

    $stmt = $conn->prepare("INSERT INTO quizzes (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $created_by);
    
    if ($stmt->execute()) {
        header("Location: add_questions.php?quiz_id=" . $quiz_id);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Quiz</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Create a New Quiz</h1>
    <form action="create_quiz.php" method="POST">
        <label>Quiz Title:</label>
        <input type="text" name="title" required>
        <label>Quiz Description:</label>
        <textarea name="description"></textarea>
        <button type="submit">Create Quiz</button>
    </form>
</body>
</html>
