<?php
// Get event ID from URL
$event_id = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch specific event
require_once 'php/connection.php';

$event = null;
$relatedEvents = [];

if ($event_id) {
  // Fetch the specific event
  $stmt = $conn->prepare("SELECT uuid, user_uuid, title, image, image_2, image_3, image_4, image_5, summary, description, activity_date, location, created_at, updated_at FROM activitiespost WHERE uuid = ?");
  $stmt->bind_param("s", $event_id);
  $stmt->execute();
  $result = $stmt->get_result();
    
  if ($result->num_rows > 0) {
    $event = $result->fetch_assoc();
        
    // Fetch related events from the same location
    $location = $event['location'];
    $stmt2 = $conn->prepare("SELECT uuid, title, summary, activity_date, location, created_at FROM activitiespost WHERE location = ? AND uuid != ? ORDER BY activity_date DESC LIMIT 5");
    $stmt2->bind_param("ss", $location, $event_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
        
    while($row = $result2->fetch_assoc()) {
      $relatedEvents[] = $row;
    }
    $stmt2->close();
  }
  $stmt->close();
}

$conn->close();

// If no event found, redirect to activities page
if (!$event) {
  header("Location: activity.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title><?php echo htmlspecialchars($event['title']); ?> - CooFICongo Event</title>
  <meta name="description" content="<?php echo htmlspecialchars(substr($event['description'], 0, 160)); ?>">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

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
    /* Event Details Enhancements */
    .event-details {
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

    .event-main-img {
      position: relative;
      overflow: hidden;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      margin-bottom: 2rem;
    }

    .event-main-img img {
      width: 100%;
      height: 400px;
      object-fit: cover;
      transition: transform 0.5s ease;
    }

    .event-main-img:hover img {
      transform: scale(1.05);
    }

    .event-title {
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

    .event-meta {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      padding: 1.5rem 2rem;
      border-radius: 12px;
      margin-bottom: 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .event-meta ul {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .event-meta li {
      font-size: 1rem;
      color: #666;
      display: flex;
      align-items: center;
    }

    .event-meta li i {
      color: var(--accent-color);
      margin-right: 8px;
      font-size: 1.2rem;
      transition: transform 0.3s ease;
    }

    .event-meta li:hover i {
      transform: scale(1.2) rotate(10deg);
    }

    .event-summary {
      background: linear-gradient(135deg, #e8f5e9, #f1f8e9);
      border-left: 4px solid var(--accent-color);
      padding: 1.5rem 2rem;
      margin: 2rem 0;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: 500;
      color: #2d5016;
      box-shadow: 0 4px 12px rgba(46, 163, 89, 0.1);
    }

    .event-description {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #444;
      animation: fadeIn 1s ease-out 0.3s backwards;
    }

    .event-description p {
      margin-bottom: 1.5rem;
    }

    /* Images Gallery */
    .event-images-gallery {
      margin: 3rem 0;
    }

    .event-images-gallery h4 {
      color: var(--accent-color);
      font-weight: 700;
      margin-bottom: 1.5rem;
    }

    .event-img-wrapper {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      margin-bottom: 1rem;
    }

    .event-img-wrapper img {
      width: 100%;
      height: 250px;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .event-img-wrapper:hover img {
      transform: scale(1.05);
    }

    /* Back Button */
    .back-to-activities {
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

    .back-to-activities:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(46, 163, 89, 0.4);
      color: white;
    }

    .back-to-activities i {
      transition: transform 0.3s ease;
    }

    .back-to-activities:hover i {
      transform: translateX(-5px);
    }

    /* Related Events Widget */
    .related-events-widget {
      background: white;
      padding: 2rem;
      border-radius: 16px;
      margin-bottom: 2rem;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease;
    }

    .related-events-widget:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 28px rgba(0, 0, 0, 0.12);
    }

    .related-events-widget .widget-title {
      color: var(--heading-color);
      font-size: 1.4rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      padding-bottom: 0.8rem;
      border-bottom: 3px solid var(--accent-color);
    }

    .related-event-item {
      padding: 1rem;
      margin-bottom: 1rem;
      border-radius: 12px;
      background: white;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      border-left: 4px solid transparent;
    }

    .related-event-item:hover {
      transform: translateX(8px);
      box-shadow: 0 6px 20px rgba(46, 163, 89, 0.15);
      border-left-color: var(--accent-color);
    }

    .related-event-item h4 a {
      color: var(--heading-color);
      font-size: 1rem;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .related-event-item:hover h4 a {
      color: var(--accent-color);
    }

    .related-event-item time {
      color: #999;
      font-size: 0.85rem;
    }

    @media (max-width: 768px) {
      .event-title {
        font-size: 1.8rem;
      }
      
      .event-main-img img {
        height: 250px;
      }
    }
  </style>

</head>

<body class="event-details-page">

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
        <h1>Event Details</h1>
        <p><?php echo htmlspecialchars($event['title']); ?></p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li><a href="activity.php">Activities</a></li>
            <li class="current">Event Details</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <div class="container">
      <div class="row">

        <div class="col-lg-8">

          <!-- Back to Activities Button -->
          <div class="mt-4">
            <a href="activity.php" class="back-to-activities">
              <i class="bi bi-arrow-left me-2"></i>Back to Activities
            </a>
          </div>

          <!-- Event Details Section -->
          <section id="event-details" class="event-details section">
            <div class="container">

              <article class="event-article">

                <?php if (!empty($event['image'])): ?>
                <div class="event-main-img">
                  <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" onerror="this.style.display='none'">
                </div>
                <?php endif; ?>

                <h2 class="event-title"><?php echo htmlspecialchars($event['title']); ?></h2>

                <div class="event-meta">
                  <ul>
                    <li>
                      <i class="bi bi-calendar-event"></i> 
                      <strong>Date:</strong>&nbsp;
                      <?php 
                        $date = new DateTime($event['activity_date']);
                        echo $date->format('F d, Y'); 
                      ?>
                    </li>
                    <li>
                      <i class="bi bi-geo-alt"></i> 
                      <strong>Location:</strong>&nbsp;<?php echo htmlspecialchars($event['location']); ?>
                    </li>
                    <li>
                      <i class="bi bi-clock"></i> 
                      <strong>Posted:</strong>&nbsp;
                      <?php 
                        $created = new DateTime($event['created_at']);
                        echo $created->format('M d, Y'); 
                      ?>
                    </li>
                  </ul>
                </div><!-- End meta -->

                <div class="event-summary">
                  <i class="bi bi-star me-2"></i><?php echo htmlspecialchars($event['summary']); ?>
                </div><!-- End summary -->

                <div class="event-description">
                  <?php 
                    // Convert line breaks to paragraphs for better formatting
                    $description = htmlspecialchars($event['description']);
                    $paragraphs = explode("\n\n", $description);
                    
                    foreach ($paragraphs as $paragraph) {
                      if (trim($paragraph)) {
                        echo "<p>" . nl2br(trim($paragraph)) . "</p>";
                      }
                    }
                  ?>
                </div><!-- End description -->

                <!-- Additional Images Gallery -->
                <?php 
                  $additional_images = [];
                  if (!empty($event['image_2'])) $additional_images[] = $event['image_2'];
                  if (!empty($event['image_3'])) $additional_images[] = $event['image_3'];
                  if (!empty($event['image_4'])) $additional_images[] = $event['image_4'];
                  if (!empty($event['image_5'])) $additional_images[] = $event['image_5'];
                  
                  if (!empty($additional_images)): 
                ?>
                <div class="event-images-gallery">
                  <h4><i class="bi bi-images me-2"></i>Event Gallery</h4>
                  <div class="row g-3">
                    <?php foreach ($additional_images as $img): ?>
                    <div class="col-md-<?php echo count($additional_images) === 1 ? '12' : (count($additional_images) === 2 ? '6' : (count($additional_images) === 3 ? '4' : '3')); ?>">
                      <div class="event-img-wrapper">
                        <img src="<?php echo htmlspecialchars($img); ?>" 
                             alt="Event gallery image" 
                             onerror="this.style.display='none'">
                      </div>
                    </div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <?php endif; ?>

              </article>

            </div>
          </section><!-- /Event Details Section -->

        </div>

        <div class="col-lg-4 sidebar">

          <div class="widgets-container">

            <!-- Related Events Widget -->
            <?php if (!empty($relatedEvents)): ?>
            <div class="related-events-widget">
              <h3 class="widget-title">Related Events</h3>

              <?php foreach ($relatedEvents as $relatedEvent): ?>
              <div class="related-event-item">
                <h4>
                  <a href="event-details.php?id=<?php echo htmlspecialchars($relatedEvent['uuid']); ?>">
                    <?php echo htmlspecialchars($relatedEvent['title']); ?>
                  </a>
                </h4>
                <div class="mb-2">
                  <i class="bi bi-calendar-event me-1 text-success"></i>
                  <time datetime="<?php echo $relatedEvent['activity_date']; ?>">
                    <?php 
                      $relatedDate = new DateTime($relatedEvent['activity_date']);
                      echo $relatedDate->format('M d, Y'); 
                    ?>
                  </time>
                </div>
                <div>
                  <i class="bi bi-geo-alt me-1 text-success"></i>
                  <span><?php echo htmlspecialchars($relatedEvent['location']); ?></span>
                </div>
              </div><!-- End related event item-->
              <?php endforeach; ?>

            </div><!--/Related Events Widget -->
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
