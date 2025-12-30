<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Blog - CooFICongo</title>
  <meta name="description" content="">
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

  <!-- Custom Blog Styles -->
  <style>
    /* Skeleton Loader Animation */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
      border-radius: 8px;
    }

    @keyframes loading {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
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

    /* Blog Post Card Enhancements */
    article.blog-card {
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      overflow: hidden;
      height: 100%;
    }

    article.blog-card:hover {
      transform: translateY(-15px) scale(1.02);
      box-shadow: 0 20px 40px rgba(46, 163, 89, 0.2);
    }

    article.blog-card .post-img {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
    }

    article.blog-card .post-img img {
      transition: transform 0.6s ease;
    }

    article.blog-card:hover .post-img img {
      transform: scale(1.15) rotate(2deg);
    }

    article.blog-card .post-img::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, rgba(46, 163, 89, 0.3), rgba(17, 101, 48, 0.3));
      opacity: 0;
      transition: opacity 0.4s ease;
    }

    article.blog-card:hover .post-img::after {
      opacity: 1;
    }

    .post-title {
      transition: color 0.3s ease;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    article.blog-card:hover .post-title {
      color: var(--accent-color);
    }

    .readmore {
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
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

    article.blog-card:hover .readmore::before {
      left: 100%;
    }

    .readmore i {
      transition: transform 0.3s ease;
    }

    article.blog-card:hover .readmore i {
      transform: translateX(5px);
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

    .blog-card-animated {
      animation: fadeInUp 0.6s ease-out forwards;
      opacity: 0;
    }

    /* Category Badge */
    .category-badge {
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

    article.blog-card:hover .category-badge {
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
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }

    /* Pagination Enhancement */
    #blog-pagination ul {
      display: flex;
      list-style: none;
      padding: 0;
      margin: 0;
      gap: 10px;
    }

    #blog-pagination ul li {
      margin: 0;
    }

    #blog-pagination ul li a,
    #blog-pagination ul li span {
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

    #blog-pagination ul li a:hover {
      transform: scale(1.1) translateY(-3px);
      background: var(--accent-color);
      color: white;
      box-shadow: 0 4px 16px rgba(46, 163, 89, 0.3);
    }

    #blog-pagination ul li a.active {
      background: var(--accent-color);
      color: white;
      box-shadow: 0 4px 16px rgba(46, 163, 89, 0.3);
    }

    #blog-pagination ul li span {
      background: transparent;
      box-shadow: none;
      color: #999;
    }

    /* Meta Information */
    .meta {
      transition: all 0.3s ease;
    }

    article.blog-card:hover .meta {
      transform: translateY(-5px);
    }

    .meta i {
      transition: transform 0.3s ease;
    }

    article.blog-card:hover .meta i {
      transform: rotate(360deg);
    }

    /* Category Filter Buttons */
    #categoriesContainer .btn {
      transition: all 0.3s ease;
      border-radius: 25px;
      font-weight: 500;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    #categoriesContainer .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(46, 163, 89, 0.2);
    }

    #categoriesContainer .btn-success {
      background: linear-gradient(135deg, var(--accent-color), #2ea359);
      border: none;
    }

    #categoriesContainer .btn-outline-success:hover {
      background: var(--accent-color);
      border-color: var(--accent-color);
      color: white;
    }

    #categoriesContainer .badge {
      font-size: 0.75rem;
      padding: 0.25em 0.5em;
    }
  </style>

</head>

