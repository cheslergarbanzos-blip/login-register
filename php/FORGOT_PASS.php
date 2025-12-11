<?php
session_start();
require_once "../data/database.php";

$errors = [];
$success_message = "";
$step = 1; // Step 1: Verify email and birthday, Step 2: Reset password
$user_id = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['verify'])) {
        // Step 1: Verify email and birthday
        $email = trim($_POST['email']);
        $birthday = $_POST['birthday'];
        
        if (empty($email)) {
            $errors[] = "Email is required";
        }
        if (empty($birthday)) {
            $errors[] = "Birthday is required";
        }
        
        if (empty($errors)) {
            $sql = "SELECT id, full_name FROM users WHERE email = ? AND birthday = ?";
            $stmt = mysqli_stmt_init($conn);
            
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $email, $birthday);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if ($user = mysqli_fetch_assoc($result)) {
                    $step = 2;
                    $user_id = $user['id'];
                    $_SESSION['reset_user_id'] = $user_id;
                    $success_message = "Verified! Please enter your new password.";
                } else {
                    $errors[] = "Email and birthday do not match our records";
                }
            }
        }
    } elseif (isset($_POST['reset_password'])) {
        // Step 2: Reset password
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_SESSION['reset_user_id'] ?? null;
        
        if (empty($new_password)) {
            $errors[] = "New password is required";
        } elseif (strlen($new_password) < 8) {
            $errors[] = "Password must be at least 8 characters long";
        }
        
        if (empty($confirm_password)) {
            $errors[] = "Please confirm your password";
        }
        
        if ($new_password !== $confirm_password) {
            $errors[] = "Passwords do not match";
        }
        
        if (empty($errors) && $user_id) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = mysqli_stmt_init($conn);
            
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $hashed_password, $user_id);
                
                if (mysqli_stmt_execute($stmt)) {
                    unset($_SESSION['reset_user_id']);
                    $success_message = "Password reset successfully! Redirecting to login...";
                    echo "<script>
                        setTimeout(function() {
                            window.location.href = 'LOGIN.php';
                        }, 2000);
                    </script>";
                } else {
                    $errors[] = "Failed to reset password. Please try again.";
                }
            }
        } else {
            $step = 2; // Stay on step 2 if there are errors
        }
    }
}

if (isset($_SESSION['reset_user_id']) && $step == 1) {
    $step = 2; // Resume at step 2 if session exists
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Iloilo Travelogue</title>
    <link rel="stylesheet" href="../DESIGN.css">
    <link rel="icon" href="../assets/logo.png" sizes="32x32" type="image/png">
    <link rel="shortcut icon" href="../assets/logo.png">
</head>

<body>
    <div class="login-wrapper">
        <img id="logo" src="../assets/logo.png">
        <h1>Forgot Password</h1>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if ($step == 1): ?>
            <p>Please enter your email and birthday to verify your identity.</p>
            
            <form method="POST" action="FORGOT_PASS.php">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="birthday">Birthday:</label>
                    <input type="date" id="birthday" name="birthday" required>
                </div>
                
                <button id="y-button" type="submit" name="verify">Verify</button>
            </form>
        <?php else: ?>
            <form method="POST" action="FORGOT_PASS.php">
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button id="y-button" type="submit" name="reset_password">Reset Password</button>
            </form>
        <?php endif; ?>
        
        <br>
        <a href="LOGIN.php" id="login-link">Back to Login</a>
    </div>
</body>
</html>