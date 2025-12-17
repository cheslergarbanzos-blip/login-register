<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Iloilo Travelogue</title>
    <link rel="stylesheet" href="../DESIGN.css?v=4">
    <link rel="icon" href="../assets/logo.png" sizes="32x32" type="image/png">
    <link rel="shortcut icon" href="../assets/logo.png">
</head>

<body>
    <div class="login-wrapper">
        <img id="logo" src="../assets/logo.png">
        <h1>Login</h1>

        <!--LOGIN VERIFIER-->
        <?php
        session_start();
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "../data/database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query ($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($user) {
                if (password_verify($password, $user["password"])) {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["user_name"] = $user["full_name"];
                    $_SESSION["user_email"] = $user["email"];
                    header("Location: HOMEPAGE.php");
                    die();
                    
                //Error messages
                } else {
                    echo "<div class='alert alert-danger'>Password does not match</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Email does not exist</div>";
            }
        }
        ?>
        
        <!--USER INPUTS-->
        <form action="LOGIN.php" method="post">
            <div class="form-group">
                <input type="text" class= "from-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class= "from-control" name="password" placeholder="Password">
            </div>
            <div class="form-btn">
                <input type="submit" class= "btn btn-primary" value="Log in" name="login">
            </div>
        </form>
        
        <a id="forgot-link" href="FORGOT_PASS.php">Forgot Password?</a>
        <a id="signup-link" href="SIGN_UP.php">Don't have an account? Sign Up</a>
    </div>
</body>
</html>