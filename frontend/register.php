<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Page</title>
<link rel="stylesheet" href="index.css">
</head>
<body>
<h1 class="shifu">Shifārasu</h1>
<div class="container">
    <h2>Register</h2>
    <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Register</button>
    </form>
</div>

<?php
// Include the database connection file
$db_host = "localhost:4307"; // Hostname of the database 
$db_user = "root"; // Username to access the database
$db_password = ""; // Password to access the database
$db_name = "college_project"; // Name of the database

// Attempt to connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define variables and initialize with empty values
    $username = $email = $password = "";

    // Process form data when the form is submitted
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate input fields (you can add more validation as needed)
    if (empty($username) || empty($email) || empty($password)) {
        // Redirect back to the registration page with an error message
        header("Location: register.php?error=emptyfields");
        exit();
    } else {
        // Prepare an SQL statement to check if the username already exists
        $sql = "SELECT id FROM userdata WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the username already exists
        if ($result->num_rows > 0) {
            // Redirect back to the registration page with an error message
            header("Location: register.php?error=usernametaken");
            exit();
        } else {
            // Prepare an SQL statement to insert the new user into the database
            $sql = "INSERT INTO userdata (username, email, pswd) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            $stmt->execute();

            // Redirect to the login page with a success message
            header("Location: index.php?success=registered");
            exit();
        }
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
</body>
</html>
