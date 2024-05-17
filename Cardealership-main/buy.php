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

//check if all the information is summited
if (isset($_POST['submit_button'])){
    if (isset($_POST["name"]) && isset($_POST["address"]) && isset($_POST["phoneN"]) && isset($_POST["ptype"]) && isset($_POST["email"]) && isset($_POST["vin"])
    && isset($_POST["date"]) && isset($_POST["make"]) && isset($_POST["model"]) && isset($_POST["theyear"]) && isset($_POST["miles"]) && isset($_POST["price"]) && isset($_POST["isnew"])){

        $name = mysqli_real_escape_string($db, $_POST["name"]);
        $address = mysqli_real_escape_string($db, $_POST["address"]);
        $phoneN = mysqli_real_escape_string($db, $_POST["phoneN"]);
        $ptype = mysqli_real_escape_string($db, $_POST["ptype"]);
        $email = mysqli_real_escape_string($db, $_POST["email"]);
        $vin = mysqli_real_escape_string($db, $_POST["vin"]);
        $date = mysqli_real_escape_string($db, $_POST["date"]);
        $make = mysqli_real_escape_string($db, $_POST["make"]);
        $model = mysqli_real_escape_string($db, $_POST["model"]);
        $theyear = mysqli_real_escape_string($db, $_POST["theyear"]);
        $miles = mysqli_real_escape_string($db, $_POST["miles"]);
        $isnew = mysqli_real_escape_string($db, $_POST["isnew"]);
        $price = mysqli_real_escape_string($db, $_POST["price"]);

        $id = $_SESSION['id'];

        //Call the procedure
        $sql = "CALL MakePurchase('$name', '$address', '$phoneN', '$ptype', '$email', '$make', '$model', $theyear, '$vin', $price, $miles, '$isnew', '$date', $id)";
        if (mysqli_query($db, $sql)){
            echo "<script>
                alert('New car added');
                </script>";
                //header("location:admin.php");
        } else {
            echo "<script>
                alert('Somethong went wrong');
                window.open(admin.php);
                </script>";
        }

        /////////////////////////////////////////////////////////////////////////


    }
}


?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Buy</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
        <nav>
			<ul id="menu">
                <li><a href="#">New Car</a></li>
				<li><a href="profile.php"><i class="fas fa-user-circle"></i> <?=$_SESSION['name']?></a></li>
				<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
			</ul>
		</nav>
        <div class="content">
            <br><br><br><br>
            <h3>Enter seller and car information</h3>
            <div class="cssform">
                <form id="signup" action="buy.php" method="post">
                    <div class="clearfix">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" required>
                    </div>
                    <div class="clearfix">
                    <label for="address">Address</label>
                    <input type="text" name= "address" id="address" required>
                    </div>
                    <div class="clearfix">
                    <label for="phoneN">Phone Number</label>
                    <input type="text" name="phoneN" id="phoneN" required>
                    </div>
                    <div class="clearfix">
                    <label for="ptype">Provider Type</label>
                    <select name="ptype" id="ptype" required>
                        <option value="Manufacture">Manufacture</option>
                        <option value="Auction">Auction</option>
                        <option value="Individual">Individual</option>
                    </select>
                    </div>
                    <div class="clearfix">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required>
                    </div>
                    <div class="clearfix">
                    <label for="make">Make</label>
                    <input type="text" name="make" id="make" required>
                    </div>
                    <div class="clearfix">
                    <label for="model">Model</label>
                    <input type="text" name="model" id="model" required>
                    </div>
                    <div class="clearfix">
                    <label for="theyear">Year</label>
                    <input type="text" name="theyear" id="theyear" required>
                    </div>
                    <div class="clearfix">
                    <label for="price">Price</label>
                    <input type="text" name="price" id="price" required>
                    </div>
                    <div class="clearfix">
                    <label for="vin">VIN</label>
                    <input type="text" name="vin" id="vin" required>
                    </div>
                    <div class="clearfix">
                    <label for="miles">Miles</label>
                    <input type="text" name="miles" id="miles" required>
                    </div>
                    <div class="clearfix">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" required>
                    </div>
                    <div class="clearfix">
                    <label for="isnew">Is new?</label>
                    <select name="isnew" id="isnew" required>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                    </div>
                    <input type="submit" name ="submit_button">
                </form>
            </div>
        </div>

    </body>
</html>
