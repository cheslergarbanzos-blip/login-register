<?php require_once 'reviews_handler.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reviews — Iloilo Travelogue</title>

  <link rel="stylesheet" href="../DESIGN.css">
  <link rel="icon" href="../assets/logo.png" sizes="32x32" type="image/png">
  <link rel="shortcut icon" href="../assets/logo.png">
</head>

<body>
 
<div class = homepage-wrapper>
   <nav class="navbar">
    <div class="nav-links">
      <a href="HOMEPAGE.php">Home</a>
      <a href="VIEW_PROFILE.php">Profile</a>
      <a href="REVIEWS.php">Reviews</a>
      <a href="ABOUT.php">About Us</a>
    </div>
  </nav>
 <div class="reviews-wrapper">
    <h1>Iloilo Travelogue — Community Reviews</h1>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (isset($errors) && count($errors) > 0): ?>
        <?php foreach ($errors as $error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div id="post-area" class="review-form">
      <h2>Post a Review</h2>
      
      <form method="POST" action="REVIEWS.php<?php echo $destination_filter ? '?destination=' . urlencode($destination_filter) : ''; ?>">
        <div class="destination-row">
          <label for="destination"><strong>Destination:</strong></label>
          <input id="destination-select" name="destination" type="text" placeholder="Type a place (e.g., Miagao Church)" value="<?php echo htmlspecialchars($destination_filter); ?>" required>
        </div>

        <div class="destination-row">
          <label for="r-title"><strong>Title:</strong></label>
          <input id="review-title" type="text" name="title" placeholder="Short title" required>
        </div>

        <div id="rating-wrapper">
          <label for="rating">Rating: </label>
          <select id="review-rating" name="rating" required>
            <option value="5">★★★★★ (5)</option>
            <option value="4">★★★★ (4)</option>
            <option value="3">★★★ (3)</option>
            <option value="2">★★ (2)</option>
            <option value="1">★ (1)</option>
          </select>
        </div>

        <div>
          <label for="body"></label>
          <textarea id="review-body" name="body" rows="5" placeholder="Write your review..." required></textarea>
        </div>

        <button id="post-review" type="submit" name="post_review">Post Review</button>
      </form>
    </div>
    
      <hr>
    
    <div class="reviews-controls">
      <label for="sort-select">Sort:</label>
      <form method="GET" action="REVIEWS.php" style="display: inline;">
        <?php if ($destination_filter): ?>
          <input type="hidden" name="destination" value="<?php echo htmlspecialchars($destination_filter); ?>">
        <?php endif; ?>
        <select id="sort-select" name="sort" onchange="this.form.submit()">
          <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest</option>
          <option value="highest" <?php echo $sort === 'highest' ? 'selected' : ''; ?>>Highest rating</option>
          <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Oldest</option>
        </select>
      </form>
    </div>

    <div id="reviews-list" class="reviews-list">
      <?php if ($destination_filter && count($reviews) === 0): ?>
        <p>No reviews yet for this destination. Be the first to post one!</p>
      <?php elseif (!$destination_filter): ?>
        <p>Enter a destination above to see reviews.</p>
      <?php else: ?>
        <?php foreach ($reviews as $review): ?>
          <div class="review-card">
            <div>
              <strong><?php echo htmlspecialchars($review['title']); ?></strong> — 
              <?php echo str_repeat('★', $review['rating']) . str_repeat('☆', 5 - $review['rating']); ?>
            </div>
            <div class="review-meta">
              <?php echo htmlspecialchars($review['full_name']); ?> · 
              <?php echo date('M j, Y g:i A', strtotime($review['created_at'])); ?>
            </div>
            <p><?php echo nl2br(htmlspecialchars($review['body'])); ?></p>
            <div class="review-actions">
              <form method="POST" action="REVIEWS.php?destination=<?php echo urlencode($destination_filter); ?>&sort=<?php echo urlencode($sort); ?>" style="display: inline;">
                <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                <button type="submit" name="like_review">Like (<?php echo $review['likes']; ?>)</button>
              </form>
              
              <?php if ($review['user_id'] == $user_id): ?>
                <form method="POST" action="REVIEWS.php?destination=<?php echo urlencode($destination_filter); ?>&sort=<?php echo urlencode($sort); ?>" style="display: inline; margin-left: 8px;">
                  <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                  <button type="submit" name="delete_review" onclick="return confirm('Delete this review?')">Delete</button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  </div>
</body>
</html>