<?php
session_start();

// Database connection
$conn = new mysqli('localhost:3307', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Check session
if (!isset($_SESSION['api_key']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] != 0) {
    header("Location: index.php");
    exit;
}

// Validate POST data
if (!isset($_POST['course_id']) || !isset($_POST['student_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit;
}

$course_id = intval($_POST['course_id']);
$student_id = intval($_POST['student_id']);

// Check if the staff owns the course
$sql_check_course = "
    SELECT COUNT(*) as count 
    FROM user_management.courses c 
    INNER JOIN user_management.users u ON c.user_id = u.id 
    WHERE c.id = ? AND u.api_key = ?;
";
$stmt = $conn->prepare($sql_check_course);
$stmt->bind_param("is", $course_id, $_SESSION['api_key']);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
if ($result['count'] == 0) {
    echo json_encode(['status' => 'error', 'message' => 'You do not have permission to enroll students in this course.']);
    exit;
}

// Check if the student is already enrolled
$sql_check_enrollment = "
    SELECT COUNT(*) as count 
    FROM user_management.course_enrollments 
    WHERE course_id = ? AND user_id = ?;
";
$stmt = $conn->prepare($sql_check_enrollment);
$stmt->bind_param("ii", $course_id, $student_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
if ($result['count'] > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Student is already enrolled in this course.']);
    exit;
}

// Insert enrollment into the table
$sql_enroll = "
    INSERT INTO user_management.course_enrollments (course_id, user_id) 
    VALUES (?, ?);
";
$stmt = $conn->prepare($sql_enroll);
$stmt->bind_param("ii", $course_id, $student_id);

if ($stmt->execute()) {
    header("Location: /course.php?id=$course_id");
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to enroll student.']);
}

$stmt->close();
$conn->close();
?>
