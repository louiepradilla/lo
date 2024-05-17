<?php
// We need to use sessions, so we always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'dealership';
$db = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}


?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Admin Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
        <nav>
			<ul id="menu">
                <li><a href="#">Sales History</a></li>
				<li><a href="profile.php"><i class="fas fa-user-circle"></i> <?=$_SESSION['name']?></a></li>
				<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
			</ul>
		</nav>
		<div class="content">
            <br><br><br><br>
            <div class="cssform">
                <form id="signup" action="carperiod.php" method="post">
                    <div class="clearfix">
                    <label for="date1">From</label>
                    <input type="date" name="date1" id="date1" required>
                    </div>
                    <div class="clearfix">
                    <label for="date2">To</label>
                    <input type="date" name="date2" id="date2" required>
                    </div><br>
                    <input type="submit" name ="submit_button">
                </form>
                <div class="tnew">
                <h1>Select Month:</h1><br>
                <form id="signup" action="carperiod.php" method="post">
                    <div class="clearfix">
                        <label for="mothn">Select Month:</label>
                        <select name="month" id="month">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">Mars</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">Junne</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select> 
                    </div><br>
                    <input type="submit" name ="submit_button">
                </form>
            </div>
            </div>
		
        <div>
            <?php
            //Check if the information is submited
            if (isset($_POST['submit_button'])){
                if (isset($_POST['date1']) && isset($_POST["date2"])){

                    $date1 = mysqli_real_escape_string($db, $_POST["date1"]);
                    $date2 = mysqli_real_escape_string($db, $_POST["date2"]);

                    $table = mysqli_query($db,"SELECT *
                    FROM transactions
                    WHERE TheDate BETWEEN '$date1' AND '$date2' AND SoB = 'Sold';
                    ");

                    echo "<table border='1'>";
                                    
                    $i = 0;
                    while($row = $table->fetch_assoc()){
                        if ($i == 0) {
                            $i++;
                            echo "<tr>";
                            foreach ($row as $key => $value) {
                            echo "<th>" . $key . "</th>";
                            }
                            echo "</tr>";
                        }
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>" . $value . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>"; 

                    $check = mysqli_num_rows($table);
                    if ($check == 0){
                        echo "<h3> NO CAR WAS SOLD IN THIS PERIOD</h3>";
                    }

                    $table->close();

                }
            }
            ?>

        <?php

            if (isset($_POST['submit_button'])){
                if (isset($_POST['month'])){

                    $month = mysqli_real_escape_string($db, $_POST["month"]);

          
                    $table = mysqli_query($db,"SELECT * FROM transactions 
                    WHERE MONTH(TheDate) = '$month' AND SoB = 'Sold';");

                    echo "<table border='1'>";
                                    
                    $i = 0;
                    while($row = $table->fetch_assoc()){
                        if ($i == 0) {
                            $i++;
                            echo "<tr>";
                            foreach ($row as $key => $value) {
                            echo "<th>" . $key . "</th>";
                            }
                            echo "</tr>";
                        }
                        echo "<tr>";
                        foreach ($row as $value) {
                            echo "<td>" . $value . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>"; 

                    $check = mysqli_num_rows($table);
                    if ($check == 0){
                        echo "<h3> NO CAR WAS SOLD IN THIS PERIOD</h3>";
                    }

                    $table->close();

                }
            }
            ?>
        </div>
        </div>
	</body>
</html>


