<?php
session_start();
// Get blog post ID from URL
$post_id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch specific blog post
require_once 'php/connection.php';

$post = null;
$relatedPosts = [];
$comments = [];
$comments_count = 0;
$commentError = null;
$categories = [];

// Handle new comment submission (only for logged-in users)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && $post_id) {
  $commentText = trim($_POST['comment']);
  $userUuid = $_SESSION['user_uuid'] ?? null;
  $guestFullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : null;

  // Validate
  if ($commentText === '') {
    $commentError = 'Comment cannot be empty.';
  } elseif (mb_strlen($commentText) > 1000) {
    $commentError = 'Comment is too long. Maximum 1000 characters.';
  } elseif (!$userUuid) {
    // Guest comment requires fullname
    if ($guestFullname === null || $guestFullname === '') {
      $commentError = 'Please enter your name to post a comment.';
    } elseif (mb_strlen($guestFullname) > 50) {
      $commentError = 'Name is too long. Maximum 50 characters.';
    }
  }

  if ($commentError === null) {
    try {
      $commentUuid = bin2hex(random_bytes(16));
    } catch (Exception $e) {
      $commentUuid = str_replace('.', '', uniqid('', true));
    }

    // If guest, store a placeholder UUID if DB requires non-null
    $userUuidToStore = $userUuid ?? '00000000-0000-0000-0000-000000000000';
    $fullnameToStore = $userUuid ? null : $guestFullname; // logged-in users don't need fullname here

    $insertSql = "INSERT INTO blogcomments (uuid, post_uuid, user_uuid, fullname, comment, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    if ($stmtIns = $conn->prepare($insertSql)) {
      $stmtIns->bind_param('sssss', $commentUuid, $post_id, $userUuidToStore, $fullnameToStore, $commentText);
      if ($stmtIns->execute()) {
        // Redirect to avoid form resubmission
        header('Location: blog-details.php?id=' . urlencode($post_id) . '#comments');
        exit();
      } else {
        $commentError = 'Failed to save your comment. Please try again.';
      }
      $stmtIns->close();
    } else {
      $commentError = 'Database error. Please try again later.';
    }
  }
}

if ($post_id) {
  // Fetch the specific post
  $stmt = $conn->prepare("SELECT uuid, user_uuid, title, category, image, image_1, image_2, image_3, body, created_at, updated_at FROM blogposts WHERE uuid = ?");
  $stmt->bind_param("s", $post_id);
  $stmt->execute();
  $result = $stmt->get_result();
    
  if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
        
    // Fetch related posts from the same category
    $category = $post['category'];
    $stmt2 = $conn->prepare("SELECT uuid, title, image, created_at FROM blogposts WHERE category = ? AND uuid != ? ORDER BY created_at DESC LIMIT 5");
    $stmt2->bind_param("ss", $category, $post_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
        
    while($row = $result2->fetch_assoc()) {
      $relatedPosts[] = $row;
    }
    $stmt2->close();

    // Fetch comments for this post (with user display info)
    $stmt3 = $conn->prepare(
      "SELECT c.uuid, c.user_uuid, c.fullname, c.comment, c.created_at, u.username, u.profile_picture
       FROM blogcomments c
       LEFT JOIN users u ON u.uuid = c.user_uuid
       WHERE c.post_uuid = ?
       ORDER BY c.created_at DESC"
    );
    $stmt3->bind_param('s', $post_id);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    while ($row = $result3->fetch_assoc()) {
      $comments[] = $row;
    }
    $comments_count = count($comments);
    $stmt3->close();
  }
  $stmt->close();
}

// Fetch all unique categories with post counts
$cat_sql = "SELECT category, COUNT(*) as count FROM blogposts GROUP BY category ORDER BY category ASC";
$cat_result = $conn->query($cat_sql);
if ($cat_result && $cat_result->num_rows > 0) {
  while($cat_row = $cat_result->fetch_assoc()) {
    $categories[] = $cat_row;
  }
}

// Close connection after all queries
$conn->close();

