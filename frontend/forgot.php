<?php
    session_start(); // Start the session

    // Check for error message from previous attempts
    if (isset($_SESSION['forgot_error'])) {
        echo '<p class="error-message">' . $_SESSION['forgot_error'] . '</p>';
        unset($_SESSION['forgot_error']); // Clear the session variable
    }

    // Check for success message from previous attempts
    if (isset($_SESSION['forgot_success'])) {
        echo '<p class="success-message">' . $_SESSION['forgot_success'] . '</p>';
        unset($_SESSION['forgot_success']); // Clear the session variable
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="forgot.css">
</head>
<body>
<h1 class="shifu">Shifārasu</h1>
  <div class="container">
    <h2>Forgot Password</h2>
    <form id="forgotForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" name="newPassword" required>
      </div>
      <div class="form-group">
        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" id="confirmPassword" name="confirmPassword" required>
      </div>
      <button type="submit">Reset Password</button><hr>
      <button type="submit" class="tologin" onclick="location.href='index.php'">Go to login</button>
    </form>
  </div>
</body>
</html>

<?php
// Your PHP code for forgot password functionality here // Start the session

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define variables and initialize with empty values
    $email = $newPassword = $confirmPassword = "";

    // Process form data when the form is submitted
    $email = trim($_POST["email"]);
    $newPassword = trim($_POST["newPassword"]);
    $confirmPassword = trim($_POST["confirmPassword"]);

    // Validate input fields (you can add more validation as needed)
    if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
        // Set error message and redirect back to the forgot password page
        $_SESSION['forgot_error'] = "All fields are required.";
        header("Location: forgot.php");
        exit();
    } elseif ($newPassword !== $confirmPassword) {
        // Set error message and redirect back to the forgot password page
        $_SESSION['forgot_error'] = "Passwords do not match.";
        header("Location: forgot.php");
        exit();
    } else {
        // Hash the new password
        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

        // Prepare an SQL statement to update the user's password in the database
        $sql = "UPDATE userdata SET pswd = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);

        // Execute the update query
        if ($stmt->execute()) {
            // Set success message and redirect to login page
            $_SESSION['forgot_success'] = "Password reset successfully. You can now login with your new password.";
            header("Location: forgot.php"); // Redirect to login page
            exit();
        } else {
            // Set error message if update query fails
            $_SESSION['forgot_error'] = "Password reset failed. Please try again later.";
            header("Location: forgot.php");
            exit();
        }
    }
}

// Close the database connection
$conn->close();
?>

   
?>
