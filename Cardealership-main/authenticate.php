<?php
session_start();
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'dealership';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['employeeid'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill both the employeeid and password fields!');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT EmployeeName, Password, Position FROM employee WHERE EmployeeID = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the employeeid is a string so we use "s"
	$stmt->bind_param('i', $_POST['employeeid']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($name, $password, $position);
        $stmt->fetch();
        // Account exists, now we verify the password.
        if ($_POST['password'] === $password) {
            // Verification success! User has logged-in!
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['id'] = $_POST['employeeid'];
            $_SESSION['name'] = $name;
            $_SESSION['position'] = $position;

            if($_SESSION['position'] == "Assistant"){
                header("location: assistant.php");
            }else if($_SESSION['position'] == "Salesman"){
                   header("location: salesman.php");
            }else if($_SESSION['position'] == "Manager"){
                   header("location: admin.php");
            }else if($_SESSION['position'] == "CEO"){
               header("location: admin.php");
            }


        } else {
            // Incorrect password
            echo 'Incorrect employeeid and/or password!';
        }
    } else {
        // Incorrect employeeid
        echo 'Incorrect employeeid and/or password!';
    }


	$stmt->close();
}
?>