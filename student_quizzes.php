<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    die("Access Denied: Only students can access quizzes.");
}

$user_id = $_SESSION['user_id'];

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get quizzes from courses the student is enrolled in
$sql = "
    SELECT q.id, q.title 
    FROM quizzes q
    JOIN courses c ON q.created_by = c.user_id
    JOIN course_enrollments ce ON ce.course_id = c.id
    WHERE ce.user_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Quizzes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Available Quizzes</h1>
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while ($quiz = $result->fetch_assoc()): ?>
                <li>
                    <a href="take_quiz.php?quiz_id=<?php echo $quiz['id']; ?>">
                        <?php echo htmlspecialchars($quiz['title']); ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No quizzes available for your courses.</p>
    <?php endif; ?>
    <nav class="navbar">
        <a href="staffhomepage.php">Home</a>
        <a href="student_quizzes.php">Quizzes</a>
        <a href="view_text_files.php">Messages</a>
        <a href="index.php">Logout</a>
        <a href="calendar.php">Calendar</a>

    </nav>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
