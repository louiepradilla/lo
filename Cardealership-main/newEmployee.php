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
    if (isset($_POST['password']) && isset($_POST["name"]) && isset($_POST["gender"]) && isset($_POST["address"]) 
    && isset($_POST["phoneNO"]) && isset($_POST["position"]) && isset($_POST["salary"]) && isset($_POST["email"])){

        $password = mysqli_real_escape_string($db, $_POST["password"]);
        $name = mysqli_real_escape_string($db, $_POST["name"]);
        $gender = mysqli_real_escape_string($db, $_POST["gender"]);
        $address = mysqli_real_escape_string($db, $_POST["address"]);
        $phoneNO = mysqli_real_escape_string($db, $_POST["phoneNO"]);
        $position = mysqli_real_escape_string($db, $_POST["position"]);
        $salary = mysqli_real_escape_string($db, $_POST["salary"]);
        $email = mysqli_real_escape_string($db, $_POST["email"]);

        $sql = " CALL AddEmployee('$name', '$password', '$phoneNO', '$email', '$address',  '$salary', '$position', '$gender')";

        if (mysqli_query($db, $sql)){
            echo "<script>
                alert('New employee added');
                </script>";
                header("location:admin.php");
        } else {
            echo "<script>
                alert('Employee already exits');
                window.open(admin.php);
                </script>";
        }
    }
} 
?>

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
                <li><a href="admin.php">Admin Page</a></li>
                <li><a href="#">New employee</a></li>
				<li><a href="profile.php"><i class="fas fa-user-circle"></i> <?=$_SESSION['name']?></a></li>
				<li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
			</ul>
		</nav>
		<div class="content">
        <p>Please fill in this form to add a new employee.</p>
        <div class="cssform">
            <form id="signup" action="newEmployee.php" method="post">
                <div class="clearfix">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required>
                </div>
                <div class="clearfix">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                </div>
                <div class="clearfix">
                <label for="phoneNO">Phone Number</label>
                <input type="tel" name="phoneNO" id="phoneNO" required>
                </div>
                <div class="clearfix">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" required>
                </div>
                <div class="clearfix">
                <label for="address">Address</label>
                <input type="text" name= "address" id="address" required>
                </div>
                <div class="clearfix">
                <label for="salary">Salary</label>
                <input type="text" name="salary" id="salary" required>
                </div>
                <div class="clearfix">
                <label for="position">Position</label>
                <select name="position" id="position" required>
                    <option value="CEO">CEO</option>
                    <option value="Manager">Manager</option>
                    <option value="Salesman">Salesman</option>
                    <option value="Assistant">Assistant</option>
                </select>
                </div>
                <div class="clearfix">
                <label for="gender">Gender</label>
                <select name="gender" id="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                </div><br>
                <input type="submit" name ='submit_button'>
            </form>
        </div>
		</div>

</body>
</html>