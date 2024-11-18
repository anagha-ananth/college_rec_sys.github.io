<?php
$insert = false;
if(isset($_POST['sname'], $_POST['rank'], $_POST['catid'], $_POST['coname'], $_POST['preregion'])){
    // Set connection variables
    $server = "localhost:4307";
    $username = "root";
    $password = "";
    $dbname = "college_project";

    // Create a database connection
    $con = mysqli_connect($server, $username, $password, $dbname);

    // Check for connection success
    if(!$con){
        die("connection to this database failed due to" . mysqli_connect_error());
    }

    // Collect post variables
    $rank = $_POST['rank'];
    $sname = $_POST['sname'];
    $catid = $_POST['catid'];
    $coname = $_POST['coname'];
    $preregion = $_POST['preregion'];

    $con->close();
}
$server = "localhost:4307";
$username = "root";
$password = "";
$dbname = "college_project";

// Create a new connection
$con = mysqli_connect($server, $username, $password, $dbname);

// Check for connection success
if(!$con){
    die("connection to this database failed due to" . mysqli_connect_error());
}

// SQL query to fetch data from student, category, and college tables
$query = "SELECT s.rank,s.sname,cat.catname,s.coname,s.preregion,clg.cname,crs.coname,clg.region
FROM student s
JOIN category cat ON s.catid=cat.catid
JOIN course crs ON cat.catid=crs.catid
JOIN cousre_b cb ON s.coname=cb.coname AND crs.coname=cb.coname
JOIN college clg ON clg.cid=crs.cid AND s.preregion=clg.region

WHERE s.rank<= crs.cocutoff AND s.catid=cat.catid AND cat.catid=crs.catid AND 
s.coname=cb.coname AND crs.coname=cb.coname AND
clg.cid=crs.cid AND s.preregion=clg.region
ORDER BY s.rank ASC";

// Execute the query
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home Page</title>
  <link rel="stylesheet" href="form.css">
</head>
<body >
<div class="navbar">
    <a href="home.php" title="Go Back" class="back-link">&#8592;</a> <!-- &#8592; is the left arrow symbol -->
    <a href="output.php" title="Go Forward" class="forward-link">&#8594;</a> <!-- &#8594; is the right arrow symbol -->
    <a href="home.php" class="page-title"><b>Shifārasu</b></a>
    <!-- <a href="#" title="Login" class="nav-link">Login</a>
    <a href="#" title="Sign Up" class="nav-link">Sign Up</a> -->
    <!-- <div class="profile-symbol"><img src="star.png"/></div> Profile symbol -->
</div>
</div>
  <div class="container">
    <h2>Enter your details</h2>
    <form id="applicationForm" method="post" action="output.php">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="sname" required>
    </div>
    <div class="form-group">
        <label for="cetRank">CET Rank:</label>
        <input type="number" id="cetRank" name="rank" required min="1" >
    </div>
    <div class="form-group">
        <label for="category">Category:</label>
        <select id="category" name="catid">
            <option value="11">GM</option>
            <option value="22">OBC</option>
            <option value="33">SC/ST</option>
        </select>
    </div>
    <div class="form-group">
        <label for="preferredCourse">Preferred Course:</label>
        <select id="preferredCourse" name="coname">
        <option value="CE Civil">CE Civil</option>
            <option value="CS Computers">CS Computers</option>
            <option value="EC Electronics">EC Electronics</option>
            <option value="EE Electrical">EE Electricals</option>
            <option value="IE Info.Science">IE Info.Science</option>
            <option value="ME Mechanical">ME Mechanical</option>
            <!-- Add more options as needed -->
        </select>
    </div>
    <div class="form-group">
        <label for="preferredRegion">Preferred Region:</label>
        <select id="preferredRegion" name="preregion">
            <option value="BENGALURU">Bangalore</option>
            <option value="MYSURU">Mysore</option>
            <option value="KALBURGI">Kalburgi</option>
            <option value="BELGAVI">Belgavi</option>
        </select>
    </div>
    <button type="submit" name="submit">Submit</button><br>
    <p>To check the cities that come under each region <a href="region_view.php">Click here</a>
</form>
  </div><hr><br>
  <div class="contact-about">
    <div class="about">
        <!-- About content here -->
        <h2>About Us</h2>
        <p>Aishwarya MN,  Anagha Ananth</p>
    </div>
    <div class="contact">
        <!-- Contact content here -->
        <h2>Contact</h2>
        <p>mnaishwarya15@gmail.com,anaghaananth@gmail.com</p>
    </div>
</div>
  
</body>
</html>
