<?php
session_start();
include '../views/db.php';

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
                header("Location: dashboard.php");
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
    <title>Login | PayFlow System</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-hero">
            <div class="hero-content">
                <div class="logo-container">
                    <div class="logo">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>PAY<span class="logo-highlight">FLOW</span></span>
                    </div>
                    <div class="logo-subtitle">Payment Management Suite</div>
                </div>
                
                <div class="hero-main">
                    <h1>Payment Management System</h1>
                    <div class="hero-features">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-lock-shield"></i>
                            </div>
                            <div class="feature-text">
                                <h3>Bank-Grade Security</h3>
                                <p>256-bit encryption and multi-factor authentication</p>
                            </div>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-analytics"></i>
                            </div>
                            <div class="feature-text">
                                <h3>Advanced Analytics</h3>
                                <p>Real-time payment tracking and insights</p>
                            </div>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-cloud-check"></i>
                            </div>
                            <div class="feature-text">
                                <h3>Cloud Reliability</h3>
                                <p>99.99% uptime with global data centers</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="hero-footer">
                    <div class="trust-badges">
                        <div class="badge">
                            <i class="fas fa-shield-check"></i>
                            <span>GDPR Compliant</span>
                        </div>
                        <div class="badge">
                            <i class="fas fa-certificate"></i>
                            <span>ISO 27001 Certified</span>
                        </div>
                    </div>
                    <div class="copyright">© <?php echo date('Y'); ?> PayFlow Systems</div>
                </div>
            </div>
        </div>
        
        <div class="login-form-container">
            <div class="form-wrapper">
                <div class="form-header">
                    <h2>Welcome Back</h2>
                    <p class="form-subtitle">Sign in to access your payments dashboard</p>
                </div>
                
                <form method="post" action="" class="login-form">
                    <div class="form-group <?php echo !empty($usernameError) ? 'has-error' : ''; ?>">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-id-badge"></i></span>
                            <input type="text" id="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>">
                        </div>
                        <?php if (!empty($usernameError)): ?>
                            <span class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $usernameError; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group <?php echo !empty($passwordError) ? 'has-error' : ''; ?>">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="fas fa-key"></i></span>
                            <input type="password" id="password" name="password" placeholder="••••••••">
                            <button type="button" class="password-toggle" aria-label="Show password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <?php if (!empty($passwordError)): ?>
                            <span class="error-message"><i class="fas fa-exclamation-circle"></i> <?php echo $passwordError; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Keep me signed in</label>
                        </div>
                        <a href="reset_password.php" class="forgot-password">Need help signing in?</a>
                    </div>
                    
                    <button type="submit" class="login-button">
                        <span>Continue</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    
                    <div class="security-notice">
                        <i class="fas fa-lock"></i>
                        <span>Your information is protected by 256-bit SSL encryption</span>
                    </div>
                </form>
                
                <div class="alternative-auth">
                    <div class="divider">
                        <span>OR CONNECT WITH</span>
                    </div>
                    
                    <div class="auth-providers">
                        <button class="auth-provider">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" alt="Google">
                        </button>
                        <button class="auth-provider">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" alt="Microsoft">
                        </button>
                        <button class="auth-provider">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple">
                        </button>
                        <button class="auth-provider">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/05/SAML.svg" alt="SAML">
                        </button>
                    </div>
                </div>
                
                <div class="form-footer">
                    <p>New to PayFlow? <a href="registration.php">Create account</a></p>
                    <div class="legal-links">
                        <a href="#">Terms of Use</a>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Contact Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        document.querySelector('.password-toggle').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    </script>
</body>
</html>