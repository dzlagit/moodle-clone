<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['quiz_id'])) {
    die("Error: `quiz_id` is missing from the URL.");
}

$quiz_id = intval($_GET['quiz_id']);

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 1) {
    die("Access Denied: Only students can take quizzes.");
}

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch quiz details
$sql = "SELECT title FROM quizzes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz_result = $stmt->get_result();
$quiz = $quiz_result->fetch_assoc();

if (!$quiz) {
    die("Quiz not found.");
}

// Fetch questions and answers
$sql = "
    SELECT qq.id AS question_id, qq.question_text, qa.id AS answer_id, qa.answer_text 
    FROM questions qq
    JOIN answers qa ON qq.id = qa.question_id
    WHERE qq.quiz_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[$row['question_id']]['question_id'] = $row['question_id'];
    $questions[$row['question_id']]['text'] = $row['question_text'];
    $questions[$row['question_id']]['answers'][] = [
        'id' => $row['answer_id'],
        'text' => $row['answer_text']
    ];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($quiz['title']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>

    <form action="submit_quiz.php" method="POST">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

        <?php foreach ($questions as $question): ?>
            <fieldset>
                <legend><?php echo htmlspecialchars($question['text']); ?></legend>
                <?php foreach ($question['answers'] as $answer): ?>
                    <label class="quiz-option">
                        <span><?php echo htmlspecialchars($answer['text']); ?></span><br>
                        <input type="radio" name="answers[<?php echo $question['question_id']; ?>]" value="<?php echo $answer['id']; ?>" required>
                    </label><br>
                <?php endforeach; ?>
            </fieldset>
        <?php endforeach; ?>

        <button type="submit">Submit Quiz</button>
    </form>
</body>
</html>
