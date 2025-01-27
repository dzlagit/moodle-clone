<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', 'password', 'user_management');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if a file has been uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileError = $file['error'];

    // Check if user is logged in
    if (!isset($_SESSION['username'])) {
        die("You must log in to upload files.");
    }

    // Check for upload errors
    if ($fileError === UPLOAD_ERR_OK) {
        $uploadDir = 'resources/';
        $filePath = $uploadDir . basename($fileName);

        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move file to the resources directory
        if (move_uploaded_file($fileTmpName, $filePath)) {
            $uploadedBy = $_SESSION['username'];
            $sql = "INSERT INTO uploaded_files (file_name, file_path, uploaded_by) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $fileName, $filePath, $uploadedBy);
            if ($stmt->execute()) {
                echo "File upload success";
            } else {
                echo "Failed to record file upload in database. Error: " . $stmt->error;
            }
        } else {
            die("Failed to upload file. Temp file: $fileTmpName, Destination: $filePath");
        }
    } else {
        die("Error uploading file: " . $fileError);
    }
} else {
    die("No file uploaded.");
}

// Close database connection
$conn->close();
?>
