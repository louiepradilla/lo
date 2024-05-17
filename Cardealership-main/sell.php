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


if (isset($_POST['submit_button'])){
    if (isset($_POST["name"]) && isset($_POST["address"]) && isset($_POST["phoneNO"]) && isset($_POST["email"]) && isset($_POST["vin"])
    && isset($_POST["date"]) && isset($_POST["price"])){

        $name = mysqli_real_escape_string($db, $_POST["name"]);
        $address = mysqli_real_escape_string($db, $_POST["address"]);
        $phoneNO = mysqli_real_escape_string($db, $_POST["phoneNO"]);
        $email = mysqli_real_escape_string($db, $_POST["email"]);
        $vin = mysqli_real_escape_string($db, $_POST["vin"]);
        $date = mysqli_real_escape_string($db, $_POST["date"]);
        $price = mysqli_real_escape_string($db, $_POST["price"]);

        $id = $_SESSION['id'];
        ///////////////////////////////////////////////////////////////////////
        $sql = "CALL MakeSale('$name', '$address', '$phoneNO', '$email', '$vin', $id, '$date', $price)";
        if (mysqli_query($db, $sql)){
            echo "<script>
                alert('Car Sold');
                </script>";
                //header("location:admin.php");
        } else {
            echo "<script>
                alert('Somethong went wrong');
                window.open(admin.php);
                </script>";
        }
        ////////////////////////////////////////////////////////////////////////////

    }
} 
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Sell</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
    
    <body class="loggedin">
        <nav>
			<ul id="menu">
                <li><a href="#">New Sell</a></li>
				<li><a href="profile.php"><i class="fas fa-user-circle"></i> <?=$_SESSION['name']?></a></li>
				<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
			</ul>
		</nav>
        <div class="content">
            <br><br><br><br>	
            <h3>Customer Information</h3>
            <div class="cssform">
                <form id="signup" action="sell.php" method="post">
                    <div class="clearfix">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" required>
                    </div>
                    <div class="clearfix">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" required>
                    </div>
                    <div class="clearfix">
                    <label for="phoneNO">Phone Number</label>
                    <input type="text" name="phoneNO" id="phoneNO" required>
                    </div>
                    <div class="clearfix">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                    </div>
                    <div class="clearfix">
                    <label for="vin">VIN</label>
                    <input type="text" name="vin" id="vin" required>
                    </div>
                    <div class="clearfix">
                    <label for="date">Date</label>
                    <input type="date" name="date" id="date" required>
                    </div>
                    <div class="clearfix">
                    <label for="price">Price</label>
                    <input type="text" name="price" id="price" required>
                    </div><br>
                    <input type="submit" name ="submit_button">
                </form>
            </div>
        </div>
    </body>
</html>

