<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    die("Access Denied: Only students can submit quizzes.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['quiz_id']) || empty($_POST['answers'])) {
    die("Invalid request: No answers submitted.");
}

$quiz_id = intval($_POST['quiz_id']);
$user_id = $_SESSION['user_id'];
$answers = $_POST['answers'];

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Calculate Score
$score = 0;
$total_questions = count($answers);

foreach ($answers as $question_id => $answer_id) {
    $sql = "SELECT is_correct FROM answers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }
    $stmt->bind_param("i", $answer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $answer = $result->fetch_assoc();
    
    if ($answer && $answer['is_correct'] == 1) {
        $score++;
    }
}

// Store the result in the database
$sql = "INSERT INTO quiz_results (user_id, quiz_id, score, total_questions) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("iiii", $user_id, $quiz_id, $score, $total_questions);
$stmt->execute();

$stmt->close();
$conn->close();

// Redirect to results page
header("Location: quiz_results.php?quiz_id=$quiz_id");
exit;
