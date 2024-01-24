<?php
session_start(); // Start the session

// Check if the user is logged in and session variables are set
if (isset($_SESSION['cust_name']) && isset($_SESSION['balance']) && isset($_SESSION['acc_no'])) {
    $accno = $_SESSION['acc_no'];
    $custName = $_SESSION['cust_name'];
    $balance = $_SESSION['balance'];
} else {
    // Handle the case where the user is not logged in or session data is missing
    header('Location: login.php');
}

// Check if the deposit form is submitted
if (isset($_POST['dep-submit'])) {
    $amount = (float)$_POST['deposit-amt'];
    if ($amount > 0) {
        // Update the balance in the database
        $mysqli = new mysqli("localhost", "root", "", "atm db");
        $query = "UPDATE bank SET balance = balance + $amount WHERE acc_no = '$accno'";
        if ($mysqli->query($query) === true) {
            // Update the session variable with the new balance
            $balance += $amount;
            $_SESSION['balance'] = $balance;
            echo "<script>alert('Amount deposited successfully.');</script>";
        } else {
            echo "<script>alert('Error updating balance.');</script>";
        }
    } else {
        echo "<script>alert('Invalid amount. Please enter a positive value.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ATMdeposit</title>
    <link rel="stylesheet" href="ATM_DW_STYLE.css">
</head>
<body>
    <div class="center-div">
        <div id="portal">
            <h3 id="username"><?php echo "Welcome, " . $custName; ?></h3><br>
            <h3 id="totalBal"><?php echo "Your balance: " . $balance; ?></h3><br>

            <div id="deposit-portal">
                <h4>Deposit</h4><br>
                <form method="post">
                    <label>Enter Amount :</label><br>
                    <input type="text" name="deposit-amt" id="deposit-amt" class="textbox-style margin-button-12" required><br>
                    <button type="submit" name="dep-submit" id="dep-submit">Deposit</button>
                    <button type="submit" name="back" id="back-btn" onclick="back_home()" >Back</button>       
                </form>
                
                
            </div><br>
        </div>
    </div>
</body>

<script>
    var varbtn=document.getElementById("back-btn");

    function back_home()
    {
        window.location.href = "ATMhome.php";
    }
</script>
</html>
