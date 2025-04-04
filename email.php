<?php
include 'db.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Check if code exists
    $stmt = $conn->prepare("SELECT u_email FROM tbl_user WHERE u_verification_code = ? AND u_status = 'pending'");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update status to 'verified'
        $stmt = $conn->prepare("UPDATE tbl_user SET u_status = 'verified' WHERE u_verification_code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();

        echo "<h2>Email Verified! You can now <a href='login.php'>Login</a>.</h2>";
    } else {
        echo "<h2>Invalid or already verified code.</h2>";
    }

    $stmt->close();
} else {
    echo "<h2>No verification code provided.</h2>";
}

$conn->close();
?>
