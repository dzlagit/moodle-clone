<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "password", "user_management");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];
$view = $_GET['view'] ?? 'month';

$sql = "SELECT * FROM events WHERE user_id = ? ORDER BY start_date";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<ul>";
while ($event = $result->fetch_assoc()) {
    echo "<li><strong>" . htmlspecialchars($event['title']) . "</strong> - " . 
         date("M d, Y H:i", strtotime($event['start_date'])) . "</li>";
}
echo "</ul>";
?>
