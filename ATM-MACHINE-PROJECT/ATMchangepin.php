<?php
session_start();

if (isset($_SESSION['cust_name']) && isset($_SESSION['balance']) && isset($_SESSION['acc_no'])) {
    $accno = $_SESSION['acc_no'];
    $custName = $_SESSION['cust_name'];
    $balance = $_SESSION['balance'];
} else {
    // Handle the case where the user is not logged in or session data is missing
    header('Location: login.php');
}

if (isset($_POST['change-pin'])) {
    $newpin = $_POST['new-pin'];
    $newconpin = $_POST['con-pin'];

    if (strlen($newpin) == 4 && strlen($newconpin) == 4) {
        if (is_numeric($newpin) && is_numeric($newconpin)) {
            $newpin = (int)$newpin;
            $newconpin = (int)$newconpin;

            if ($newpin !== $newconpin) { // Changed !== operator here
                echo "<script>alert('Your PINs don\'t match!!!');</script>";
            } else {
                $mysqli = new mysqli("localhost", "root", "", "atm db");

                if ($mysqli->connect_error) {
                    die("Connection failed: " . $mysqli->connect_error);
                }

                $query = "UPDATE bank SET pin = $newpin WHERE acc_no = '$accno'";
                if ($mysqli->query($query) === true) {
                    $_SESSION['pin'] = $newpin;
                    echo "<script>alert('Your PIN was updated successfully.');</script>";
                } else {
                    echo "<script>alert('Error updating PIN.');</script>";
                }
            }
        } else {
            echo "<script>alert('Your PIN should not contain alphabets.');</script>";
        }
    } else {
        echo "<script>alert('Your Pin should contain 4 digits.');</script>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Pin</title>
    <link rel="stylesheet" href="ATMpin.css">
</head>
<body>
    <div class="center-div">
        <div id="portal">
            <h3 id="username"><?php echo "Welcome, " . $custName; ?></h3><br>
            <h3 id="totalBal"><?php echo "Your balance : " . $balance; ?></h3><br>

            <div id="deposit-portal">
                <h4>Change Pin</h4><br>
                <form method="post">
                    <label>Enter New Pin :</label><br>
                    <input type="text" name="new-pin" id="new-pin" maxlength="4" class="textbox-style margin-button-12" required><br>
                    <label>Confirm New Pin :</label><br>
                    <input type="text" name="con-pin" id="con-pin" maxlength="4" class="textbox-style margin-button-12" required><br>
                    <button type="submit" name="change-pin" id="change-pin">Change</button>
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