<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from session
$userId = $_SESSION['userId'];
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$nickname = $_SESSION['nickname'];
$email = $_SESSION['email'];
$userType = $_SESSION['userType'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="maindash.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="wrapper">

    <div class="sidebar">
        <h2>Fair <span>Share</span></h2>
        <ul>
          <li><a href="maindash.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
          <li><a href="bill.php"><i class="fas fa-file-invoice"></i> Bills</a></li>
          <li><a href="archive.php"><i class="fas fa-archive"></i> Archive Bills</a></li>
          <li><a href="createTickets.php"><i class="fas fa-money-bill-wave"></i> Expenses</a></li>    
        </ul>
    </div>
    
    <div class="container">
       <div class="upper">
            <h1>Dashboard</h1>
        </div>  

        <section id="First">
        <div class="box">
    <h2>Profile Information</h2>
    <div class="row">
        <p><strong>User ID:</strong> <?php echo htmlspecialchars($userId); ?></p>
        <p><strong>Nickname:</strong> <?php echo htmlspecialchars($nickname); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    </div>
    <div class="row">
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($firstName); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($lastName); ?></p>
        <p><strong>Account Type:</strong> <?php echo htmlspecialchars($userType); ?></p>
    </div>
</div>

<div class="box-upgrade">
    <h3>Upgrade To Pro</h3>
    <p>Get access to additional<br> features and contact </p>
    <button class="upgrade-button">Upgrade</button> <!-- Upgrade button -->
</div>

        </section>

        <section id="Second">
        <div class="borrow">
            <h2>Recent Bills</h2>
            <table>
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Bill Name</th>
                        <th>Paid by</th>
                        <th>Person Involved</th>
                        <th>Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <a href="addBill.php" class="add-btn"><i class="fas fa-plus-circle"></i> Add</a>
            <a href="registerAssets.php" class="add-btn"><i class="fas fa-edit"></i> Edit</a>
            <a href="registerAssets.php" class="add-btn"><i class="fas fa-eye"></i> View</a>
            <a href="archiveAssets.php" class="add-btn"><i class="fas fa-archive"></i> Archive</a>
            <a href="deleteAssets.php" class="add-btn"><i class="fas fa-trash"></i> Delete</a>
        </section>
        

 </div>

 </div>
</body>
</html>