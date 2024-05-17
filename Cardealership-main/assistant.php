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
		<title>Assistant Page</title>
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
					<li><a href="viewcustomers.php">Customer List</a></li>
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
        <h2>Assistant page</h2>
		<div class="buttonrow">
			<button type="button" class="button" onclick="window.location.href='carperiod.php';">Car sold in a range of time</button>
			<button type="button" class="button" onclick="window.location.href='mostexpensive.php';">Most expensive</button>
			</div>
			<div class="buttonrow">
			<button type="button" class="button" onclick="window.location.href='bestemployee.php';">Outstanding employees</button>
			<button type="button" class="button" onclick="window.location.href='employeerec.php';">Employees Records</button>
			<button type="button" class="button" onclick="window.location.href='profit.php';">Profit</button>
			</div>
		</div>
		</div>
		



		



		<script>
			function addNewEmployee() {
				window.open("newEmployee.php");
			}

			function dropEmployee() {
				window.open("dropemployee.php");
			}

			function transactionSearch() {
				window.open("transactionSearch.php");
			}

			function sell() {
				window.open("sell.php");
			}

			function buy() {
				window.open("buy.php");
			}

			function view() {
				window.open("view.php");
			}

			function make() {
				window.open("make.php");
			}

			function carperiod() {
				window.open("carperiod.php");
			}

			function mostexpensive() {
				window.open("mostexpensive.php");
			}

			function bestemployee() {
				window.open("bestemployee.php");
			}

			function employeerec() {
				window.open("employeerec.php");
			}

			function profit() {
				window.open("profit.php");
			}

			
		</script> 
	</body>
</html>