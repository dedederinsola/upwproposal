<?php

//Start a session if one hasn't been, and enable error reporting
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Database connection parameters
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'proposal';

// Connect to the database
$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}

$email = $_POST['email'];
$password = $_POST['password'];


class User{
    public function authenticate($conn, $email, $password) {
        $login = "SELECT * FROM proposal WHERE email=? AND password=?";

        //Prepare the statement
        $stmt = mysqli_prepare($conn, $login);
        if (!$stmt) {
            die('Error in preparing the statement: ' . mysqli_error($conn));
        }

        // Bind the parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, 'ss', $email, $password);

        // Execute the prepared statement
        mysqli_stmt_execute($stmt);

        // Get the result from the prepared statement
        $result = mysqli_stmt_get_result($stmt);

        // Check if there is a row returned
        if (mysqli_num_rows($result) == 1) {
            header('Location: dashboard.php');
        exit;

        } else {
        // Invalid username or password
            $error = "Invalid email or password";
            include("proposal.html");
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);

        // Close the database connection
        mysqli_close($conn);

    }

    public function register($conn, $email, $password) {
        $reg = 'INSERT INTO proposal (email, password) VALUES (?, ?)';

        //Prepare the statement
        $stmt = mysqli_prepare($conn, $reg);
        if (!$stmt) {
            die('Error in preparing the statement: ' . mysqli_error($conn));
        }

        // Bind the parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, 'ss', $email, $password);

        // Execute the prepared statement
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_execute($stmt)){
            header('Location: dashboard.php');
        }        
    }

}

$user = new User();

if ($_POST['action'] === 'login') {
    $user->authenticate($conn, $email, $password);
} elseif ($_POST['action'] === 'register') {
    $user->register($conn, $email, $_password);
}

?>