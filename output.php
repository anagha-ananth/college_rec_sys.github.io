<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Result</title>
<link rel="stylesheet" href="output.css">
</head>
<body>
<div class="navbar">
    <a href="form.php" title="Go Back" class="back-link">&#8592;</a> <!-- &#8592; is the left arrow symbol -->
    <a href="#" title="Go Forward" class="forward-link">&#8594;</a> <!-- &#8594; is the right arrow symbol -->
    <a href="home.php" class="page-title"><b>Shifārasu</b></a>
    <!-- <a href="#" title="Login" class="nav-link">Login</a>
    <a href="#" title="Sign Up" class="nav-link">Sign Up</a> -->
     <!-- <div class="profile-symbol"><img src="star.png"/></div>// Profile symbol -->
</div>
<?php
session_start();

// Check if form is submitted
if(isset($_POST['sname'], $_POST['rank'], $_POST['catid'], $_POST['coname'], $_POST['preregion'])){
    // Handle form submission
    // Your form submission handling logic here...
    // $_SESSION['form_submitted'] = true;
    // Redirect to the same page using GET method to display the results
    header("Location: output.php?" . http_build_query($_POST));
    exit(); // Terminate the current script
}
?>

<div class="container">
<?php
// Display form data first
if(isset($_GET['sname'], $_GET['rank'], $_GET['catid'], $_GET['coname'], $_GET['preregion'])) {
    echo "<div class='form-data'>";
    echo "<h2>Form Data</h2>";
    echo "<p><strong>Name:</strong> {$_GET['sname']}</p>";
    echo "<p><strong>Rank:</strong> {$_GET['rank']}</p>";
    echo "<p><strong>Category ID:</strong> {$_GET['catid']}</p>";
    echo "<p><strong>Course Name:</strong> {$_GET['coname']}</p>";
    echo "<p><strong>Preferred Region:</strong> {$_GET['preregion']}</p>";
    echo "</div>";
}

// Check if form is submitted
if(isset($_GET['sname'], $_GET['rank'], $_GET['catid'], $_GET['coname'], $_GET['preregion'])){
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

    // Check if student can submit form more than 4 times
    // $check_query = "SELECT COUNT(*) AS form_count FROM student WHERE rank = '{$_GET['rank']}' AND sname = '{$_GET['sname']}'";
    // $check_result = mysqli_query($con, $check_query);
    // $row = mysqli_fetch_assoc($check_result);
    // $form_count = $row['form_count'];

    // if($form_count > 4) {
    //     echo "<div class='error-message'>You have already submitted the form 4 times. You cannot submit it again.</div>";
    // } else {
        // Collect post variables
        $rank = $_GET['rank'];
        $sname = $_GET['sname'];
        $catid = $_GET['catid'];
        $coname = $_GET['coname'];
        $preregion = $_GET['preregion'];

        $sql = "INSERT INTO `student` (`rank`, `sname`, `catid`, `coname`, `preregion`) VALUES ('$rank', '$sname', '$catid', '$coname', '$preregion')";

        // Execute the query
        try {
            // Execute the query
            if ($con->query($sql) === TRUE) {
                $insert = true;
            } else {
                echo "Error: " . $sql . "<br>" . $con->error;
            }
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = 'form.php';</script>";
        }
       // Fetch $sname and $rank again for using in SQL query
        $sname = mysqli_real_escape_string($con, $sname);
        $rank = mysqli_real_escape_string($con, $rank);

        // SQL query to fetch data from student, category, course, cousre_b, and college tables
        $query = "SELECT clg.cname, clg.cloc, clg.region,clg.chyperlink
        FROM student s
        JOIN category cat ON s.catid = cat.catid
        JOIN course crs ON cat.catid = crs.catid
        JOIN cousre_b cb ON s.coname = cb.coname AND crs.coname = cb.coname
        JOIN college clg ON clg.cid = crs.cid AND s.preregion = clg.region
        WHERE s.rank <= crs.cocutoff 
          AND s.catid = cat.catid 
          AND cat.catid = crs.catid 
          AND s.coname = cb.coname 
          AND crs.coname = cb.coname 
          AND clg.cid = crs.cid 
          AND s.preregion = clg.region 
          AND s.sname = '$sname' 
          AND s.rank = '$rank'
          AND clg.region = '{$_GET['preregion']}'
          ORDER BY crs.cocutoff ASC";

        // Execute the query
        $result = mysqli_query($con, $query);

        // Check if query results are available
        if($result && mysqli_num_rows($result) > 0) {
            echo "<h1>Here are colleges recommended for your data</h1>"; // Added h1 tag
            echo "<div class='card-container'>";
            $count=1;
            while($row = mysqli_fetch_assoc($result)) {
                echo "<div class='card'>";
                echo "<div class='card-content'><strong>{$count}.College Name:" . $row["cname"] . "</strong></div>"; // College name in bold
                echo "<div class='card-content'>Location: " . $row["cloc"] . "</div>";
                echo "<div class='card-content'>Region: " . $row["region"] . "</div>";
                echo "<div class='card-content'>Website:<a href='" . $row["chyperlink"] . "'> " . $row["chyperlink"] . "</a></div>";

                echo "<br>";
                //      echo "<div class='save-result'>";
                //   echo "<span>&#9734;</span>"; //<!-- Star symbol (Unicode: &#9734;) -->
                //     echo "</div>";
                // Save Result
                echo "</div>";
                $count++;
            }
            echo "</div>"; // Close card-container
        } else {
            echo "<div class='error-message'>No results found for your data</div>";
        }
    }

    // Close the database connection
    mysqli_close($con);
// }
?>
</div>
<hr><br>
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
