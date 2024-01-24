<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Establish a MySQL database connection
$mysqli = new mysqli("localhost", "root", "", "atm db");

// Check for database connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve form data and sanitize it
$rAccName = $mysqli->real_escape_string($_POST["rAccName"]);
$rAccNo = $mysqli->real_escape_string($_POST["rAccNo"]);
$rAccPin = $mysqli->real_escape_string($_POST["rAccPin"]);
$rConAccPin = $mysqli->real_escape_string($_POST["rConAccPin"]);
$initialDep = $mysqli->real_escape_string($_POST["initial-dep"]);

// Validation checks
if (preg_match("/^[a-zA-Z]+$/", $rAccName)) {
    if (is_numeric($rAccNo) && strlen($rAccNo) === 13) {
        if (strlen($rAccPin) == 4 && strlen($rConAccPin) == 4) {
            if (is_numeric($rAccPin) && is_numeric($rConAccPin)) {
                if ($rAccPin === $rConAccPin) {
                    if ($initialDep >= 500) {
                        // Use prepared statements for security
                        $query = "INSERT INTO bank (cust_name, acc_no, pin, balance) VALUES (?, ?, ?, ?)";
                        $stmt = $mysqli->prepare($query);
                        $stmt->bind_param("sssi", $rAccName, $rAccNo, $rAccPin, $initialDep);
                        $stmt->execute();

                        if ($stmt->affected_rows > 0) {
                            // Registration successful
                            echo "<script>alert('Registration successful.');</script>";

                            // Retrieve user details after successful registration
                            $getUserDetailsQuery = "SELECT cust_id, cust_name, acc_no, balance FROM bank WHERE acc_no = ?";
                            $stmt = $mysqli->prepare($getUserDetailsQuery);
                            $stmt->bind_param("s", $rAccNo);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();

                                // Set session variables
                                $_SESSION['cust_id'] = $row['cust_id'];
                                $_SESSION['cust_name'] = $row['cust_name'];
                                $_SESSION['acc_no'] = $row['acc_no'];
                                $_SESSION['balance'] = $row['balance'];

                                // Redirect to home page
                                header("Location: ATMhome.php");
                                exit();
                            } else {
                                echo "User details not found.";
                            }
                        } else {
                            echo "Error: " . $stmt->error;
                        }
                    } else {
                        echo "<script>alert('Minimum Initial Deposit Amount is RS 500.');</script>";
                    }
                } else {
                    echo "<script>alert('Your Pin doesn\'t match.');</script>";
                }
            } else {
                echo "<script>alert('Your Pin should contain only digits.');</script>";
            }
        } else {
            echo "<script>alert('Your Pin should contain 4 digits.');</script>";
        }
    } else {
        echo "<script>alert('Re-Enter Your Account No Correctly.');</script>";
    }
} else {
    echo "<script>alert('Your Name should contain only alphabets.');</script>";
}

// Close the database connection
$mysqli->close();
?>
