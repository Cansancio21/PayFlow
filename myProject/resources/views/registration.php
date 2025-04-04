<?php
// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include '../views/db.php'; 
require 'C:/xampp/htdocs/Split-Wise/myProject/PHPMailer-master/src/Exception.php'; 
require 'C:/xampp/htdocs/Split-Wise/myProject/PHPMailer-master/src/PHPMailer.php'; 
require 'C:/xampp/htdocs/Split-Wise/myProject/PHPMailer-master/src/SMTP.php'; 

// Initialize variablesd
$lastNameError = $firstNameError = $nicknameError = $emailError = $usernameError = $passwordError = $confirmPasswordError = "";
$lastName = $firstName = $nickname = $email = $username = $password = $confirmPassword = $userType = "";
$isValid = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $lastName = trim($_POST["lastName"]);
    if (empty($lastName)) {
        $lastNameError = "Last Name is required.";
        $isValid = false;
    }

    $firstName = trim($_POST["firstName"]);
    if (empty($firstName)) {
        $firstNameError = "First Name is required.";
        $isValid = false;
    }

    $nickname = trim($_POST["nickname"]);
    if (empty($nickname)) {
        $nicknameError = "Nickname is required.";
        $isValid = false;
    }

    $email = trim($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email address.";
        $isValid = false;
    }

    $username = trim($_POST["username"]);
    if (empty($username)) {
        $usernameError = "Username is required.";
        $isValid = false;
    }

    $password = $_POST["password"];
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,16}$/";
    if (!preg_match($passwordPattern, $password)) {
        $passwordError = "Password must be 8-16 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.";
        $isValid = false;
    }

    $confirmPassword = $_POST["confirmPassword"];
    if ($confirmPassword !== $password) {
        $confirmPasswordError = "Passwords do not match.";
        $isValid = false;
    }

    $userType = $_POST['subscriptionType']; // Using subscriptionType as user type

    // If valid, insert data into the database
    if ($isValid) {
      
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO tbl_user (u_lname, u_fname, u_nickname, u_email, u_username, u_password, u_confirm, u_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password for security

            $stmt->bind_param("ssssssss", $lastName, $firstName, $nickname, $email, $username, $hashedPassword, $confirmPassword, $userType);

            // Execute the statement
            if ($stmt->execute()) {
                // Send the confirmation email using PHPMailer
                $mail = new PHPMailer(true); // Create a new PHPMailer instance

                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Set the 
                    $mail->SMTPAuth = true;
                    $mail->Username = 'jonwilyammayormita@gmail.com'; // Your SMTP username
                    $mail->Password = 'mqkcqkytlwurwlks'; // Your SMTP password
                    $mail->Port = 587;  // Change from 465 to 587 for STARTTLS
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // This should match the port

                    // Sender and recipient details
                    $mail->setFrom('jonwilyammayormita@gmail.com', 'Your Website');
                    $mail->addAddress($email, "$firstName $lastName");

                    // Email subject and body content
                    $mail->isHTML(true);
                    $mail->Subject = 'Welcome to Our Platform!';
                    $mail->Body = "
                    <html>
                    <head>
                        <title>Verify Your Email</title>
                    </head>
                    <body>
                        <p>Dear $firstName $lastName,</p>
                        <p>Thank you for registering. Please verify your email by clicking the link below:</p>
                      <p><a href='http://localhost/split-wise/login.php?code=$verificationCode'>Login Page</a></p>

                        <p>Once verified, you can log in.</p>
                        <p>Best regards,<br>Your Platform Team</p>
                    </body>
                    </html>
                ";
                    // Send the email
                    $mail->send();
                    echo 'Confirmation email has been sent. Please check your inbox.';
                } catch (Exception $e) {
                    echo "Error sending confirmation email: {$mail->ErrorInfo}";
                }

                // Redirect to login.php after successful registration
                header("Location: login.php");
                exit(); // Stop further script execution
            } else {
                // Output the error message
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }


// Close the database connection
$conn->close();
?>



    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Registration Form</title>
        <link rel="stylesheet" href="../css/register.css"> <!-- Link to the CSS file -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    </head>
    <body>
    <form method="post" action="">
        <h2>Registration Form</h2>
        <div class="form-row">
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <div class="input-container">
                    <i class="fas fa-user"></i>
                    <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" placeholder="Enter LastName">
                </div>
                <span class="error"><?php echo $lastNameError; ?></span>
            </div>

            <div class="form-group">
                <label for="firstName">First Name:</label>
                <div class="input-container">
                    <i class="fas fa-user"></i>
                    <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" placeholder="Enter FirstName">
                </div>
                <span class="error"><?php echo $firstNameError; ?></span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="nickname">Nickname:</label>
                <div class="input-container">
                    <i class="fas fa-user-tag"></i>
                    <input type="text" id="nickname" name="nickname" value="<?php echo htmlspecialchars($nickname); ?>" placeholder="Enter Nickname">
                </div>
                <span class="error"><?php echo $nicknameError; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <div class="input-container">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter Email Address">
                </div>
                <span class="error"><?php echo $emailError; ?></span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="username">Username:</label>
                <div class="input-container">
                    <i class="fas fa-user-circle"></i>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Username">
                </div>
                <span class="error"><?php echo $usernameError; ?></span>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password">
                </div>
                <span class="error"><?php echo $passwordError; ?></span>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <div class="input-container">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-enter your password">
                </div>
                <span class="error"><?php echo $confirmPasswordError; ?></span>
            </div>
        </div>

        <div class="form-group">
    <label for="subscriptionType">Status Type:</label>
    <div class="input-container">
        <select id="subscriptionType" name="subscriptionType" required>
            <option value="" disabled selected>Select Type</option>
            <option value="Standard">Standard</option>
            <option value="Premium">Premium</option>
        </select>
        <i class="fas fa-caret-down"></i> <!-- Dropdown icon -->
    </div>
</div>



        <div class="form-group">
            <button type="submit">Register</button>
        </div>
    </form>
    </body>
    </html>

