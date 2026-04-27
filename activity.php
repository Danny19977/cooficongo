<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Activities - CooFICongo</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/logo2_icon.png" rel="icon">
  <link href="assets/img/logo2_icon.png" rel="logo2_icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Marcellus:wght@400&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <script src="assets/js/i18n.js" defer></script>

  <!-- Custom Activity Styles -->
  <style>
    /* Skeleton Loader Animation */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
      border-radius: 8px;
    }

    @keyframes loading {
      0% {
        background-position: 200% 0;
      }

      100% {
        background-position: -200% 0;
      }
    }

    .skeleton-img {
      height: 250px;
      width: 100%;
      margin-bottom: 1rem;
    }

    .skeleton-text {
      height: 20px;
      margin-bottom: 10px;
      width: 100%;
    }

    .skeleton-text.short {
      width: 60%;
    }

    /* Activity Card Enhancements */
    article.activity-card {
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      overflow: hidden;
      height: 100%;
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 1.5rem;
    }

    article.activity-card:hover {
      transform: translateY(-15px) scale(1.02);
      box-shadow: 0 20px 40px rgba(46, 163, 89, 0.2);
    }

    article.activity-card .activity-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--accent-color), #2ea359);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 1rem;
      transition: transform 0.4s ease;
    }

    article.activity-card:hover .activity-icon {
      transform: rotate(360deg) scale(1.1);
    }

    article.activity-card .activity-icon i {
      font-size: 2.5rem;
      color: white;
    }

    .activity-title {
      transition: color 0.3s ease;
      font-weight: 700;
      letter-spacing: 0.5px;
      margin-bottom: 0.75rem;
    }

    article.activity-card:hover .activity-title {
      color: var(--accent-color);
    }

    .activity-date {
      display: inline-block;
      background: linear-gradient(135deg, var(--accent-color), #2ea359);
      color: white;
      padding: 6px 15px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 0.75rem;
      box-shadow: 0 2px 8px rgba(46, 163, 89, 0.3);
    }

    .activity-location {
      color: #666;
      font-size: 0.9rem;
      margin-bottom: 0.75rem;
    }

    .activity-location i {
      color: var(--accent-color);
      margin-right: 5px;
    }

    .activity-summary {
      color: #777;
      margin-bottom: 1rem;
      line-height: 1.6;
    }

    .readmore {
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
      display: inline-block;
      color: var(--accent-color);
      font-weight: 600;
      text-decoration: none;
    }

    .readmore::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
      transition: left 0.5s ease;
    }

    article.activity-card:hover .readmore::before {
      left: 100%;
    }

    .readmore i {
      transition: transform 0.3s ease;
    }

    article.activity-card:hover .readmore i {
      transform: translateX(5px);
    }

    /* Status Badge */
    .status-badge {
      position: absolute;
      top: 1rem;
      right: 1rem;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .status-badge.upcoming {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
    }

    .status-badge.past {
      background: linear-gradient(135deg, #6c757d, #495057);
      color: white;
    }

    /* Fade In Animation */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .activity-card-animated {
      animation: fadeInUp 0.6s ease-out forwards;
      opacity: 0;
    }

    /* Location Badge */
    .location-badge {
      background: linear-gradient(135deg, var(--accent-color), #2ea359);
      color: white;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
      display: inline-block;
      margin-left: 8px;
      box-shadow: 0 2px 8px rgba(46, 163, 89, 0.3);
      transition: transform 0.3s ease;
    }

    article.activity-card:hover .location-badge {
      transform: scale(1.1);
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
    }

    .empty-state i {
      font-size: 5rem;
      color: #ccc;
      margin-bottom: 20px;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-20px);
      }
    }

    /* Pagination Enhancement */
    #activity-pagination ul {
      display: flex;
      list-style: none;
      padding: 0;
      margin: 0;
      gap: 10px;
    }

    #activity-pagination ul li {
      margin: 0;
    }

    #activity-pagination ul li a,
    #activity-pagination ul li span {
      display: inline-block;
      padding: 10px 16px;
      background: white;
      color: var(--heading-color);
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      font-weight: 500;
      min-width: 45px;
      text-align: center;
    }

    #activity-pagination ul li a:hover {
      transform: scale(1.1) translateY(-3px);
      background: var(--accent-color);
      color: white;
      box-shadow: 0 4px 16px rgba(46, 163, 89, 0.3);
    }

    #activity-pagination ul li a.active {
      background: var(--accent-color);
      color: white;
      box-shadow: 0 4px 16px rgba(46, 163, 89, 0.3);
    }

    #activity-pagination ul li span {
      background: transparent;
      box-shadow: none;
      color: #999;
    }

    /* Location Filter Buttons */
    #locationsContainer .btn {
      transition: all 0.3s ease;
      border-radius: 25px;
      font-weight: 500;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    #locationsContainer .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(46, 163, 89, 0.2);
    }

    #locationsContainer .btn-success {
      background: linear-gradient(135deg, var(--accent-color), #2ea359);
      border: none;
    }

    #locationsContainer .btn-outline-success:hover {
      background: var(--accent-color);
      border-color: var(--accent-color);
      color: white;
    }

    #locationsContainer .badge {
      font-size: 0.75rem;
      padding: 0.25em 0.5em;
    }
  </style>

