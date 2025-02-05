<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Get the course id from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$course_id = intval($_GET['id']);

if (!isset($_SESSION['api_key']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 0) {
    header("Location: index.php");
    exit;
}

// does the staff member own that course
$sql_check_course = "
    SELECT COUNT(*) as count 
    FROM user_management.courses c, user_management.users u 
    WHERE c.id = ? AND u.api_key = ? AND c.user_id = u.id;
";
$stmt = $conn->prepare($sql_check_course);
$stmt->bind_param("is", $course_id, $_SESSION['api_key']);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
if ($result['count'] == 0) {
    header("Location: index.php");
    exit;
}

$sql_get_enrollments = "
    SELECT ce.user_id
    FROM user_management.course_enrollments ce
    WHERE ce.course_id = ?;
";

$stmt = $conn->prepare($sql_get_enrollments);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$enrolled_students_result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$enrolled_students = [];

foreach ($enrolled_students_result as $enrollment) {
    $user_id = $enrollment['user_id'];
    $sql_get_user = "
        SELECT id, first_name, last_name, username 
        FROM user_management.users 
        WHERE id = ?;
    ";
    $user_stmt = $conn->prepare($sql_get_user);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result()->fetch_assoc();

    if ($user_result) {
        $enrolled_students[] = $user_result;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrolled Students</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Enrolled Students</h1>
    <p>Here are all the students enrolled in this course:</p>
    <?php if (count($enrolled_students) > 0): ?>
        <ul>
            <?php foreach ($enrolled_students as $student): ?>
                <li>
                    <strong><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></strong>
                    (<?php echo htmlspecialchars($student['username']); ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No students are currently enrolled in this course.</p>
    <?php endif; ?>
    <a href="/courses.php">Back to your courses</a>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
