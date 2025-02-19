<?php
session_start();
$conn = new mysqli("localhost", "root", "password", "user_management");
if ($conn->connect_error) die(json_encode(["status" => "error", "message" => "Database connection failed."]));

$user_id = $_SESSION['user_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$sql = "INSERT INTO events (id, user_id, title, description, start_date, end_date) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $user_id, $title, $description, $start_date, $end_date);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Event added successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add event."]);
}
?>