<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Region Colleges</title>
<link rel="stylesheet" href="rv.css"> <!-- Link to your custom CSS file -->
</head>
<body>
<div class="navbar">
    <a href="form.php" title="Go Back" class="back-link">&#8592;</a>
    <a href="#" title="Go Forward" class="forward-link">&#8594;</a>
    <a href="home.php" class="page-title"><b>Shifārasu</b></a>
</div>

<div class="container">
<?php
// Set connection variables
$server = "localhost:4307";
$username = "root";
$password = "";
$dbname = "college_project";

// Create a database connection
$con = mysqli_connect($server, $username, $password, $dbname);

// Check for connection success
if(!$con){
    die("Connection to the database failed due to" . mysqli_connect_error());
}

// Fetch data from RegionCollegeView
$query = "SELECT region, colleges_in_region FROM RegionCollegeView";
$result = mysqli_query($con, $query);

// Check if query results are available
if($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<div class='card'>";
        echo "<div class='card-content'>";
        echo "<h2>{$row['region']}</h2>";

        // Split the values by comma
        $colleges = explode(',', $row['colleges_in_region']);
        
        // Output each value as a bullet point
        echo "<ul>";
        foreach ($colleges as $college) {
            echo "<li>$college</li>";
        }
        echo "</ul>";
        
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<div class='error-message'>No data found</div>";
}

// Close the database connection
mysqli_close($con);
?>
</div>
<hr>
<div class="contact-about">
    <div class="about">
        <h2>About Us</h2>
        <p>Aishwarya MN, Anagha Ananth</p>
    </div>
    <div class="contact">
        <h2>Contact</h2>
        <p>mnaishwarya15@gmail.com,anaghaananth@gmail.com</p>
    </div>
</div>

</body>
</html>
