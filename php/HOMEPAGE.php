<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title> Home - Iloilo Travelogue</title>

        <!--LINKING STYLE SHEET-->
        <link rel="stylesheet" href="../DESIGN.css">
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
        
        // Get popular reviews
        require_once "../data/database.php";
        $popular_reviews = [];
        $sql = "SELECT r.*, u.full_name FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                ORDER BY r.likes DESC, r.created_at DESC 
                LIMIT 3";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $popular_reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        
        // Handle search
        $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
        $search_results = [];
        
        if ($search_query) {
            $search_term = '%' . $search_query . '%';
            $sql = "SELECT * FROM destinations WHERE 
                    name LIKE ? OR 
                    full_name LIKE ? OR 
                    description LIKE ? OR 
                    location LIKE ? OR 
                    tags LIKE ?";
            $stmt = mysqli_stmt_init($conn);
            
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "sssss", $search_term, $search_term, $search_term, $search_term, $search_term);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $search_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }
        }
        ?>
        
        <div class = "homepage-wrapper">
            
    <!--NAVIGATION-->
               <nav class ="navbar">
            <div class="nav-links">
                <a href="HOMEPAGE.php">Home</a>
                <a href="VIEW_PROFILE.php">Profile</a>
                <a href="REVIEWS.php">Reviews</a>
                <a href="ABOUT.php">About Us</a>
        </nav>

    <!--HEADER-->
    <div id = "homepage-content">
        <div id = "homepage-text">
        <main>
        <section id="home">
            <h2>Discover Iloilo's Hidden Gems</h2>
            <p>Share your adventures and read authentic reviews from fellow travelers</p>
            <form id="search-form" method="GET" action="HOMEPAGE.php">
                <input id="search-field" type="text" placeholder="Search for destinations..." name="search" value="<?php echo htmlspecialchars($search_query); ?>">
                <button id="search-btn" type="submit">Search</button>
            </form>
        </section>
    </div>

    <div id="search-results" style="display: <?php echo $search_query ? 'block' : 'none'; ?>;">
        <h2>Search Results</h2>
        <div id="search-results-list">
            <?php if ($search_query && count($search_results) === 0): ?>
                <p>No destinations found. Try searching for "Molo Church", "Miagao Church", or other places.</p>
            <?php elseif ($search_query): ?>
                <?php foreach ($search_results as $dest): ?>
                    <div class="destination-card">
                        <?php if ($dest['image']): ?>
                            <img src="<?php echo htmlspecialchars($dest['image']); ?>" alt="<?php echo htmlspecialchars($dest['name']); ?>" class="dest-image" onerror="this.style.display='none'">
                        <?php endif; ?>
                        <div class="dest-info">
                            <h3><?php echo htmlspecialchars($dest['name']); ?></h3>
                            <p class="dest-location">📍 <?php echo htmlspecialchars($dest['location']); ?></p>
                            <p><?php echo htmlspecialchars($dest['description']); ?></p>
                            <a href="REVIEWS.php?destination=<?php echo urlencode($dest['name']); ?>">View Reviews →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a href="HOMEPAGE.php"><button id="clear-search">Show Popular Places</button></a>
    </div>

    <div id="popular-section" style="display: <?php echo $search_query ? 'none' : 'block'; ?>;">
         <section id="destinations">
            <h2>Popular Places</h2>
            <p>Top rated destinations from our community</p>
            <div id="popular-reviews">
                <?php if (count($popular_reviews) === 0): ?>
                    <p>No reviews yet. Be the first to post one!</p>
                <?php else: ?>
                    <?php foreach ($popular_reviews as $review): ?>
                        <div class="popular-review-card">
                            <h3><?php echo htmlspecialchars($review['title']); ?></h3>
                            <div class="review-rating">
                                <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?>
                            </div>
                            <p><strong>Destination:</strong> <?php echo htmlspecialchars($review['destination']); ?></p>
                            <p>
                                <?php 
                                $preview = strlen($review['body']) > 150 ? substr($review['body'], 0, 150) . '...' : $review['body'];
                                echo htmlspecialchars($preview);
                                ?>
                            </p>
                            <p class="review-meta">
                                By <?php echo htmlspecialchars($review['full_name']); ?> · <?php echo $review['likes']; ?> likes
                            </p>
                            <a href="REVIEWS.php?destination=<?php echo urlencode($review['destination']); ?>">
                                View all reviews for this destination →
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
    </div>
    </div>
    </div>
    </body>
</html>