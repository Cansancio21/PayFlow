<?php

session_start(); // Start session for login management
include 'db.php';

// Initialize variables as empty
$billname = $paidby = $with = "";
$amount = $date = $code = ""; 

$billnameErr = $paidbyErr = $amountErr = "";
$hasError = false;
$successMessage = "";

// Fetch users for the dropdown
$userOptions = "";
$sql = "SELECT u_id, u_username FROM tbl_user";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Use the username as the value in the dropdown
        $userOptions .= "<option value='" . htmlspecialchars($row['u_username']) . "'>" . htmlspecialchars($row['u_username']) . "</option>";
    }
}

// User Registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $billname = trim($_POST['bill_name']);
    $paidby = trim($_POST['paid_by']); // This will now be the username
    $with = trim($_POST['with']); // This will also be the username
    $amount = trim($_POST['amount']);
    $date = trim($_POST['date_created']);
    $code = trim($_POST['code']);

    // Validate account name
    if (!preg_match("/^[a-zA-Z\s-]+$/", $billname)) {
        $billnameErr = "Bill Name should not contain numbers.";
        $hasError = true;
    }

    // Insert into database if no errors
    if (!$hasError) {
        $sql = "INSERT INTO bills (bill_name, paid_by, involved, amount, date, code)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters correctly
        $stmt->bind_param("sssiss", $billname, $paidby, $with, $amount, $date, $code);

        if ($stmt->execute()) {
            // Show alert and then redirect using JavaScript
            echo "<script type='text/javascript'>
                    alert('Bill has been Added successfully.');
                    window.location.href = 'bill.php'; // Redirect to bill.php
                  </script>";
        } else {
            die("Execution failed: " . $stmt->error);
        }
        
        $stmt->close();
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Bill</title>
    <link rel="stylesheet" href="addBill.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="generateCode.js" defer></script>
</head>
<body>
     <div class="wrapper">
       <div class="container">
        <h1>Bill Details</h1>
        <form method="POST" action="" class="form">
    <div class="form-row">
        <label for="event_name">Bill Name:</label>
        <input type="text" id="bill_name" name="bill_name" placeholder="Bill Name" required>
    </div>
    <div class="form-row">
        <label for="paid_by">Paid by:</label>
        <input type="text" id="paid_by" name="paid_by" placeholder="Paid by" required>
    </div>
    <div class="form-row">
    <label for="with">Person Involved:</label>
    <select name="with" required>
        <option value="" disabled selected>Persons Involved</option>
        <?php echo $userOptions; ?>
    </select>
</div>
    <div class="form-row">
        <label for="amount">Enter Amount:</label>
        <input type="text" id="amount" name="amount" placeholder="Amount" required>
    </div>
    <div class="form-row">
        <label for="date_created">Date Created:</label>
        <input type="date" id="date_created" name="date_created" placeholder="Date" required>
    </div>
    <div class="form-row">
        <label for="code">Code:</label>
        <div class="input-wrapper">
        <input type="text" id="code" name="code" placeholder="Generated Code" readonly>
        <button type="button" id="generate-code" onclick="generateCode()">Generate Code</button>
        </div>
    </div>
    <button type="submit">Add</button>
    </form>
     </div>
</body>
</html>
