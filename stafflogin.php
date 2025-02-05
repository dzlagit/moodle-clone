<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - ACE Training</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1 class="title">Ace Training</h1>
    <h2 class="subtitle">Staff Login</h2>
    <div class="container">
        <form id="staff-login" action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
