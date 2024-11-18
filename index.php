<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Page</title>
<link rel="stylesheet" href="index.css"> <!-- Include index.css -->
</head>
<body><br>
<h1 class="shifu">Shifārasu</h1>
<div class="container">
    <h2>Login</h2>
    <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="pswd" required>
        </div>
        <button type="submit">Login</button>
    </form><hr>
    <div class="options">
        <a href="forgot.php">Forgot Password</a> <b>|</b>
        <a href="register.php">Register</a>
    </div>
    <p id="error" class="error-message"></p>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    
    $db_host = "localhost:4307"; // Hostname of the database server
    $db_user = "root"; // Username to access the database
    $db_password = ""; // Password to access the database
    $db_name = "college_project"; // Name of the database

    // Attempt to connect to the database
    $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process form data
    $username = trim($_POST["username"]);
    $password = trim($_POST["pswd"]); // Use "pswd" instead of "password"

    // Prepare and execute SQL statement to retrieve user data
    $sql = "SELECT * FROM userdata WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username); // Check both username and email
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, verify password
        $row = $result->fetch_assoc();
        $hashed_password_from_db = $row['pswd'];
        var_dump($hashed_password_from_db); // Debug: Output hashed password from the database
            // User found, verify password
            $stored_hashed_password = $row['pswd'];
        
            // Generate a hash from a known password for comparison
            $known_password = 'test'; // Change this to a known password
            $known_hash = password_hash($known_password, PASSWORD_DEFAULT);
        
            // Check if the stored hashed password matches the known hash
            // if (password_verify($known_password, $stored_hashed_password)) {
            //     echo "<script>alert('Stored hash matches known hash');</script>";
            // } else {
            //     echo "<script>alert('Stored hash does not match known hash');</script>";
            // }
        
        if (password_verify($password, $hashed_password_from_db)) {
            // Password matches, redirect to home page or desired location
            echo "<script>alert('Logged in');</script>";
            header("Location: home.php?success=login");
            exit();
        } else {
            // Password doesn't match
            echo "<script>alert('Incorrect password');</script>";
        }
    } else {
        // User not found
        echo "<script>alert('User not found');</script>";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>


<script src="script.js"></script>
</body>
</html>
