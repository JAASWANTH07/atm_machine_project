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

if (isset($_POST['transfer'])) 
{
    $recipient_accno=$_POST['acc-no'];
    $recipient_name=$_POST['rec-name'];
    $amount=(float)$_POST['amount'];

    if($amount > 0)
    {
        if(is_numeric($recipient_accno))
        {
            $mysqli = new mysqli("localhost", "root", "", "atm db");

            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            $query = "SELECT cust_id, cust_name, acc_no, balance FROM bank WHERE acc_no = '$recipient_accno' AND cust_name = '$recipient_name'";
            $result = $mysqli->query($query);

            if ($result->num_rows === 1) 
            {
                
                        $mysqli = new mysqli("localhost", "root", "", "atm db");
            
                        if ($mysqli->connect_error) {
                            die("Connection failed: " . $mysqli->connect_error);
                        }
                        
                        if($balance >= $amount)
                        {
                            $balance = $balance - $amount;
                            $_SESSION['balance'] = $balance;
                            $query = "UPDATE bank SET balance = $balance WHERE acc_no = '$accno' AND cust_name='$custName'"; 
                            if ($mysqli->query($query) === true) {
            
                                $query = "UPDATE bank SET balance = balance + $amount WHERE acc_no = '$recipient_accno' AND cust_name='$recipient_name'";
            
                                if ($mysqli->query($query) === true) {
                                    echo "<script>alert('Amount transfered successfully.');</script>";
                                }
                                else{
                                    echo "<script>alert('Transaction Failed');</script>";
                                }
            
                           
                            } else {
                                echo "<script>alert('Error Updating Balance.');</script>";
                            }
                        } 

            }
            else
            {
                echo "<script>alert('Recipient Account No not found!!!');</script>";
            }

        }
        else{
            echo "<script>alert('Recipient Account No should contain only digits.');</script>";
        }
    }
    else{
        echo "<script>alert('Enter Valid Amount.');</script>";
    }
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Transfer</title>
    <link rel="stylesheet" href="ATMpin.css">
</head>
<body>
    <div class="center-div">
        <div id="portal">
            <h3 id="username"><?php echo "Welcome, " . $custName; ?></h3><br>
            <h3 id="totalBal"><?php echo "Your balance : " . $balance; ?></h3><br>

            <div id="deposit-portal">
                <h4>Money Transfer</h4><br>
                <form method="post">
                    <label>Enter Recipient Account No :</label><br>
                    <input type="text" name="acc-no" id="acc-no" maxlength="13" class="textbox-style margin-button-12" required><br>
                    <label>Enter Recipient Name :</label><br>
                    <input type="text" name="rec-name" id="rec-name"  class="textbox-style margin-button-12" required><br>
                    <label>Enter Amount :</label><br>
                    <input type="text" name="amount" id="amount"  class="textbox-style margin-button-12" required><br>
                    <button type="submit" name="transfer" id="transfer">Transfer Money</button>
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