// If no post found, redirect to blog page
if (!$post) {
  header("Location: blog.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo htmlspecialchars($post['title']); ?> - CooFICongo Blog</title>
  <meta name="description" content="<?php echo htmlspecialchars(substr($post['body'], 0, 160)); ?>">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/logo2_icon.png" rel="icon">
  <link href="assets/img/logo2_icon.png" rel="logo2_icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Marcellus:wght@400&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <style>
    /* Blog Details Enhancements */
    .article {
      animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .post-img {
      position: relative;
      overflow: hidden;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      margin-bottom: 2rem;
    }

    .post-img img {
      transition: transform 0.5s ease;
    }

    .post-img:hover img {
      transform: scale(1.05);
    }

    .title {
      font-size: 2.5rem;
      font-weight: 800;
      color: var(--heading-color);
      margin: 2rem 0;
      line-height: 1.3;
      animation: slideInLeft 0.6s ease-out;
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .meta-top {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      padding: 1rem 1.5rem;
      border-radius: 12px;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .meta-top ul {
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .meta-top li {
      font-size: 0.95rem;
      color: #666;
    }

    .meta-top li i {
      color: var(--accent-color);
      margin-right: 5px;
      transition: transform 0.3s ease;
    }

    .meta-top li:hover i {
      transform: scale(1.2) rotate(10deg);
    }

    .content {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #444;
      animation: fadeIn 1s ease-out 0.3s backwards;
    }

    .content p {
      margin-bottom: 1.5rem;
    }

    .content h3 {
      color: var(--accent-color);
      font-weight: 700;
      margin: 2rem 0 1rem;
      font-size: 1.8rem;
    }

    .content blockquote {
      background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
      border-left: 4px solid var(--accent-color);
      padding: 1.5rem 2rem;
      margin: 2rem 0;
      border-radius: 8px;
      font-style: italic;
      color: #2d5016;
      box-shadow: 0 4px 12px rgba(46, 163, 89, 0.1);
    }

    .content img {
      border-radius: 12px;
      margin: 2rem 0;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease;
    }

    .content img:hover {
      transform: scale(1.02);
    }

    .meta-bottom {
      background: #f8f9fa;
      padding: 1.5rem;
      border-radius: 12px;
      margin-top: 2rem;
    }

    .meta-bottom .cats li,
    .meta-bottom .tags li {
      display: inline-block;
      margin-right: 0.5rem;
      margin-bottom: 0.5rem;
    }

    .meta-bottom .cats li a,
    .meta-bottom .tags li a {
      background: var(--accent-color);
      color: white;
      padding: 0.4rem 1rem;
      border-radius: 20px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      display: inline-block;
    }

    .meta-bottom .cats li a:hover,
    .meta-bottom .tags li a:hover {
      background: #116530;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(46, 163, 89, 0.3);
    }

    /* Related Posts */
    .recent-posts-widget-2 .post-item {
      padding: 1rem;
      margin-bottom: 1rem;
      border-radius: 12px;
      background: white;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      border-left: 4px solid transparent;
    }

    .recent-posts-widget-2 .post-item:hover {
      transform: translateX(8px);
      box-shadow: 0 6px 20px rgba(46, 163, 89, 0.15);
      border-left-color: var(--accent-color);
    }

    .recent-posts-widget-2 .post-item h4 a {
      color: var(--heading-color);
      font-size: 1rem;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .recent-posts-widget-2 .post-item:hover h4 a {
      color: var(--accent-color);
    }

    .recent-posts-widget-2 .post-item time {
      color: #999;
      font-size: 0.85rem;
    }

    /* Sidebar Widgets */
    .widget-item {
      background: white;
      padding: 2rem;
      border-radius: 16px;
      margin-bottom: 2rem;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease;
    }

    .widget-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 28px rgba(0, 0, 0, 0.12);
    }

    .widget-title {
      color: var(--heading-color);
      font-size: 1.4rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      padding-bottom: 0.8rem;
      border-bottom: 3px solid var(--accent-color);
    }

    /* Back to Blog Button */
    .back-to-blog {
      display: inline-block;
      margin-bottom: 2rem;
      padding: 0.8rem 1.5rem;
      background: linear-gradient(135deg, var(--accent-color), #2ea359);
      color: white;
      text-decoration: none;
      border-radius: 25px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(46, 163, 89, 0.3);
    }

    .back-to-blog:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(46, 163, 89, 0.4);
      color: white;
    }

    .back-to-blog i {
      transition: transform 0.3s ease;
    }

    .back-to-blog:hover i {
      transform: translateX(-5px);
    }

    /* Share Buttons */
    .share-buttons {
      margin: 2rem 0;
      padding: 1.5rem;
      background: #f8f9fa;
      border-radius: 12px;
    }

    .share-buttons h5 {
      margin-bottom: 1rem;
      color: var(--heading-color);
      font-weight: 700;
    }

    .share-btn {
      display: inline-block;
      width: 45px;
      height: 45px;
      line-height: 45px;
      text-align: center;
      border-radius: 50%;
      color: white;
      margin-right: 0.5rem;
      transition: all 0.3s ease;
      font-size: 1.2rem;
    }

    .share-btn:hover {
      transform: translateY(-5px) rotate(360deg);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .share-btn.facebook { background: #3b5998; }
    .share-btn.twitter { background: #1da1f2; }
    .share-btn.linkedin { background: #0077b5; }
    .share-btn.whatsapp { background: #25d366; }

    @media (max-width: 768px) {
      .title {
        font-size: 1.8rem;
      }
    }
  </style>

</head>

<body class="blog-details-page">

  <header id="header" class="header d-flex align-items-center position-relative">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="CooFICongo">
      </a>

      <nav id="navmenu" class="navmenu stagger-animation">
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="about.html">About Us</a></li>
          <li><a href="services.html">Our Services</a></li>
          <li><a href="testimonials.html">Testimonials</a></li>
          <li><a href="blog.php">Blog</a></li>
          <li><a href="activity.php">Activities</a></li>
          <li class="dropdown"><a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="#">Dropdown 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                <ul>
                  <li><a href="#">Deep Dropdown 1</a></li>
                  <li><a href="#">Deep Dropdown 2</a></li>
                  <li><a href="#">Deep Dropdown 3</a></li>
                  <li><a href="#">Deep Dropdown 4</a></li>
                  <li><a href="#">Deep Dropdown 5</a></li>
                </ul>
              </li>
              <li><a href="#">Dropdown 2</a></li>
              <li><a href="#">Dropdown 3</a></li>
              <li><a href="#">Dropdown 4</a></li>
            </ul>
          </li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade" style="background-image: url(assets/img/page-title-bg.webp);">
      <div class="container position-relative">
        <h1>Blog Details</h1>
        <p><?php echo htmlspecialchars($post['title']); ?></p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li class="current">Blog Details</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <div class="container">
      <div class="row">

        <div class="col-lg-8">

          <!-- Back to Blog Button -->
          <div class="mt-4">
            <a href="blog.php" class="back-to-blog">
              <i class="bi bi-arrow-left me-2"></i>Back to Blog
            </a>
          </div>

          <!-- Blog Details Section -->
          <section id="blog-details" class="blog-details section">
            <div class="container">

              <article class="article">

                <div class="post-img">
                  <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="img-fluid" onerror="this.src='assets/img/blog/blog-1.jpg'">
                </div>

                <h2 class="title"><?php echo htmlspecialchars($post['title']); ?></h2>

                <div class="meta-top">
                  <ul>
                    <li class="d-flex align-items-center">
                      <i class="bi bi-person"></i> 
                      <a href="#">CooFICongo Author</a>
                    </li>
                    <li class="d-flex align-items-center">
                      <i class="bi bi-clock"></i> 
                      <a href="#">
                        <time datetime="<?php echo $post['created_at']; ?>">
                          <?php 
                            $date = new DateTime($post['created_at']);
                            echo $date->format('F d, Y'); 
                          ?>
                        </time>
                      </a>
                    </li>
                    <li class="d-flex align-items-center">
                      <i class="bi bi-folder2"></i> 
                      <a href="#"><?php echo htmlspecialchars($post['category']); ?></a>
                    </li>
                  </ul>
                </div><!-- End meta top -->

                <div class="content">
                  <?php 
                    // Convert line breaks to paragraphs for better formatting
                    $body = htmlspecialchars($post['body']);
                    $paragraphs = explode("\n\n", $body);
                    
                    foreach ($paragraphs as $paragraph) {
                      if (trim($paragraph)) {
                        echo "<p>" . nl2br(trim($paragraph)) . "</p>";
                      }
                    }
                  ?>
                </div><!-- End post content -->

                <!-- Additional Images Gallery -->
                <?php 
                  $additional_images = [];
                  if (!empty($post['image_1'])) $additional_images[] = $post['image_1'];
                  if (!empty($post['image_2'])) $additional_images[] = $post['image_2'];
                  if (!empty($post['image_3'])) $additional_images[] = $post['image_3'];
                  
                  if (!empty($additional_images)): 
                ?>
                <div class="additional-images-gallery mt-4">
                  <h4 class="mb-3" style="color: var(--accent-color); font-weight: 700;">
                    <i class="bi bi-images me-2"></i>More Images
                  </h4>
                  <div class="row g-3">
                    <?php foreach ($additional_images as $img): ?>
                    <div class="col-md-<?php echo count($additional_images) === 1 ? '12' : (count($additional_images) === 2 ? '6' : '4'); ?>">
                      <div class="additional-img-wrapper" style="position: relative; overflow: hidden; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <img src="<?php echo htmlspecialchars($img); ?>" 
                             alt="Additional blog image" 
                             class="img-fluid" 
                             style="width: 100%; height: 250px; object-fit: cover; transition: transform 0.3s ease;"
                             onerror="this.style.display='none'"
                             onmouseover="this.style.transform='scale(1.05)'"
                             onmouseout="this.style.transform='scale(1)'">
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <?php endif; ?>

                <!-- Share Buttons -->
                <div class="share-buttons">
                  <h5><i class="bi bi-share me-2"></i>Share this post</h5>
                  <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-btn facebook" title="Share on Facebook">
                    <i class="bi bi-facebook"></i>
                  </a>
                  <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($post['title']); ?>" target="_blank" class="share-btn twitter" title="Share on Twitter">
                    <i class="bi bi-twitter-x"></i>
                  </a>
                  <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-btn linkedin" title="Share on LinkedIn">
                    <i class="bi bi-linkedin"></i>
                  </a>
                  <a href="https://wa.me/?text=<?php echo urlencode($post['title'] . ' - ' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-btn whatsapp" title="Share on WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                  </a>
                </div>

                <div class="meta-bottom">
                  <i class="bi bi-folder"></i>
                  <ul class="cats">
                    <li><a href="blog.php?category=<?php echo urlencode($post['category']); ?>"><?php echo htmlspecialchars($post['category']); ?></a></li>
                  </ul>
                </div><!-- End meta bottom -->

              </article>

            </div>
          </section><!-- /Blog Details Section -->

          <!-- Blog Comments Section -->
          <section id="blog-comments" class="blog-comments section">
            <div class="container" id="comments">

              <h4 class="comments-count"><?php echo (int)$comments_count; ?> Comment<?php echo $comments_count === 1 ? '' : 's'; ?></h4>

              <?php if ($commentError): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($commentError); ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php endif; ?>

              <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $c): ?>
                  <?php 
                    $avatar = !empty($c['profile_picture']) ? $c['profile_picture'] : 'assets/img/team/team-1.jpg';
                    // Show fullname for guests, username for members, or 'Member' as fallback
                    $displayName = !empty($c['fullname']) ? $c['fullname'] : (!empty($c['username']) ? $c['username'] : 'Member');
                  ?>
                  <div id="comment-<?php echo htmlspecialchars($c['uuid']); ?>" class="comment">
                    <div class="d-flex">
                      <div class="comment-img"><img src="<?php echo htmlspecialchars($avatar); ?>" alt="" onerror="this.src='assets/img/team/team-1.jpg'" style="object-fit:cover;width:60px;height:60px;"></div>
                      <div>
                        <h5><a href="#"><?php echo htmlspecialchars($displayName); ?></a></h5>
                        <time datetime="<?php echo htmlspecialchars($c['created_at']); ?>">
                          <?php 
                            try { $cd = new DateTime($c['created_at']); echo $cd->format('M d, Y H:i'); } catch (Exception $e) { echo htmlspecialchars($c['created_at']); }
                          ?>
                        </time>
                        <p><?php echo nl2br(htmlspecialchars($c['comment'])); ?></p>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="text-muted py-3">Be the first to comment on this post.</div>
              <?php endif; ?>

            </div>
          </section><!-- /Blog Comments Section -->

          <!-- Comment Form Section -->
          <section id="comment-form" class="comment-form section">
            <div class="container">
              <form action="blog-details.php?id=<?php echo urlencode($post['uuid']); ?>#comments" method="post">
                <h4>Post Comment</h4>
                <p>Your email address will not be published. Required fields are marked * </p>

                <?php if (!isset($_SESSION['user_uuid'])): ?>
                <div class="row">
                  <div class="col-md-6 form-group">
                    <input name="fullname" type="text" class="form-control" placeholder="Your Name*" maxlength="50" required>
                  </div>
                </div>
                <?php endif; ?>

                <div class="row">
                  <div class="col form-group">
                    <textarea name="comment" class="form-control" placeholder="Your Comment*" rows="4" maxlength="1000" required></textarea>
                  </div>
                </div>

                <div class="text-start mt-2">
                  <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Post Comment</button>
                </div>
              </form>
            </div>
          </section><!-- /Comment Form Section -->

        </div>

        <div class="col-lg-4 sidebar">

          <div class="widgets-container">

            <!-- Search Widget -->
            <div class="search-widget widget-item">
              <h3 class="widget-title">Search</h3>
              <form action="blog.php" method="get">
                <input type="text" name="search" placeholder="Search blog posts...">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
              </form>
            </div><!--/Search Widget -->

            <!-- Categories Widget -->
            <div class="categories-widget widget-item">
              <h3 class="widget-title">Categories</h3>
              <ul class="mt-3">
                <?php if (!empty($categories)): ?>
                  <?php foreach ($categories as $cat): ?>
                    <li><a href="blog.php?category=<?php echo urlencode($cat['category']); ?>"><?php echo htmlspecialchars($cat['category']); ?> <span>(<?php echo (int)$cat['count']; ?>)</span></a></li>
                  <?php endforeach; ?>
                <?php else: ?>
                  <li class="text-muted">No categories available</li>
                <?php endif; ?>
              </ul>
            </div><!--/Categories Widget -->

            <!-- Recent Posts Widget -->
            <?php if (!empty($relatedPosts)): ?>
            <div class="recent-posts-widget-2 widget-item">
              <h3 class="widget-title">Related Posts</h3>

              <?php foreach ($relatedPosts as $relatedPost): ?>
              <div class="post-item">
                <h4>
                  <a href="blog-details.php?id=<?php echo htmlspecialchars($relatedPost['uuid']); ?>">
                    <?php echo htmlspecialchars($relatedPost['title']); ?>
                  </a>
                </h4>
                <time datetime="<?php echo $relatedPost['created_at']; ?>">
                  <?php 
                    $relatedDate = new DateTime($relatedPost['created_at']);
                    echo $relatedDate->format('M d, Y'); 
                  ?>
                </time>
              </div><!-- End recent post item-->
              <?php endforeach; ?>

            </div><!--/Recent Posts Widget -->
            <?php endif; ?>

          </div>

        </div>

      </div>
    </div>

  </main>

  <footer id="footer" class="footer dark-background">

    <div class="footer-top">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename">CooFICongo</span>
            </a>
            <div class="footer-contact pt-3">
              <p>Paroisse Notre Dame du Perpétuel Secours, </p>
              <p>Ignié centre PK47 département du Pool Congo Brazzaville</p>
              <p class="mt-3"><strong>Phone:</strong> <span>+242 05 537 6788</span></p>
              <p class="mt-3"><strong>Phone:</strong> <span>+242 06 687 3182</span></p>
              <p><strong>Email:</strong> <span>Coofi.apsf@gmail.com</span></p>
            </div>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="index.html">Home</a></li>
              <li><a href="about.html">About us</a></li>
              <li><a href="services.html">Services</a></li>
              <li><a href="testimonials.html">Terms of service</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Nos Services</h4>
            <ul>
              <li><a href="#services">Plantation</a></li>
              <li><a href="#services">Paillage</a></li>
              <li><a href="#services">Labour</a></li>
              <li><a href="#services">Semis & Récolte</a></li>
              <li><a href="#services">Manioc Frais</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Hic solutasetp</h4>
            <ul>
              <li><a href="#">Molestiae accusamus iure</a></li>
              <li><a href="#">Excepturi dignissimos</a></li>
              <li><a href="#">Suscipit distinctio</a></li>
              <li><a href="#">Dilecta</a></li>
              <li><a href="#">Sit quas consectetur</a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4>Nobis illum</h4>
            <ul>
              <li><a href="#">Ipsam</a></li>
              <li><a href="#">Laudantium dolorum</a></li>
              <li><a href="#">Dinera</a></li>
              <li><a href="#">Trodelas</a></li>
              <li><a href="#">Flexo</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>

    <div class="copyright text-center">
      <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">

        <div class="d-flex flex-column align-items-center align-items-lg-start">
          <div>
            © Copyright <strong><span>CooFICongo</span></strong>. All Rights Reserved
          </div>
          <div class="credits">
            Designed by <a href="https://freelancesoutions.vercel.app/">FREELANCE SOLUTIONS</a> Distributed by <a href="https://freelancesoutions.vercel.app/">FS</a>
          </div>
        </div>

        <div class="social-links order-first order-lg-last mb-3 mb-lg-0">
          <a href=""><i class="bi bi-twitter-x"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
        </div>

      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
