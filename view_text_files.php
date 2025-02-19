<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    die("Error: You must log in to view files.");
}

$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'];

// If the user is staff, they can see all their own uploaded files
if ($user_type == 0) {
    $sql = "SELECT file_name, file_path, uploaded_at FROM uploaded_files WHERE tutor_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
} 
// If the user is a student, show only files uploaded by tutors of their enrolled courses
else {
    $sql = "
        SELECT uf.file_name, uf.file_path, uf.uploaded_at 
        FROM uploaded_files uf
        JOIN courses c ON uf.tutor_id = c.user_id
        JOIN course_enrollments ce ON c.id = ce.course_id
        WHERE ce.user_id = ?
        AND uf.file_type = 'text/plain'"; // Ensures only text files are shown

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Text Files</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <a href="<?php echo ($user_type == 0) ? 'staffhomepage.php' : 'studenthomepage.php'; ?>">Home</a>
        <a href="courses.php">Courses</a>
        <a href="calendar.php">Calendar</a>
        <a href="index.php">Logout</a>
    </nav>

    <h1 class="title">Available Text Files</h1>

    <div class="container">
        <?php if ($result->num_rows > 0): ?>
            <ul>
                <?php while ($file = $result->fetch_assoc()): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($file['file_name']); ?></strong>
                        (Uploaded on: <?php echo date("d M Y", strtotime($file['uploaded_at'])); ?>)
                        - <a href="<?php echo htmlspecialchars($file['file_path']); ?>" target="_blank">View</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No text files available for your courses.</p>
        <?php endif; ?>
    </div>

</body>
</html>
