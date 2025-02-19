<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 0) {
    die("Access Denied: Only staff can create quizzes.");
}

// Ensure quiz_id is provided
if (!isset($_GET['quiz_id'])) {
    die("Error: Quiz ID is missing.");
}

$quiz_id = intval($_GET['quiz_id']); // Convert to integer for safety

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = trim($_POST['question_text']);
    $correct_answer_index = intval($_POST['correct_answer']);
    $answers = array_filter($_POST['answers'], 'strlen'); // Remove empty answers

    if (empty($question_text) || count($answers) < 2) {
        echo "<p style='color:red;'>Error: You must enter a question and at least 2 answers.</p>";
    } else {
        // Insert question into the database
        $stmt = $conn->prepare("INSERT INTO quiz_questions (quiz_id, question_text) VALUES (?, ?)");
        $stmt->bind_param("is", $quiz_id, $question_text);
        $stmt->execute();
        $question_id = $stmt->insert_id; // Get the ID of the newly inserted question

        // Insert answers into the database
        foreach ($answers as $index => $answer) {
            $is_correct = ($index == $correct_answer_index) ? 1 : 0;
            $stmt = $conn->prepare("INSERT INTO quiz_answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $question_id, $answer, $is_correct);
            $stmt->execute();
        }

        echo "<p style='color:green;'>Question added successfully!</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Questions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Add a Question</h1>
    <form action="add_questions.php?quiz_id=<?php echo $quiz_id; ?>" method="POST">
        <label>Question:</label>
        <input type="text" name="question_text" required>

        <label>Answers:</label>
        <input type="text" name="answers[]" required>
        <input type="text" name="answers[]" required>
        <input type="text" name="answers[]">
        <input type="text" name="answers[]">

        <label>Correct Answer (Index, starting at 0):</label>
        <input type="number" name="correct_answer" min="0" required>

        <button type="submit">Add Another Question</button>
    </form>

    <!-- Finish Quiz Button -->
    <form action="staffhomepage.php" method="GET">
        <button type="submit" style="background-color: red; color: white;">Finish Quiz</button>
    </form>
</body>
</html>
