<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['quiz_id'])) {
    die("Error: Quiz ID is missing.");
}

$quiz_id = intval($_GET['quiz_id']);

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch quiz title
$sql = "SELECT title FROM quizzes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
$quiz = $result->fetch_assoc();
if (!$quiz) {
    die("Error: Quiz not found.");
}

// Fetch results
$sql = "SELECT u.username, r.score, r.total_questions, r.submitted_at 
        FROM quiz_results r
        JOIN users u ON r.user_id = u.id
        WHERE r.quiz_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- ✅ Navbar -->
    <nav class="navbar">
        <a href="studenthomepage.php">Home</a>
        <a href="courses.php">Courses</a>
        <a href="quizzes.php" class="active">Quizzes</a>
        <a href="logout.php">Logout</a>
    </nav>

    <h1>Quiz Results: <?php echo htmlspecialchars($quiz['title']); ?></h1>
    <table border="1">
        <tr>
            <th>Student</th>
            <th>Score</th>
            <th>Total Questions</th>
            <th>Submitted At</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo $row['score']; ?></td>
            <td><?php echo $row['total_questions']; ?></td>
            <td><?php echo $row['submitted_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <?php
    $stmt->close();
    $conn->close();
    ?>

</body>
</html>
