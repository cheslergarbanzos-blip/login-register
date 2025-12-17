<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Profile - Iloilo Travelogue</title>
        <link rel="stylesheet" href="../DESIGN.css?v=5">
        <link rel="icon" href="../assets/logo.png" sizes="32x32" type="image/png">
        <link rel="shortcut icon" href="../assets/logo.png">
    </head>

    <body>
    <?php
    session_start();

    // Check if user is logged in
    if (!isset($_SESSION["user_id"])) {
        header("Location: LOGIN.php");
        exit();
    }

    // Get user data from database
    require_once "../data/database.php";
    $user_id = $_SESSION["user_id"];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    } else {
        die("Error loading profile");
    }
    ?>

<!--NAVIGATION BAR-->
<div class="homepage-wrapper">
    <nav class="navbar">
        <div class="nav-links">
            <a href="HOMEPAGE.php">Home</a>
            <a href="VIEW_PROFILE.php">Profile</a>
            <a href="REVIEWS.php">Reviews</a>
            <a href="ABOUT.php">About Us</a>
        </div>
    </nav>

    <div class="profile-wrapper">
        <div class="profile-container">
            <div id="profile-content">
                <h1>My Profile</h1>
                
                <div class="profile-field">
                    <label>Full Name:</label>
                    <p id="profile-name"><?php echo htmlspecialchars($user['full_name']); ?></p>
                </div>
                
                <div class="profile-field">
                    <label>Email:</label>
                    <p id="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                
                <div class="profile-field">
                    <label>Date of birth:</label>
                    <p id="profile-birthday"><?php echo date('n-j-y', strtotime($user['birthday'])); ?></p>
                </div>
                
                <div class="profile-field">
                    <label>Joined:</label>
                    <p id="profile-joined">12-24-25</p>
                </div>
                
                <div class="profile-buttons">
                    <button id="logout-btn" onclick="window.location.href='LOGOUT.php'">Log Out</button>
                    <button id="edit-btn" onclick="toggleEditMode()">Edit Profile</button>
                    <button id="delete-btn" onclick="deleteAccount()">Delete Account</button>
                </div>
            </div>
            
            <div id="edit-form" style="display: none;">
                <h2>Edit Profile</h2>
                <form action="VIEW_PROFILE.php" method="post">
                    <div class="form-group">
                        <label>Full Name:</label>
                        <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Date of Birth:</label>
                        <input type="date" name="birthday" value="<?php echo htmlspecialchars($user['birthday']); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" id="y-button">Save Changes</button>
                    <button type="button" onclick="toggleEditMode()">Cancel</button>
                </form>
            </div>
            
            <?php
            // Handle profile update
            if (isset($_POST['update_profile'])) {
                $new_fullname = $_POST['fullname'];
                $new_email = $_POST['email'];
                $new_birthday = $_POST['birthday'];
                
                $update_sql = "UPDATE users SET full_name = ?, email = ?, birthday = ? WHERE id = ?";
                $update_stmt = mysqli_stmt_init($conn);
                
                if (mysqli_stmt_prepare($update_stmt, $update_sql)) {
                    mysqli_stmt_bind_param($update_stmt, "sssi", $new_fullname, $new_email, $new_birthday, $user_id);
                    mysqli_stmt_execute($update_stmt);
                    echo "<div class='alert alert-success'>Profile updated successfully!</div>";
                    echo "<script>setTimeout(function(){ window.location.href = 'VIEW_PROFILE.php'; }, 1500);</script>";
                }
            }
            ?>
        </div>
    </div>
</div>

<script>
function toggleEditMode() {
    var profileContent = document.getElementById('profile-content');
    var editForm = document.getElementById('edit-form');
    
    if (editForm.style.display === 'none') {
        profileContent.style.display = 'none';
        editForm.style.display = 'block';
    } else {
        profileContent.style.display = 'block';
        editForm.style.display = 'none';
    }
}

function deleteAccount() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        window.location.href = 'DELETE_ACCOUNT.php';
    }
}
</script>

<script type="text/javascript" src="../data/PROFILE.js" defer></script>
</body>
</html>

        