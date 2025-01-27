<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Register here:</h2>
        <form id="staff-register" action="register.php" method="POST">
            <!-- First Name Field -->
            <label for="first_name">Enter First Name:</label>
            <input 
                type="text" 
                id="first_name" 
                name="first_name" 
                placeholder="Enter first name" 
                required>
            
            <!-- Last Name Field -->
            <label for="last_name">Enter Last Name:</label>
            <input 
                type="text" 
                id="last_name" 
                name="last_name" 
                placeholder="Enter last name" 
                required>
            
            <!-- Username Field -->
            <label for="username">Enter Username:</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                placeholder="Enter a unique username" 
                required>
            
            <!-- Password Field -->
            <label for="password">Enter Strong Password:</label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" 
                title="Password must be at least 8 characters long, with at least one uppercase letter, one lowercase letter, one number, and one special character."
                placeholder="Create a strong password">
            
            <!-- User Type: Staff or Student -->
            <label for="user_type">Select User Type:</label>
            <select id="user_type" name="user_type" required>
                <option value="0">Staff</option>
                <option value="1">Student</option>
            </select>
            
            <!-- Submit Button -->
            <button type="submit">Register</button>            
        </form>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
