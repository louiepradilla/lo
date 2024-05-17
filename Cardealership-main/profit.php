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
                <li><a href="#">Profit</a></li>
				<li><a href="profile.php"><i class="fas fa-user-circle"></i> <?=$_SESSION['name']?></a></li>
				<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
			</ul>
		</nav>
			</div>

		<div class="content">
			<br><br><br><br>
				<?php

					echo "<h2>Total Purchases</h2>";
					$table = mysqli_query($db,"SELECT SUM(Final_price) as 'Total spent'
                    FROM transactions
                    WHERE SoB LIKE '%Bou%';
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
							echo "<td>$" . $value . "</td>";
						}
						echo "</tr>";
					}
					echo "</table>"; 

					echo "<br>";


					echo "<h2>Total Sells</h2>";
					$table = mysqli_query($db,"SELECT SUM(Final_price) as 'Total earned'
                    FROM transactions
                    WHERE SoB LIKE '%Sold%';   
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
							echo "<td>$" . $value . "</td>";
						}
						echo "</tr>";
					}
					echo "</table>";

                    $soldd = $db->prepare("SELECT SUM(Final_price)
                    FROM transactions
                    WHERE SoB LIKE '%Sold%';");
                    $soldd->execute();
                    $soldd->bind_result($totalearned);
                    $soldd->fetch();
                    $soldd->close();

                    echo "<h2>Profit</h2>";
					$table = mysqli_query($db,"SELECT $totalearned - SUM(Final_price) as 'Total profit' FROM transactions WHERE SoB LIKE '%Bou%';   
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
							echo "<td>$" . $value . "</td>";
						}
						echo "</tr>";
					}
					echo "</table>"; 
				?>

        </div>
        <div>
                
            </div>
	</body>
</html>
