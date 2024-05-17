<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Salesman page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
	<nav>
				<ul id="menu">
				<li><a href="#">Employees</a>
					<ul>
					<li><a href="viewemployee.php">View all employees</a></li>
					</ul>
				</li>
				<li><a href="#">Cars</a>
					<ul>
					<li><a href="view.php">View the inventory</a></li>
					<li><a href="viewsold.php">View Sold Cards</a></li>
					</ul>
				</li>
				<li><a href="#">Providers</a>
					<ul>
					<li><a href="viewproviders.php">View providers list</a></li>
					<li><a href="viewBestproviders.php">View Providers rec</a></li>
					</ul>
				</li>
				<li><a href="#">Customers</a>
					<ul>
					<li><a href="viewcustomers.php">Customers</a></li>
					<li><a href="viewBestcustomer.php">View Customers rec</a></li>
					</ul>
				</li>
				<li><a href="#">Support</a></li>
				<li><a href="profile.php"><i class="fas fa-user-circle"></i> <?=$_SESSION['name']?></a></li>
				<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
				</ul>
			</nav>
		<div class="content">
        <br><br><br><br>
			<h2>Salesman page</h2>
            <div class="buttonrow">
			<button type="button" class="button" onclick="window.location.href='sell.php';">Make a sell</button>
			<button type="button" class="button" onclick="window.location.href='buy.php';">Buy</button>
			<button type="button" class="button" onclick="window.location.href='carperiod.php';">Car sold in a range of time</button>
			<button type="button" class="button" onclick="window.location.href='mostexpensive.php';">Most expensive</button>
			</div>
			<div class="buttonrow">
			<button type="button" class="button" onclick="window.location.href='bestemployee.php';">Outstanding employees</button>
			<button type="button" class="button" onclick="window.location.href='employeerec.php';">Employees Records</button>
			<button type="button" class="button" onclick="window.location.href='profit.php';">Profit</button>
			</div>
		</div>

	</body>
</html>