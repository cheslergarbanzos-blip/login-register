<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: LOGIN.php");
    exit();
}

require_once "../data/database.php";
$user_id = $_SESSION["user_id"];

// Delete user from database
$sql = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    
    // Destroy session
    session_unset();
    session_destroy();
    
    // Redirect to login with message
    header("Location: LOGIN.php");
    exit();
} else {
    die("Error deleting account");
}
?>
