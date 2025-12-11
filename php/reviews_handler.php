<?php
session_start();
require_once "../data/database.php";

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: LOGIN.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// Handle POST review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_review'])) {
    $destination = trim($_POST['destination']);
    $title = trim($_POST['title']);
    $rating = intval($_POST['rating']);
    $body = trim($_POST['body']);
    
    $errors = [];
    
    if (empty($destination)) array_push($errors, "Enter a destination.");
    if (empty($title)) array_push($errors, "Title is required.");
    if (empty($body)) array_push($errors, "Write something.");
    if ($rating < 1 || $rating > 5) array_push($errors, "Pick a rating 1-5.");
    
    if (count($errors) === 0) {
        $sql = "INSERT INTO reviews (user_id, destination, title, rating, body) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "issis", $user_id, $destination, $title, $rating, $body);
            mysqli_stmt_execute($stmt);
            $success_message = "Review posted successfully!";
        }
    }
}

// Handle DELETE review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review'])) {
    $review_id = intval($_POST['review_id']);
    
    // Check if user owns this review
    $sql = "DELETE FROM reviews WHERE id = ? AND user_id = ?";
    $stmt = mysqli_stmt_init($conn);
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $review_id, $user_id);
        mysqli_stmt_execute($stmt);
    }
}

// Handle LIKE review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_review'])) {
    $review_id = intval($_POST['review_id']);
    
    // Check if already liked
    $check_sql = "SELECT id FROM review_likes WHERE review_id = ? AND user_id = ?";
    $check_stmt = mysqli_stmt_init($conn);
    
    if (mysqli_stmt_prepare($check_stmt, $check_sql)) {
        mysqli_stmt_bind_param($check_stmt, "ii", $review_id, $user_id);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($result) === 0) {
            // Not liked yet, add like
            $like_sql = "INSERT INTO review_likes (review_id, user_id) VALUES (?, ?)";
            $like_stmt = mysqli_stmt_init($conn);
            
            if (mysqli_stmt_prepare($like_stmt, $like_sql)) {
                mysqli_stmt_bind_param($like_stmt, "ii", $review_id, $user_id);
                mysqli_stmt_execute($like_stmt);
                
                // Update likes count
                $update_sql = "UPDATE reviews SET likes = likes + 1 WHERE id = ?";
                $update_stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($update_stmt, $update_sql);
                mysqli_stmt_bind_param($update_stmt, "i", $review_id);
                mysqli_stmt_execute($update_stmt);
            }
        }
    }
}

// Get destination filter
$destination_filter = isset($_GET['destination']) ? trim($_GET['destination']) : '';

// Get sort option
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build query
if ($destination_filter) {
    $sql = "SELECT r.*, u.full_name, u.email FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE LOWER(r.destination) = LOWER(?)";
    
    switch ($sort) {
        case 'oldest':
            $sql .= " ORDER BY r.created_at ASC";
            break;
        case 'highest':
            $sql .= " ORDER BY r.rating DESC, r.created_at DESC";
            break;
        default: // newest
            $sql .= " ORDER BY r.created_at DESC";
    }
    
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $destination_filter);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $reviews = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $reviews = [];
    }
} else {
    $reviews = [];
}

// Function to get popular reviews (for homepage)
function getPopularReviews($conn, $limit = 3) {
    $sql = "SELECT r.*, u.full_name, u.email FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            ORDER BY r.likes DESC, r.created_at DESC 
            LIMIT ?";
    
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}
?>
