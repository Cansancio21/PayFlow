<?php
session_start();
include 'db.php';

$usernameError = $passwordError = "";
$username = $password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Check if username and password are provided
    if (empty($username)) {
        $usernameError = "Username is required.";
    }
    if (empty($password)) {
        $passwordError = "Password is required.";
    }

    // If no errors, proceed to check credentials
    if (empty($usernameError) && empty($passwordError)) {
        // Update the SQL query to select the ID, nickname, and email
        $stmt = $conn->prepare("SELECT u_id, u_password, u_fname, u_lname, u_nickname, u_email, u_type FROM tbl_user WHERE u_username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userId, $hashedPassword, $firstName, $lastName, $nickname, $email, $userType);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashedPassword)) {
                // Store user information in session
                $_SESSION['userId'] = $userId; // Store user ID
                $_SESSION['username'] = $username;
                $_SESSION['firstName'] = $firstName;
                $_SESSION['lastName'] = $lastName;
                $_SESSION['nickname'] = $nickname; // Store nickname
                $_SESSION['email'] = $email; // Store email
                $_SESSION['userType'] = $userType;

                // Redirect to maindash.php after successful login
                header("Location: maindash.php");
                exit();
            } else {
                $passwordError = "Invalid password.";
            }
        } else {
            $usernameError = "No user found with that username.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css"> <!-- Link to the CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
</head>
<body>
    <div class="container">
    <div class="container">
    <div class="left-side">
    <h1>Welcome Back!</h1>
    <p>We are glad to see you again. Here you can manage your expenses and track your financial goals.</p>
    <p>Feel free to explore the features we offer, and don't hesitate to reach out if you have any questions!</p>
    <p>Let's get started on your financial journey!</p>
</div>

        <form method="post" action="">
            <h2>Login Form</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <div class="input-container">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Enter Username" >
                    </div>
                    <span class="error"><?php echo $usernameError; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="input-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter Password" >
                    </div>
                    <span class="error"><?php echo $passwordError; ?></span>
                </div>
            </div>

            <div class="form-group">
                <button type="submit">Login</button>
            </div>
            <div class="form-row">
    <div class="form-group left">
        <a href="reset_password.php">Forgot your password?</a>
    </div>
    <div class="form-group right">
        <a href="registration.php">Click to Register?</a>
    </div>
</div>
        </form>
    </div>
</body>
</html>