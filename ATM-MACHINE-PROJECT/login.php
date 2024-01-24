<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establish a MySQL database connection
$mysqli = new mysqli("localhost", "root", "", "atm db");

// Check for database connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve form data and sanitize it
$lAccNo = $mysqli->real_escape_string($_POST["lAccNo"]);
$lAccPin = $mysqli->real_escape_string($_POST["lAccPin"]);

// Query to check if the user exists
$query = "SELECT cust_id, cust_name, acc_no, balance FROM bank WHERE acc_no = '$lAccNo' AND pin = '$lAccPin'";
$result = $mysqli->query($query);

if ($result->num_rows === 1) {
    // User exists and the login details are correct
 echo "<script>alert('Logged In successful.');</script>";
    $row = $result->fetch_assoc();

    // Set user details in session variables
    session_start();
    $_SESSION['cust_id'] = $row['cust_id'];
    $_SESSION['cust_name'] = $row['cust_name'];
    $_SESSION['acc_no'] = $row['acc_no'];
    $_SESSION['balance'] = $row['balance'];
    
    header("Location: ATMhome.php");

    exit();
} else {
    // No user found or incorrect login details
    echo "<script>alert('Invalid account number or PIN. Please try again.');</script>";
    
}
