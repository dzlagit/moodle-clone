<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Redirect if not logged in
if (!isset($_SESSION['api_key']) || !isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit;
}

$user_type = $_SESSION['user_type']; // 0 = Staff, 1 = Student

function get_staff_courses($conn) {
    $sql = "
        SELECT c.id, c.name, c.description 
        FROM user_management.users u, user_management.courses c 
        WHERE u.api_key = ? AND u.id = c.user_id;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['api_key']);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_student_courses($conn) {
    $sql = "
        SELECT c.name, c.description 
        FROM user_management.users u, user_management.courses c, user_management.course_enrollments ce  
        WHERE u.api_key = ? 
        AND ce.user_id = u.id
        AND ce.course_id = c.id;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['api_key']);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_all_students($conn) {
    $sql = "SELECT id, username FROM user_management.users WHERE user_type = 1;";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses | ACE Training</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Here are your courses!</h1>

        <?php if ($user_type == 0): ?>
            <p>View your courses that you can enroll students in below:</p>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Course Name</th>
                            <th>Course Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $courses = get_staff_courses($conn);
                        foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['name']); ?></td>
                                <td><?php echo htmlspecialchars($course['description']); ?></td>
                                <td>
                                    <a href="course.php?id=<?php echo htmlspecialchars($course['id']); ?>">
                                        <button>View Class</button>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <h2>Create a New Course</h2>
            <form action="createcourse.php" method="post">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name" required>
                <label for="course_description">Course Description:</label>
                <textarea id="course_description" name="course_description" rows="4" required></textarea>
                <button type="submit">Create Course</button>
            </form>

            <h2>Enroll a Student in a Course</h2>
            <form action="enroll.php" method="post">
                <label for="course_id">Select a Course:</label>
                <select id="course_id" name="course_id" required>
                    <option value="" disabled selected>Choose a course</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo htmlspecialchars($course['id']); ?>">
                            <?php echo htmlspecialchars($course['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="student_id">Select a Student:</label>
                <select id="student_id" name="student_id" required>
                    <option value="" disabled selected>Choose a student</option>
                    <?php 
                    $students = get_all_students($conn);
                    foreach ($students as $student): ?>
                        <option value="<?php echo htmlspecialchars($student['id']); ?>">
                            <?php echo htmlspecialchars($student['username']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Enroll Student</button>
            </form>
            <a href="staffhomepage.php">Back to staff homepage</a>

        <?php elseif ($user_type == 1): ?>
            <?php 
            $courses = get_student_courses($conn);
            if (count($courses) > 0): ?>
                <p>View your enrolled courses below:</p>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Course Name</th>
                                <th>Course Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($course['name']); ?></td>
                                    <td><?php echo htmlspecialchars($course['description']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>You are not enrolled in any courses yet.</p>
            <?php endif; ?>
        <?php else: ?>
            <h1>Access Denied</h1>
            <p>You do not have permission to view this page.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