</head>

<body class="activity-page">

  <header id="header" class="header d-flex align-items-center position-relative">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="CooFICongo" width="200" height="400">
      </a>

      <!-- Desktop Navigation -->
      <nav class="desktop-nav">
        <ul class="nav-links">
          <li><a href="index.html"><span data-i18n="nav.home">Accueil</span></a></li>
          <li><a href="about.html"><span data-i18n="nav.about">À propos</span></a></li>
          <li><a href="services.html"><span data-i18n="nav.services">Nous Faisons</span></a></li>
          <li><a href="blog.php"><span data-i18n="nav.blog">Blog</span></a></li>
          <li><a href="activity.php" class="active"><span data-i18n="nav.activities">Activités</span></a></li>
          <li><a href="contact.html"><span data-i18n="nav.contact">Contact</span></a></li>
          <li><a href="donation.html" class="btn-donate-nav"><span data-i18n="nav.donate">Faire un don</span></a></li>
          <li class="dropdown language-dropdown">
            <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-translate me-1"></i><span data-i18n="lang.current">Français</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#" data-lang="fr">Français</a></li>
              <li><a class="dropdown-item" href="#" data-lang="en">English</a></li>
            </ul>
          </li>
        </ul>
      </nav>

      <!-- Mobile Hamburger -->
      <button class="btn d-lg-none" type="button" data-bs-toggle="modal" data-bs-target="#navbarModal" aria-label="Toggle mobile menu">
        <i class="bi bi-list" style="font-size: 32px; color: var(--nav-color);"></i>
      </button>

    </div>
  </header>

  <!-- Bootstrap Modal for Mobile Navigation -->
  <div class="modal fade" id="navbarModal" tabindex="-1" aria-labelledby="navbarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="navbarModalLabel">
            <img src="assets/img/logo.png" alt="CooFICongo" style="height: 40px;">
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <nav class="mobile-nav-menu">
            <ul class="list-unstyled">
              <li><a href="index.html" class="mobile-nav-link" data-bs-dismiss="modal"><i class="bi bi-house-door me-2"></i><span data-i18n="nav.home">Accueil</span></a></li>
              <li><a href="about.html" class="mobile-nav-link" data-bs-dismiss="modal"><i class="bi bi-info-circle me-2"></i><span data-i18n="nav.about">À propos</span></a></li>
              <li><a href="services.html" class="mobile-nav-link" data-bs-dismiss="modal"><i class="bi bi-gear me-2"></i><span data-i18n="nav.services">Nous Faisons</span></a></li>
              <li><a href="blog.php" class="mobile-nav-link" data-bs-dismiss="modal"><i class="bi bi-journal-text me-2"></i><span data-i18n="nav.blog">Blog</span></a></li>
              <li><a href="activity.php" class="mobile-nav-link active" data-bs-dismiss="modal"><i class="bi bi-calendar-event me-2"></i><span data-i18n="nav.activities">Activités</span></a></li>
              <li><a href="contact.html" class="mobile-nav-link" data-bs-dismiss="modal"><i class="bi bi-envelope me-2"></i><span data-i18n="nav.contact">Contact</span></a></li>
              <li><a href="donation.html" class="mobile-nav-link btn-donate-mobile" data-bs-dismiss="modal"><i class="bi bi-heart me-2"></i><span data-i18n="nav.donate">Faire un don</span></a></li>
            </ul>
            <div class="mt-4">
              <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-lang="fr">Français</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" data-lang="en">English</button>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </div>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title dark-background" data-aos="fade"
      style="background-image: url(assets/img/page-title-bg.webp);">
      <div class="container position-relative">
        <h1 data-i18n="page.activities">Nos Activités</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html" data-i18n="breadcrumbs.home">Accueil</a></li>
            <li class="current" data-i18n="breadcrumbs.activities">Activités</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Location Filter Section -->
    <section class="py-4" style="background: #f8f9fa;">
      <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i><span data-i18n="cta.locations_label">Emplacements :</span></h5>
          <div id="locationsContainer" class="d-flex flex-wrap gap-2">
            <!-- Locations will be loaded here -->
          </div>
        </div>
      </div>
    </section><!-- End Location Filter Section -->

    <!-- Activities Section -->
    <section id="activities-section" class="activities-section section">

      <div class="container">
        <div class="row gy-4" id="activitiesContainer">
          <!-- Loading Skeletons (shown while loading) -->
          <div class="col-lg-4 skeleton-loader">
            <div class="skeleton skeleton-img"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text short"></div>
          </div>
          <div class="col-lg-4 skeleton-loader">
            <div class="skeleton skeleton-img"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text short"></div>
          </div>
          <div class="col-lg-4 skeleton-loader">
            <div class="skeleton skeleton-img"></div>
            <div class="skeleton skeleton-text"></div>
            <div class="skeleton skeleton-text short"></div>
          </div>
        </div>
      </div>

    </section><!-- /Activities Section -->

    <!-- Activity Pagination Section -->
    <section id="activity-pagination" class="activity-pagination section">
      <div class="container">
        <div class="d-flex justify-content-center">
          <ul id="paginationContainer">
            <!-- Pagination will be dynamically generated -->
          </ul>
        </div>
      </div>
    </section><!-- /Activity Pagination Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section light-background">

      <div class="content">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <h3 data-i18n="cta.join_event">Rejoignez notre prochain événement</h3>
              <p class="opacity-50" data-i18n="cta.stay_updated">
                Restez informé de nos dernières activités et événements !
              </p>
            </div>
            <div class="col-lg-6">
              <form action="forms/newsletter.php" class="form-subscribe php-email-form">
                <div class="form-group d-flex align-items-stretch">
                  <input type="email" name="email" class="form-control h-100" data-i18n-placeholder="newsletter.placeholder.email" placeholder="Entrez votre e-mail">
                  <input type="submit" class="btn btn-secondary px-4" data-i18n-value="newsletter.subscribe" value="S'abonner">
                </div>
                <div class="loading" data-i18n="cta.loading">Chargement...</div>
                <div class="error-message"></div>
                <div class="sent-message" data-i18n="cta.sent">
                  Votre demande d'abonnement a été envoyée. Merci !
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section><!-- /Call To Action Section -->

  </main>

  <footer id="footer" class="footer dark-background">

    <div class="footer-top">
      <div class="container">
        <div class="row gy-4 stagger-animation">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename">COOFICONGO</span>
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
            <h4 data-i18n="footer.useful_links">Liens utiles</h4>
            <ul>
              <li><a href="index.html" data-i18n="footer.home">Accueil</a></li>
              <li><a href="about.html" data-i18n="footer.about">À propos</a></li>
              <li><a href="services.html" data-i18n="footer.services">Nous Faisons</a></li>
              <!-- <li><a href="testimonials.html">Testimonials</a></li> -->
              <!-- <li><a href="#">Privacy policy</a></li> -->
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4 data-i18n="footer.what_we_do">Ce que nous faisons</h4>
            <ul>
              <li><a href="our_service_info.html#production-only">Plantation</a></li>
              <li><a href="our_service_info.html#transformation-manioc">Transformation du manioc en chikwangue</a></li>
              <li><a href="our_service_info.html#production-only">Production durable de manioc</a></li>
              <li><a href="#services"></a></li>
              <li><a href="#services"></a></li>
            </ul>
          </div>

          <div class="col-lg-2 col-md-3 footer-links">
            <h4 data-i18n="footer.activities">Activités</h4>
            <ul>
              <li><a href="blog.php" data-i18n="footer.blog">Blog</a></li>
              <li><a href="activity.php" data-i18n="footer.our_activities">Nos activités</a></li>
              <li><a href="contact.html" data-i18n="footer.contact">Contact</a></li>
              <li><a href="donation.html" data-i18n="footer.donate">Faire un don</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>

    <div class="copyright text-center">
      <div class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">

        <div class="d-flex flex-column align-items-center align-items-lg-start">
          <div>
            © Copyright <strong><span>FreelanceSolutions</span></strong>. All Rights Reserved
          </div>
          <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/herobiz-bootstrap-business-template/ -->
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
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

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

  <!-- Activity Dynamic Loading Script -->
  <script>
    // Configuration
    const activitiesPerPage = 6;
    let currentPage = 1;
    let totalActivities = 0;
    let allActivities = [];
    let allLocations = [];
    let currentLocation = null;

    // Load activities on page load
    document.addEventListener('DOMContentLoaded', function () {
      loadActivities();
    });

    // Fetch activities from PHP backend
    function loadActivities() {
      fetch('php/activitiesdisplay.php?json=true')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.activities) {
            allActivities = data.activities;
            allLocations = data.locations || [];
            totalActivities = data.count;
            displayLocations();
            displayActivities(currentPage);
            setupPagination();
          } else {
            showEmptyState();
          }
        })
        .catch(error => {
          console.error('Error loading activities:', error);
          showErrorState();
        });
    }

    // Display location filter
    function displayLocations() {
      const container = document.getElementById('locationsContainer');
      if (!container) return;

      container.innerHTML = '';

      // Add "All" location
      const allBtn = document.createElement('button');
      allBtn.className = `btn btn-sm ${currentLocation === null ? 'btn-success' : 'btn-outline-success'}`;
      allBtn.innerHTML = '<i class="bi bi-globe me-1"></i>All';
      allBtn.onclick = () => filterByLocation(null);
      container.appendChild(allBtn);

      // Add dynamic locations
      allLocations.forEach(loc => {
        const btn = document.createElement('button');
        btn.className = `btn btn-sm ${currentLocation === loc.location ? 'btn-success' : 'btn-outline-success'}`;
        btn.innerHTML = `${escapeHtml(loc.location)} <span class="badge bg-light text-dark ms-1">${loc.count}</span>`;
        btn.onclick = () => filterByLocation(loc.location);
        container.appendChild(btn);
      });
    }

    // Filter activities by location
    function filterByLocation(location) {
      currentLocation = location;
      currentPage = 1;
      displayLocations();
      displayActivities(currentPage);
      setupPagination();
      scrollToTop();
    }

    // Display activities for current page
    function displayActivities(page) {
      const container = document.getElementById('activitiesContainer');

      // Filter activities by location if selected
      let filteredActivities = currentLocation
        ? allActivities.filter(activity => activity.location === currentLocation)
        : allActivities;

      totalActivities = filteredActivities.length;

      const startIndex = (page - 1) * activitiesPerPage;
      const endIndex = startIndex + activitiesPerPage;
      const activitiesToShow = filteredActivities.slice(startIndex, endIndex);

      if (activitiesToShow.length === 0) {
        showEmptyState();
        return;
      }

      // Clear skeleton loaders and existing content
      container.innerHTML = '';

      // Create and display activity cards
      activitiesToShow.forEach((activity, index) => {
        const activityCard = createActivityCard(activity, index);
        container.appendChild(activityCard);
      });
    }

    // Create activity card element
    function createActivityCard(activity, index) {
      const col = document.createElement('div');
      col.className = 'col-lg-4 activity-card-animated';
      col.style.animationDelay = `${index * 0.1}s`;

      // Format date
      const activityDate = new Date(activity.activity_date);
      const formattedDate = activityDate.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });

      // Determine if upcoming or past
      const now = new Date();
      const isPast = activityDate < now;
      const statusClass = isPast ? 'past' : 'upcoming';
      const statusText = isPast ? 'Past Event' : 'Upcoming';

      // Truncate summary for preview
      const summaryPreview = activity.summary.length > 100 ? activity.summary.substring(0, 100) + '...' : activity.summary;

      col.innerHTML = `
        <article class="activity-card position-relative">
          <span class="status-badge ${statusClass}">${statusText}</span>
          
          <div class="activity-icon">
            <i class="bi bi-calendar-event"></i>
          </div>

          <h3 class="activity-title">${escapeHtml(activity.title)}</h3>
          
          <div class="activity-date">
            <i class="bi bi-calendar3 me-2"></i>${formattedDate}
          </div>
          
          <div class="activity-location">
            <i class="bi bi-geo-alt"></i>${escapeHtml(activity.location)}
          </div>
          
          <p class="activity-summary">${escapeHtml(summaryPreview)}</p>
          
          <a href="event-details.php?id=${escapeHtml(activity.uuid)}" class="readmore">
            <span>Learn More</span><i class="bi bi-arrow-right ms-2"></i>
          </a>
        </article>
      `;

      return col;
    }

    // Setup pagination controls
    function setupPagination() {
      const paginationSection = document.getElementById('activity-pagination');
      const paginationContainer = document.getElementById('paginationContainer');
      const totalPages = Math.ceil(totalActivities / activitiesPerPage);

      if (totalPages <= 1) {
        paginationSection.style.display = 'none';
        return;
      }

      paginationSection.style.display = 'block';
      paginationContainer.innerHTML = '';

      // Previous button
      const prevLi = document.createElement('li');
      const prevDisabled = currentPage === 1;
      prevLi.innerHTML = `<a href="#" ${prevDisabled ? 'style="opacity: 0.5; pointer-events: none;"' : ''}><i class="bi bi-chevron-left"></i></a>`;
      if (!prevDisabled) {
        prevLi.addEventListener('click', (e) => {
          e.preventDefault();
          currentPage--;
          displayActivities(currentPage);
          setupPagination();
          scrollToTop();
        });
      }
      paginationContainer.appendChild(prevLi);

      // Page numbers with smart ellipsis
      for (let i = 1; i <= totalPages; i++) {
        // Always show first page, last page, current page, and pages around current
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
          const li = document.createElement('li');
          li.innerHTML = `<a href="#" ${i === currentPage ? 'class="active"' : ''}>${i}</a>`;
          li.addEventListener('click', (e) => {
            e.preventDefault();
            currentPage = i;
            displayActivities(currentPage);
            setupPagination();
            scrollToTop();
          });
          paginationContainer.appendChild(li);
        } else if (i === currentPage - 2 || i === currentPage + 2) {
          // Show ellipsis
          const li = document.createElement('li');
          li.innerHTML = `<span>...</span>`;
          paginationContainer.appendChild(li);
        }
      }

      // Next button
      const nextLi = document.createElement('li');
      const nextDisabled = currentPage === totalPages;
      nextLi.innerHTML = `<a href="#" ${nextDisabled ? 'style="opacity: 0.5; pointer-events: none;"' : ''}><i class="bi bi-chevron-right"></i></a>`;
      if (!nextDisabled) {
        nextLi.addEventListener('click', (e) => {
          e.preventDefault();
          currentPage++;
          displayActivities(currentPage);
          setupPagination();
          scrollToTop();
        });
      }
      paginationContainer.appendChild(nextLi);
    }

    // Show empty state when no activities
    function showEmptyState() {
      const container = document.getElementById('activitiesContainer');
      container.innerHTML = `
        <div class="col-12 empty-state">
          <i class="bi bi-calendar-x"></i>
          <h3>No Activities Yet</h3>
          <p class="text-muted">Check back soon for exciting new events and activities!</p>
        </div>
      `;
      document.getElementById('activity-pagination').style.display = 'none';
    }

    // Show error state
    function showErrorState() {
      const container = document.getElementById('activitiesContainer');
      container.innerHTML = `
        <div class="col-12 empty-state">
          <i class="bi bi-exclamation-triangle text-warning"></i>
          <h3>Oops! Something went wrong</h3>
          <p class="text-muted">We couldn't load the activities. Please try again later.</p>
          <button class="btn btn-primary mt-3" onclick="loadActivities()">
            <i class="bi bi-arrow-clockwise me-2"></i>Retry
          </button>
        </div>
      `;
      document.getElementById('activity-pagination').style.display = 'none';
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
      const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Scroll to top of activities section
    function scrollToTop() {
      document.getElementById('activities-section').scrollIntoView({ behavior: 'smooth' });
    }
  </script>

</body>

</html>