<body class="blog-page">

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
          <li><a href="blog.php" class="active">Blog</a></li>
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
        <h1>Blog</h1>
        <p>Discover our latest stories and insights</p>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Blog</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Categories Filter Section -->
    <section class="py-4" style="background: #f8f9fa;">
      <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <h5 class="mb-0"><i class="bi bi-folder2 me-2"></i>Categories:</h5>
          <div id="categoriesContainer" class="d-flex flex-wrap gap-2">
            <!-- Categories will be loaded here -->
          </div>
        </div>
      </div>
    </section><!-- End Categories Filter Section -->

    <!-- Blog Posts Section -->
    <section id="blog-posts-2" class="blog-posts-2 section">

      <div class="container">
        <div class="row gy-4" id="blogPostsContainer">
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

    </section><!-- /Blog Posts Section -->

    <!-- Blog Pagination Section -->
    <section id="blog-pagination" class="blog-pagination section">
      <div class="container">
        <div class="d-flex justify-content-center">
          <ul id="paginationContainer">
            <!-- Pagination will be dynamically generated -->
          </ul>
        </div>
      </div>
    </section><!-- /Blog Pagination Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section light-background">

      <div class="content">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <h3>Subscribe To Our Newsletter</h3>
              <p class="opacity-50">
                Stay updated with our latest posts and insights!
              </p>
            </div>
            <div class="col-lg-6">
              <form action="forms/newsletter.php" class="form-subscribe php-email-form">
                <div class="form-group d-flex align-items-stretch">
                  <input type="email" name="email" class="form-control h-100" placeholder="Enter your e-mail">
                  <input type="submit" class="btn btn-secondary px-4" value="Subscribe">
                </div>
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">
                  Your subscription request has been sent. Thank you!
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
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 footer-about">
            <a href="index.html" class="logo d-flex align-items-center">
              <span class="sitename">CooFICongo</span>
            </a>
            <div class="footer-contact pt-3">
              <p>Paroisse Notre Dame du Perpétuel Secours, </p>
              <p>Ignié centre PK47 département du Pool Congo Brazzaville</p>
              <p class="mt-3"><strong>Phone:</strong> <span>+242 05 537 6788
                </span></p>
              <p class="mt-3"><strong>Phone:</strong> <span>
                  +242 06 687 3182</span></p>
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
      <div
        class="container d-flex flex-column flex-lg-row justify-content-center justify-content-lg-between align-items-center">

        <div class="d-flex flex-column align-items-center align-items-lg-start">
          <div>
            © Copyright <strong><span>CooFICongo</span></strong>. All Rights Reserved
          </div>
          <div class="credits">
            Designed by <a href="https://freelancesoutions.vercel.app/">FREELANCE SOLUTIONS</a> Distributed by <a
              href="https://freelancesoutions.vercel.app/">FS</a>
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

  <!-- Blog Dynamic Loading Script -->
  <script>
    // Configuration
    const postsPerPage = 6;
    let currentPage = 1;
    let totalPosts = 0;
    let allPosts = [];
    let allCategories = [];
    let currentCategory = null;

    // Load blog posts on page load
    document.addEventListener('DOMContentLoaded', function() {
      loadBlogPosts();
    });

    // Fetch blog posts from PHP backend
    function loadBlogPosts() {
      fetch('php/blogdisplay.php?json=true')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.posts) {
            allPosts = data.posts;
            allCategories = data.categories || [];
            totalPosts = data.count;
            displayCategories();
            displayPosts(currentPage);
            setupPagination();
          } else {
            showEmptyState();
          }
        })
        .catch(error => {
          console.error('Error loading blog posts:', error);
          showErrorState();
        });
    }

    // Display categories filter
    function displayCategories() {
      const container = document.getElementById('categoriesContainer');
      if (!container) return;

      container.innerHTML = '';

      // Add "All" category
      const allBtn = document.createElement('button');
      allBtn.className = `btn btn-sm ${currentCategory === null ? 'btn-success' : 'btn-outline-success'}`;
      allBtn.innerHTML = '<i class="bi bi-grid-3x3-gap me-1"></i>All';
      allBtn.onclick = () => filterByCategory(null);
      container.appendChild(allBtn);

      // Add dynamic categories
      allCategories.forEach(cat => {
        const btn = document.createElement('button');
        btn.className = `btn btn-sm ${currentCategory === cat.category ? 'btn-success' : 'btn-outline-success'}`;
        btn.innerHTML = `${escapeHtml(cat.category)} <span class="badge bg-light text-dark ms-1">${cat.count}</span>`;
        btn.onclick = () => filterByCategory(cat.category);
        container.appendChild(btn);
      });
    }

    // Filter posts by category
    function filterByCategory(category) {
      currentCategory = category;
      currentPage = 1;
      displayCategories();
      displayPosts(currentPage);
      setupPagination();
      scrollToTop();
    }

    // Display posts for current page
    function displayPosts(page) {
      const container = document.getElementById('blogPostsContainer');
      
      // Filter posts by category if selected
      let filteredPosts = currentCategory 
        ? allPosts.filter(post => post.category === currentCategory)
        : allPosts;
      
      totalPosts = filteredPosts.length;
      
      const startIndex = (page - 1) * postsPerPage;
      const endIndex = startIndex + postsPerPage;
      const postsToShow = filteredPosts.slice(startIndex, endIndex);

      if (postsToShow.length === 0) {
        showEmptyState();
        return;
      }

      // Clear skeleton loaders and existing content
      container.innerHTML = '';

      // Create and display blog post cards
      postsToShow.forEach((post, index) => {
        const postCard = createBlogCard(post, index);
        container.appendChild(postCard);
      });
    }

    // Create blog post card element
    function createBlogCard(post, index) {
      const col = document.createElement('div');
      col.className = 'col-lg-4 blog-card-animated';
      col.style.animationDelay = `${index * 0.1}s`;

      // Format date
      const date = new Date(post.created_at);
      const day = date.getDate();
      const month = date.toLocaleString('default', { month: 'long' });

      // Truncate body text for preview
      const bodyPreview = post.body.length > 120 ? post.body.substring(0, 120) + '...' : post.body;

      col.innerHTML = `
        <article class="blog-card position-relative h-100">
          <div class="post-img position-relative overflow-hidden">
            <img src="${escapeHtml(post.image)}" class="img-fluid" alt="${escapeHtml(post.title)}" 
                 onerror="this.src='assets/img/blog/blog-1.jpg'">
          </div>

          <div class="meta d-flex align-items-end">
            <span class="post-date"><span>${day}</span>${month}</span>
            <div class="d-flex align-items-center">
              <i class="bi bi-person"></i> <span class="ps-2">Author</span>
            </div>
            <span class="px-3 text-black-50">/</span>
            <div class="d-flex align-items-center">
              <i class="bi bi-folder2"></i> <span class="ps-2">${escapeHtml(post.category)}</span>
            </div>
          </div>

          <div class="post-content d-flex flex-column">
            <h3 class="post-title">${escapeHtml(post.title)}</h3>
            <p class="text-muted small">${escapeHtml(bodyPreview)}</p>
            <a href="blog-details.php?id=${escapeHtml(post.uuid)}" class="readmore stretched-link">
              <span>Read More</span><i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </article>
      `;

      return col;
    }

    // Setup pagination controls
    function setupPagination() {
      const paginationSection = document.getElementById('blog-pagination');
      const paginationContainer = document.getElementById('paginationContainer');
      const totalPages = Math.ceil(totalPosts / postsPerPage);
      
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
          displayPosts(currentPage);
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
            displayPosts(currentPage);
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
          displayPosts(currentPage);
          setupPagination();
          scrollToTop();
        });
      }
      paginationContainer.appendChild(nextLi);
    }

    // Show empty state when no posts
    function showEmptyState() {
      const container = document.getElementById('blogPostsContainer');
      container.innerHTML = `
        <div class="col-12 empty-state">
          <i class="bi bi-inbox"></i>
          <h3>No Blog Posts Yet</h3>
          <p class="text-muted">Check back soon for exciting new content!</p>
        </div>
      `;
      document.getElementById('blog-pagination').style.display = 'none';
    }

    // Show error state
    function showErrorState() {
      const container = document.getElementById('blogPostsContainer');
      container.innerHTML = `
        <div class="col-12 empty-state">
          <i class="bi bi-exclamation-triangle text-warning"></i>
          <h3>Oops! Something went wrong</h3>
          <p class="text-muted">We couldn't load the blog posts. Please try again later.</p>
          <button class="btn btn-primary mt-3" onclick="loadBlogPosts()">
            <i class="bi bi-arrow-clockwise me-2"></i>Retry
          </button>
        </div>
      `;
      document.getElementById('blog-pagination').style.display = 'none';
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

    // Scroll to top of blog section
    function scrollToTop() {
      document.getElementById('blog-posts-2').scrollIntoView({ behavior: 'smooth' });
    }
  </script>

</body>

</html>
