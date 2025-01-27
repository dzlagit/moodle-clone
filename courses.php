<?php
session_start();

function get_staff_courses($sqlConnection) {
    $sql = "
        SELECT c.id, c.name, c.description 
        FROM user_management.users u, user_management.courses c 
        WHERE u.api_key = ? AND u.id = c.user_id;
    ";
    $stmt = $sqlConnection->prepare($sql);
    $stmt->bind_param("s", $_SESSION['api_key']);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return $result;
}

function get_student_courses($sqlConnection) {
    $sql = "
        SELECT c.name, c.description 
        FROM user_management.users u, user_management.courses c, user_management.course_enrollments ce  
        WHERE u.api_key = ? 
        AND ce.user_id = u.id
        AND ce.course_id = c.id;
    ";

    $stmt = $sqlConnection->prepare($sql);
    $stmt->bind_param("s", $_SESSION['api_key']);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    return $result;
}

function get_all_students($sqlConnection) {
    $sql = "
        SELECT id, username 
        FROM user_management.users 
        WHERE user_type = 1; -- Assuming '1' represents student user type
    ";

    $result = $sqlConnection->query($sql);

    return $result->fetch_all(MYSQLI_ASSOC);
}

// Database connection
$conn = new mysqli('localhost:3307', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

if (!isset($_SESSION['api_key']) || !isset($_SESSION['user_type'])) {
    header("Location: index.php");
    exit;
}

$user_type = $_SESSION['user_type']; // Assuming 0 for staff, 1 for student
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Here are your courses!</h1>
    <?php if ($user_type == 0): ?>
        <p>View your courses that you can enroll students in below:</p>
        <table border="1">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Course Description</th>
                    <th>Actions</th> <!-- New Actions column for staff -->
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
                            <a href="/course.php?id=<?php echo htmlspecialchars($course['id']); ?>">
                                <button>View Class</button>
                            </a>
                        </td> <!-- View Class button for each course -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Create a New Course</h2>
        <form action="/createcourse.php" method="post">
            <label for="course_name">Course Name:</label><br>
            <input type="text" id="course_name" name="course_name" required><br>
            <label for="course_description">Course Description:</label><br>
            <textarea id="course_description" name="course_description" rows="4" required></textarea><br>
            <button type="submit">Create Course</button>
        </form>

        <h2>Enroll a Student in a Course</h2>
        <form action="/enroll.php" method="post">
            <label for="course_id">Select a Course:</label><br>
            <select id="course_id" name="course_id" required>
                <option value="" disabled selected>Choose a course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo htmlspecialchars($course['id']); ?>">
                        <?php echo htmlspecialchars($course['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <label for="student_id">Select a Student:</label><br>
            <select id="student_id" name="student_id" required>
                <option value="" disabled selected>Choose a student</option>
                <?php 
                $students = get_all_students($conn);
                foreach ($students as $student): ?>
                    <option value="<?php echo htmlspecialchars($student['id']); ?>">
                        <?php echo htmlspecialchars($student['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select><br>

            <button type="submit">Enroll Student</button>
        </form>
        <a href="/staffhomepage.php">Back to staff homepage</a>
    <?php elseif ($user_type == 1): ?>
        <?php 
        $courses = get_student_courses($conn);
        if (count($courses) > 0): ?>
            <p>View your enrolled courses below:</p>
            <table border="1">
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
        <?php else: ?>
            <p>Good news! You currently don't have any courses to endure!</p>
        <?php endif; ?>
    <?php else: ?>
        <h1>Access Denied</h1>
        <p>You do not have permission to view this page.</p>
    <?php endif; ?>
</body>
</html>
