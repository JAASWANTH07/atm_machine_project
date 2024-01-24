<?php
session_start(); // Start the session

// Check if the user is logged in and session variables are set
if (isset($_SESSION['cust_name']) && isset($_SESSION['balance'])) {
    $custName = $_SESSION['cust_name'];
    $balance = $_SESSION['balance'];
} else {
    // Handle the case where the user is not logged in or session data is missing
    // You can redirect the user to a login page or display an error message
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATMhome</title>
    <link rel="stylesheet" href="ATMhome.css">
</head>
<body>
    <div class="center-div">
        <div id="portal">
            <h3 id="username"><?php echo "Welcome, " . $custName; ?></h3><br>
            <h3 id="totalBal"><?php echo "Your balance: " . $balance; ?></h3><br>

            <div id="options">
                <h3>Please select an option</h3><br>
                <a href="ATMdeposit.php"><button id="deposit-btn">Deposit</button></a>
                <a href="ATMwithdraw.php"><button id="withdraw-btn">Withdraw</button></a>
                <br> <!-- Add a line break to separate the button groups -->
                <a href="ATMchangepin.php"><button id="change-btn">Change Pin</button></a>
                <a href="ATMmoney.php"><button id="change-btn">Money Transfer</button></a>
            </div>
        </div>
    </div>
</body>
</html>
