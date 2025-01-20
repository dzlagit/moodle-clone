
//session_start();
/*
// Debugging - Show submitted form data
var_dump($_POST); // This will print the form data

// Database connection
$conn = new mysqli('localhost', 'root', '', 'user_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Query the database for the user
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // Store user info in session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['username'] = $user['username'];

    // Redirect based on user type
    if ($user['user_type'] == 0) {
        header("Location: staff_homepage.html");
    } else {
        header("Location: student_homepage.html");
    }
    exit;
} else {
    echo "Invalid username or password.";
}

$conn->close();
?>
/*