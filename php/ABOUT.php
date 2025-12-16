<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>About - Iloilo Travelogue</title>
        <link rel="stylesheet" href="../DESIGN.css?v=3">
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
        ?>

        <div class="homepage-wrapper">
            <nav class="navbar">
                <div class="nav-links">
                    <a href="HOMEPAGE.php">Home</a>
                    <a href="VIEW_PROFILE.php">Profile</a>
                    <a href="REVIEWS.php">Reviews</a>
                    <a href="ABOUT.php">About Us</a>
                </div>
            </nav>

            <div class="about-wrapper">
                <h1>About Us</h1>

                <!-- Background Section -->
                <div class="background-section">
                    <div class="abt-info">
                        <h3>Background of the Website</h3>
                        <p>This website was created to showcase Iloilo's beauty, both the well-known tourist spots and the hidden gems only locals usually know about. We wanted a single place where travelers, students, and even Ilonggos themselves can easily discover what makes the province special.</p>
                    </div>
                    <img src="../assets/skyline.jpg" alt="Purpose" class="about-image">
                </div>

                <!-- Purpose Section -->
                <div class="purpose-section">
                    <img src="../assets/Festive.jpg" alt="Purpose" class="about-image">
                    <div class="abt-purpose">
                        <h3>Purpose</h3>
                        <p>Our purpose is simple: to help people find Iloilo's destinations with ease. Whether you're looking for popular attractions, peaceful corners, food spots, or lesser known locations, this website serves as your guide in exploring and appreciating the heart of Iloilo.</p>
                    </div>
                </div>

                <!-- Members Section -->
                <div class="member-section">
                    <h3>Members</h3>
                    <div class="members-grid">
                        <div class="member-item">
                            <img src="../assets/members/french.jpg" alt="Member 1" class="member-circle">
                            <div class="member-info">
                                <p>French Mar V. Ballescas</p>
                                <p>Back End Developer, Documentation</p>
                            </div>
                        </div>

                        <div class="member-item">
                            <img src="../assets/members/danielle.png" alt="Member 1" class="member-circle">
                            <div class="member-info">
                                <p>Danielle P. Ferrao</p>
                                <p>Front End Developer, Documentation</p>
                            </div>
                        </div>

                        <div class="member-item">
                            <img src="../assets/members/chesler.jpg" alt="Member 1" class="member-circle">
                            <div class="member-info">
                                <p>Chesler F. Garbanzos</p>
                                <p>Back-End Developer, Documentation</p>
                            </div>
                        </div>

                        <div class="member-item">
                            <img src="../assets/members/gianna.jpeg" alt="Member 1" class="member-circle">
                            <div class="member-info">
                                <p>Gianna P. Parreñas</p>
                                <p>Back End Developer, Documentation</p>
                            </div>
                        </div>

                        <div class="member-item">
                         <img src="../assets/members/maxs.jpg" alt="Member 1" class="member-circle">
                            <div class="member-info">
                                <p>Maxs Ephraim O. Sevillano</p>
                                <p>Front-End Developer, Documentation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>