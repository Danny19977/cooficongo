/**
* Template Name: AgriCulture
* Template URL: https://bootstrapmade.com/agriculture-bootstrap-website-template/
* Updated: Aug 07 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  /**
   * Loading Page Management
   */
  function initLoader() {
    // Create loader if it doesn't exist
    if (!document.querySelector('.loader-container')) {
      const loader = document.createElement('div');
      loader.className = 'loader-container';
      loader.innerHTML = `
        <div class="loader-logo">
          <img src="assets/img/logo2.png" alt="CooFICongo Loading">
        </div>
        <div class="loader-message">Bienvenue chez CooFICongo</div>
        <div class="loader-submessage">Votre source de manioc et chikwangue de qualité</div>
        <div class="loading-spinner"></div>
      `;
      document.body.appendChild(loader);
    }

    // Hide loader after page load
    window.addEventListener('load', () => {
      setTimeout(() => {
        const loader = document.querySelector('.loader-container');
        if (loader) {
          loader.classList.add('fade-out');
          setTimeout(() => {
            loader.remove();
          }, 600);
        }
      }, 1500); // Show loader for at least 1.5 seconds
    });
  }

  /**
   * Enhanced Scroll Animations
   */
  function initScrollAnimations() {
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    });

    animateElements.forEach(el => observer.observe(el));
  }

  /**
   * Stagger Animation Observer
   */
  function initStaggerAnimations() {
    const staggerElements = document.querySelectorAll('.stagger-animation');
    
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate');
        }
      });
    }, {
      threshold: 0.2
    });

    staggerElements.forEach(el => observer.observe(el));
  }

  /**
   * Typing Animation
   */
  function initTypingAnimation() {
    const typingElements = document.querySelectorAll('.typing-animation');
    
    typingElements.forEach(element => {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.borderRight = '2px solid var(--accent-color)';
            observer.unobserve(entry.target);
          }
        });
      });
      
      observer.observe(element);
    });
  }

  /**
   * Parallax Effect for Hero Images
   */
  function initParallax() {
    const parallaxElements = document.querySelectorAll('.parallax-element');
    
    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;
      
      parallaxElements.forEach(element => {
        const rate = scrolled * -0.5;
        element.style.transform = `translateY(${rate}px)`;
      });
    });
  }

  /**
   * Page Transition Effects
   */
  function initPageTransitions() {
    // Add fade-in effect to body on page load
    document.body.style.opacity = '0';
    window.addEventListener('load', () => {
      setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease';
        document.body.style.opacity = '1';
      }, 100);
    });
    
    // Smooth page transitions for internal links
    document.querySelectorAll('a[href^="#"], a[href*=".html"]').forEach(link => {
      link.addEventListener('click', function(e) {
        if (this.getAttribute('href').startsWith('#')) return; // Skip anchor links
        
        e.preventDefault();
        const targetUrl = this.getAttribute('href');
        
        document.body.style.transition = 'opacity 0.3s ease';
        document.body.style.opacity = '0';
        
        setTimeout(() => {
          window.location.href = targetUrl;
        }, 300);
      });
    });
  }

  /**
   * Enhanced Service Item Interactions
   */
  function initServiceInteractions() {
    const serviceItems = document.querySelectorAll('.service-item');
    
    serviceItems.forEach(item => {
      item.addEventListener('mouseenter', () => {
        const icon = item.querySelector('.service-item-icon svg, .service-item-icon i');
        if (icon) {
          icon.style.transition = 'all 0.3s ease';
          icon.style.transform = 'scale(1.2) rotateY(20deg)';
        }
      });
      
      item.addEventListener('mouseleave', () => {
        const icon = item.querySelector('.service-item-icon svg, .service-item-icon i');
        if (icon) {
          icon.style.transform = 'scale(1) rotateY(0deg)';
        }
      });
    });
  }

  /**
   * Testimonial Cards Animation
   */
  function initTestimonialAnimations() {
    const testimonials = document.querySelectorAll('.testimonial');
    
    testimonials.forEach((testimonial, index) => {
      testimonial.style.opacity = '0';
      testimonial.style.transform = 'translateY(30px)';
      
      setTimeout(() => {
        testimonial.style.transition = 'all 0.6s ease';
        testimonial.style.opacity = '1';
        testimonial.style.transform = 'translateY(0)';
      }, 200 * (index + 1));
    });
  }

  // Initialize all custom functions
  initLoader();
  initScrollAnimations();
  initStaggerAnimations();
  initTypingAnimation();
  initParallax();
  initPageTransitions();
  initServiceInteractions();
  
  // Initialize testimonial animations when DOM is loaded
  document.addEventListener('DOMContentLoaded', initTestimonialAnimations);

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader) return;
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Scroll up sticky header to headers with .scroll-up-sticky class
   */
  let lastScrollTop = 0;
  window.addEventListener('scroll', function() {
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky')) return;

    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    if (scrollTop > lastScrollTop && scrollTop > selectHeader.offsetHeight) {
      selectHeader.style.setProperty('position', 'sticky', 'important');
      selectHeader.style.top = `-${header.offsetHeight + 50}px`;
    } else if (scrollTop > selectHeader.offsetHeight) {
      selectHeader.style.setProperty('position', 'sticky', 'important');
      selectHeader.style.top = "0";
    } else {
      selectHeader.style.removeProperty('top');
      selectHeader.style.removeProperty('position');
    }
    lastScrollTop = scrollTop;
  });

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  if (mobileNavToggleBtn) {
    function mobileNavToogle() {
      document.querySelector('body').classList.toggle('mobile-nav-active');
      mobileNavToggleBtn.classList.toggle('bi-list');
      mobileNavToggleBtn.classList.toggle('bi-x');
    }
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
  }

  /**
   * Hide mobile nav on same-page/hash links
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  
  if (scrollTop) {
    scrollTop.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 600,
        easing: 'ease-in-out',
        once: true,
        mirror: false
      });
    }
  }
  window.addEventListener('load', aosInit);

  /**
   * Auto generate the carousel indicators
   */
  document.querySelectorAll('.carousel-indicators').forEach((carouselIndicator) => {
    carouselIndicator.closest('.carousel').querySelectorAll('.carousel-item').forEach((carouselItem, index) => {
      if (index === 0) {
        carouselIndicator.innerHTML += `<li data-bs-target="#${carouselIndicator.closest('.carousel').id}" data-bs-slide-to="${index}" class="active"></li>`;
      } else {
        carouselIndicator.innerHTML += `<li data-bs-target="#${carouselIndicator.closest('.carousel').id}" data-bs-slide-to="${index}"></li>`;
      }
    });
  });

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Initiate glightbox
   */
  if (typeof GLightbox !== 'undefined') {
    const glightbox = GLightbox({
      selector: '.glightbox'
    });
  }

})();