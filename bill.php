<?php
session_start();
include 'db.php';

// Check if the database connection is established
if ($conn) {
    $sqlBorrowed = "SELECT bill_id, bill_name , paid_by, involved, amount, date, code FROM bills"; 
    $resultBorrowed = $conn->query($sqlBorrowed); 

} else {
    echo "Database connection failed.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="bills.css"> 
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
            <h1>Created Bills</h1>
        </div>  

        <section id="Second">
        <div class="borrow">
            <h2>Bills</h2>
            <table>
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Bill Name</th>
                        <th>Paid by</th>
                        <th>Person Involved</th>
                        <th>Amount</th>
                        <th>Date Created</th>
                        <th>Generated Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    if ($resultBorrowed && $resultBorrowed->num_rows > 0) { 
                        while ($row = $resultBorrowed->fetch_assoc()) { 
                            echo "<tr> 
                                    <td>{$row['bill_id']}</td> 
                                    <td>{$row['bill_name']}</td>  
                                    <td>{$row['paid_by']}</td>
                                    <td>{$row['involved']}</td>
                                    <td>{$row['amount']}</td>    
                                    <td>{$row['date']}</td>
                                    <td>{$row['code']}</td>
                                    <td>
                                        <button class='delete-btn'><i class='fas fa-archive'></i> Delete</button>
                                        <button class='archive-btn'><i class='fas fa-trash'></i> Archive</button>
                                    </td>
                                  </tr>"; 
                        } 
                    } else { 
                        echo "<tr><td colspan='5'>No bills found.</td></tr>"; 
                    } 
                    ?>
                </tbody>
            </table>
            <a href="addBill.php" class="add-btn"><i class="fas fa-plus-circle"></i> Add</a>
            <a href="registerAssets.php" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>
            <a href="registerAssets.php" class="view-btn"><i class="fas fa-eye"></i> View</a>
        </section>
        

 </div>

 </div>
</body>
</html>