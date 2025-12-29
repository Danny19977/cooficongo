# CooFICongo Project - Complete Analysis

## 📋 Project Overview
**CooFICongo** is a comprehensive web-based content management and donation platform for a non-profit organization focused on manioc (cassava) and chikwangue production and education in Congo.

**Technology Stack:**
- **Backend:** PHP 7+ with MySQLi
- **Frontend:** HTML5, Bootstrap 5.3.3, JavaScript
- **Database:** MySQL/MariaDB
- **CSS Framework:** Bootstrap 5 with custom SCSS
- **Libraries:** AOS (Animate on Scroll), GLightbox, Swiper, Bootstrap Icons

**Database Name:** `cooficongo`
**Server:** localhost (XAMPP)
**Credentials:** root (no password)

---

## 🏗️ Project Structure

### Root Level Files (Entry Points)
```
index.html          → Landing/home page with hero section
dashboard.php       → Authenticated user dashboard (protected)
Login.html          → Login form
reg.html            → Registration form
about.html          → About page
services.html       → Services page
testimonials.html   → Testimonials section
contact.html        → Contact form
blog.html           → Blog listing page
blog-details.html   → Single blog post view
blogpost.php        → Blog post creation interface
blog-details.php    → Backend for blog post details
blog.php            → Blog post display handler
event-details.php   → Single event details page
event.php           → Events display handler
eventpost.php       → Event creation interface
activity.php        → Activities display handler
gallery.html        → Gallery page
gallerypost.php     → Gallery upload interface
donation.html       → Donation page
profile.html        → User profile page
contactview.php     → Contact messages management
newsletters.php     → Newsletter management
```

### Directory Structure

#### `/php/` - Backend Logic
**Core Files:**
- `connection.php` - Database connection (MySQLi)
- `auth_check.php` - Session & Remember Me token verification
- `login.php` - User authentication handler
- `reg.php` - User registration handler
- `logout.php` - Session termination

**Blog Management:**
- `addpost.php` - Create new blog posts with multiple images
- `editpost.php` - Edit existing blog posts
- `deletepost.php` - Delete blog posts
- `blogdisplay.php` - Fetch and display blog posts with stats

**Events/Activities Management:**
- `addevent.php` - Create events with multiple images
- `editevent.php` - Edit events
- `deleteevent.php` - Delete events
- `activitiesdisplay.php` - Display events with stats
- `geteventstats.php` - Event statistics (JSON endpoint)

**Gallery Management:**
- `addgallery.php` - Upload gallery items (images & videos)
- `editgallery.php` - Edit gallery items
- `deletegallery.php` - Delete gallery items
- `gallerydisplay.php` - Fetch gallery items with stats

**Donations:**
- `donationsdisplay.php` - View and manage donations
- `process_visa_payment.php` - Credit card payment processing & validation
- `create_donations_table.sql` - Donation table schema

**User Management:**
- `profile.php` - User profile handler
- `updateprofile.php` - Update user information
- `changepassword.php` - Password change handler
- `create_remember_tokens_table.sql` - Remember Me tokens table

**Communications:**
- `submitcontact.php` - Handle contact form submissions
- `contactdisplay.php` - Display contact messages
- `deletecontact.php` - Delete contact messages
- `newsletter.php` - Newsletter subscription management

**Utilities:**
- `getstats.php` - Blog statistics (JSON endpoint)
- `test_system.php` - System testing utilities

#### `/forms/` - Form Handlers
- `contact.php` - Contact form submission
- `newsletter.php` - Newsletter signup

#### `/assets/` - Static Resources

**CSS Files:**
- `css/main.css` - Main stylesheet
- `css/donation.css` - Donation page styles
- `css/bootstrap/` - Bootstrap framework files

**JavaScript:**
- `js/main.js` - Main application logic (370 lines)
  - Loader/splash screen management
  - Scroll animations & observers
  - Stagger animations
  - Various UI utilities
- `js/donation.js` - Donation form handling

**Images (`img/`):**
- `blog/` - Blog post images
- `gallery/` - Gallery images
- `profiles/` - User profile pictures
- `team/` - Team member photos
- `testimonials/` - Testimonial images
- Hero images, logo files, product images

**Vendor Libraries:**
- `vendor/bootstrap/` - Bootstrap CSS & JS (5.3.3)
- `vendor/bootstrap-icons/` - Icon library
- `vendor/aos/` - Animate on Scroll library
- `vendor/swiper/` - Image carousel library
- `vendor/glightbox/` - Lightbox image viewer
- `vendor/php-email-form/` - Email validation

**SCSS:**
- `scss/` - Source SCSS files (compiled to CSS)

---

## 🗄️ Database Schema

### Tables Created Automatically

#### `users` Table
```sql
├── uuid (VARCHAR 36, PRIMARY KEY)
├── username (VARCHAR 150, UNIQUE)
├── role (VARCHAR 50)
├── phone (VARCHAR 50, UNIQUE)
├── email (VARCHAR 150, UNIQUE)
├── password (VARCHAR 255, hashed)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

#### `remember_tokens` Table
```sql
├── user_uuid (FK → users.uuid)
├── token_hash (VARCHAR 255)
├── expires_at (TIMESTAMP)
└── created_at (TIMESTAMP)
```

#### `blogposts` Table
```sql
├── uuid (VARCHAR 36, PRIMARY KEY)
├── user_uuid (FK → users.uuid)
├── title (VARCHAR 255)
├── category (VARCHAR 100)
├── image (VARCHAR 255)
├── image_1, image_2, image_3 (VARCHAR 255)
├── body (LONGTEXT)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

#### `activitiespost` Table
```sql
├── uuid (VARCHAR 36, PRIMARY KEY)
├── user_uuid (FK → users.uuid)
├── title (VARCHAR 255)
├── image, image_2, image_3, image_4, image_5
├── summary (TEXT)
├── description (LONGTEXT)
├── activity_date (DATE)
├── location (VARCHAR 255)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

#### `gallery` Table
```sql
├── uuid (VARCHAR 36, PRIMARY KEY)
├── user_uuid (FK → users.uuid)
├── title (VARCHAR 255)
├── image_1 to image_10 (VARCHAR 255, for images)
├── video_1 to video_10 (VARCHAR 255, for video URLs)
├── description (LONGTEXT)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

#### `donations` Table
```sql
├── id (INT AUTO_INCREMENT PRIMARY KEY)
├── transaction_id (VARCHAR 50, UNIQUE)
├── donor_name (VARCHAR 255)
├── email (VARCHAR 255)
├── phone (VARCHAR 50)
├── amount (DECIMAL 10,2)
├── payment_method (VARCHAR 50)
├── card_type (VARCHAR 50)
├── masked_card_number (VARCHAR 50)
├── status (VARCHAR 20) [pending, completed, failed]
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
Indexes: transaction_id, email, status, payment_method, created_at
```

#### `newsletter_emails` Table (Auto-created)
```sql
├── id (INT AUTO_INCREMENT PRIMARY KEY)
├── email (VARCHAR 255, UNIQUE)
├── subscribed_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

#### `contacts` Table (Auto-created in submitcontact.php)
```sql
├── uuid (VARCHAR 36, PRIMARY KEY)
├── name (VARCHAR 255)
├── email (VARCHAR 255)
├── phone (VARCHAR 20)
├── subject (VARCHAR 255)
├── message (LONGTEXT)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

---

## 🔐 Authentication & Authorization

### Login System
- **Method:** Session-based with optional "Remember Me"
- **Location:** `php/login.php`
- **Identifier:** Email OR phone number
- **Password Hashing:** `password_hash()` with fallback for plain text
- **Session Variables:** 
  - `user_uuid` - Unique user identifier
  - `username` - User's display name
  - `role` - User role (default: "User")

### Remember Me Token
- **Duration:** 30 days
- **Storage:** `remember_tokens` table with hashed tokens
- **Verification:** Done on page load if session not active
- **Fallback:** Auth check in `auth_check.php` at top of protected pages

### Protected Pages
- `dashboard.php` - Main user dashboard (requires login)
- `blogpost.php` - Blog creation
- `eventpost.php` - Event creation
- `gallerypost.php` - Gallery management
- All edit/delete operations

---

## 📝 Core Features

### 1. **Blog Management System**
**File:** `addpost.php`, `blogdisplay.php`, `editpost.php`, `deletepost.php`

**Features:**
- Create blog posts with title, category, content, and multiple images
- Support for 4 images per post (main + 3 additional)
- Image validation: JPEG, PNG, GIF, WebP (max 5MB)
- Edit and delete existing posts
- Display posts with categories
- Statistics: total posts, views, drafts, published count

**Image Upload:**
- Unique filename generation: `blog_[timestamp].ext`
- Destination: `/assets/img/blog/`

### 2. **Events/Activities Management**
**File:** `addevent.php`, `activitiesdisplay.php`, `editevent.php`, `deleteevent.php`

**Features:**
- Create events with title, summary, description, date, location
- Support for 5 images per event
- Event date filtering (upcoming vs past)
- Location-based grouping
- Event statistics

**Image Upload:**
- Unique filename generation: `event_[timestamp].ext`
- Destination: `/assets/img/events/`

### 3. **Gallery System**
**File:** `addgallery.php`, `gallerydisplay.php`, `editgallery.php`, `deletegallery.php`

**Features:**
- Upload up to 10 images and 10 video URLs
- Media type statistics (total images, videos)
- Gallery item descriptions
- JSON API for AJAX requests

**Image Upload:**
- Destination: `/assets/img/gallery/`

### 4. **Donation System**
**File:** `process_visa_payment.php`, `donationsdisplay.php`

**Features:**
- Credit card payment processing (Visa, Mastercard, AmEx, Discover)
- Donation tracking with status (pending, completed, failed)
- Donor information collection
- Payment method logging
- Statistics dashboard

**Validation:**
- Luhn algorithm for card number validation
- Expiry date validation
- Card type detection based on BIN
- Amount validation

**Data Stored:**
- Donor name, email, phone
- Amount, payment method
- Masked card number, card type
- Transaction ID (unique), status, timestamps

### 5. **User Management**
**File:** `reg.php`, `login.php`, `profile.php`, `updateprofile.php`, `logout.php`

**Features:**
- User registration with email & phone uniqueness
- Login with email/phone and password
- Profile viewing and editing
- Password change functionality
- Remember Me functionality (30 days)

**User Data:**
- UUID (unique identifier)
- Username, email, phone
- Role assignment
- Timestamps

### 6. **Newsletter System**
**File:** `newsletter.php`, `newsletters.php`

**Features:**
- Email subscription management
- Newsletter statistics
- Email listing and deletion
- Auto-creates `newsletter_emails` table

**Functions:**
- `createNewsletterTable()` - Schema creation
- `saveNewsletterEmail()` - Add subscriber
- `getAllNewsletterEmails()` - Retrieve subscribers
- `deleteNewsletterEmail()` - Remove subscriber
- `getNewsletterStats()` - Statistics

### 7. **Contact Form System**
**File:** `submitcontact.php`, `contactdisplay.php`, `deletecontact.php`

**Features:**
- Contact form submissions with name, email, phone, subject, message
- Contact message viewing and deletion
- Email validation
- UUID generation for tracking

---

## 📊 Statistics & Analytics

### Available Endpoints

**Blog Stats:**
- `php/getstats.php` - Returns JSON with:
  - Total posts, views, drafts, published
  - Today's posts, this week, this month

**Event Stats:**
- `php/geteventstats.php` - Returns JSON with:
  - Total events, upcoming, past events

**Donation Stats:**
- Calculated in `donationsdisplay.php`:
  - Total donations, amount, completed, pending
  - Breakdown by payment method

**Gallery Stats:**
- Calculated in `gallerydisplay.php`:
  - Total galleries, images, videos

---

## 🎨 Frontend Pages

### Public Pages
- **index.html** - Home/landing page with:
  - Hero section with carousel
  - Service showcase
  - Team section
  - Testimonials
  - Contact section
  - Newsletter signup
  
- **about.html** - Organization information
- **services.html** - Service descriptions
- **testimonials.html** - Customer testimonials
- **contact.html** - Contact form
- **blog.html** - Blog listing
- **blog-details.html** - Single blog post
- **event.php** - Events listing
- **event-details.php** - Single event details
- **gallery.html** - Gallery view
- **donation.html** - Donation page with:
  - Donation form
  - Card payment integration
  - Amount selection

### Authenticated Pages
- **dashboard.php** - User dashboard with sidebar navigation
- **blogpost.php** - Blog creation interface
- **eventpost.php** - Event creation interface
- **gallerypost.php** - Gallery upload interface
- **profile.html** - User profile view
- **contactview.php** - Contact messages management
- **newsletters.php** - Newsletter management

---

## 🔧 Key Functions & Utilities

### Authentication (`auth_check.php`)
```php
- Session validation
- Remember Me token verification
- Auto-login from token
- Redirect to login if unauthorized
```

### File Upload Utilities
```php
uploadImage()           - Blog image upload
uploadEventImage()      - Event image upload
uploadGalleryImage()    - Gallery image upload
```

### UUID Generation (`reg.php`, `submitcontact.php`)
```php
uuidv4()               - RFC 4122 compliant UUID generation
generateUUID()         - Alternative UUID generation
```

### Donation Validation (`process_visa_payment.php`)
```php
validate_card_number()      - Luhn algorithm validation
validate_expiry_date()      - Expiry date validation
get_card_type()            - Detect card type from BIN
sanitize_input()           - Input sanitization
```

### Statistics Functions
```php
get_donations()           - Fetch donations with filters
get_donation_stats()      - Calculate donation metrics
getNewsletterStats()      - Newsletter metrics
```

---

## 🎭 Frontend Features

### JavaScript Features (`main.js`)
- **Loader Screen:** Splash screen with logo (1.5s duration)
- **Scroll Animations:** AOS library integration
- **Stagger Animations:** Timed element animations
- **Animation Classes:**
  - `.animate-on-scroll` - Trigger on viewport entry
  - `.stagger-animation` - Sequential animations
  - `.fade-in-down`, `.fade-in-up`, etc. - Animation directions

### CSS Features
- **Bootstrap 5.3.3** - Responsive grid system
- **Custom Styling:** 
  - Green gradient backgrounds (env-friendly theme)
  - Glass-morphism effects (sidebar, headers)
  - Smooth transitions and animations
  - Mobile-responsive design

### Loading States
- Skeleton loaders for async content
- Animated gradient backgrounds
- Smooth transitions between states

---

## 🔌 API Endpoints (JSON)

| Endpoint | Method | Response | Purpose |
|----------|--------|----------|---------|
| `php/getstats.php` | GET | JSON | Blog statistics |
| `php/geteventstats.php` | GET | JSON | Event statistics |
| `php/blogdisplay.php?json=true` | GET | JSON | Blog posts data |
| `php/activitiesdisplay.php?json=true` | GET | JSON | Events data |
| `php/gallerydisplay.php?format=json` | GET | JSON | Gallery items |
| `php/process_visa_payment.php` | POST | JSON | Payment processing |
| `php/newsletter.php` | POST | JSON | Newsletter subscription |

---

## 📱 Responsive Design

- **Mobile First:** Bootstrap grid system
- **Breakpoints:** Bootstrap default (xs, sm, md, lg, xl)
- **Sidebar:** Hidden on mobile, visible on md+ screens
- **Navigation:** Responsive menu with hamburger support
- **Images:** Responsive with max-width and auto height

---

## 🚀 Deployment Considerations

### Required Files
- All PHP files must be in web root
- Database must be created and tables initialized
- Image directories must be writable (`/assets/img/blog/`, etc.)
- Sessions directory must be writable

### Security Measures Implemented
- Password hashing with `password_hash()`
- Prepared statements (MySQLi)
- Session regeneration after login
- HTTPONLY cookies for Remember Me tokens
- Input sanitization functions
- UUID for user identification

### Performance Optimizations
- Image lazy loading with AOS
- Skeleton loaders for async content
- Indexed database fields (common queries)
- JSON endpoints for AJAX requests
- Minified vendor libraries

---

## 📝 Common Workflows

### Adding a Blog Post
1. User logs in via `Login.html`
2. Navigates to dashboard → "Blog Post"
3. Fills form in `blogpost.php`
4. Submits to `addpost.php`
5. Images uploaded to `/assets/img/blog/`
6. Post stored in `blogposts` table
7. Visible on `blog.html` via `blogdisplay.php`

### Creating an Event
1. User logs in
2. Dashboard → "Events"
3. Fills form in `eventpost.php`
4. Submits to `addevent.php`
5. Images uploaded to `/assets/img/events/`
6. Event stored in `activitiespost` table
7. Visible on `event.php` via `activitiesdisplay.php`

### Processing Donations
1. User visits `donation.html`
2. Enters donation amount
3. Submits card details
4. Processed by `process_visa_payment.php`
5. Card validated (Luhn + expiry)
6. Transaction stored in `donations` table
7. Status tracked (pending → completed)

### Newsletter Signup
1. User enters email on any page
2. Submits to `newsletter.php`
3. Email stored in `newsletter_emails` table
4. Managed via `newsletters.php` (admin)

---

## 🐛 Known/Potential Issues

1. **Password Storage:** Fallback to plain-text comparison for legacy passwords (should be phased out)
2. **Image Directories:** Need write permissions on creation
3. **Session Management:** No CSRF tokens implemented in forms
4. **SQL Injection:** Some dynamic queries use string concatenation (e.g., `geteventstats.php`) - should use prepared statements
5. **Error Handling:** Limited validation error feedback on some forms
6. **Database Connection:** No connection pooling or reconnection logic
7. **Donation Processing:** No real payment gateway integration (processing is mock)

---

## 🔄 Development Workflow

### Adding New Features
1. Create HTML form in root directory
2. Create PHP handler in `/php/`
3. Add table schema if needed
4. Implement CRUD operations
5. Add JSON endpoint if needed
6. Integrate authentication checks

### Database Modifications
1. Update schema in PHP auto-creation code
2. Or use SQL files in `/php/`
3. Run migrations on server

### Styling New Pages
1. Use Bootstrap classes
2. Extend with CSS in `/assets/css/`
3. Add animations to elements with `.animate-on-scroll`

---

## 📦 Dependencies Summary

| Library | Version | Purpose |
|---------|---------|---------|
| Bootstrap | 5.3.3 | UI Framework |
| AOS | Latest | Scroll Animations |
| Swiper | Latest | Image Carousel |
| GLightbox | Latest | Image Lightbox |
| Bootstrap Icons | Latest | Icon Library |

---

## 🎯 Summary

**CooFICongo** is a fully-featured content management and donation platform with:
- ✅ User authentication with Remember Me
- ✅ Blog post management with multiple images
- ✅ Event/activity management with date filtering
- ✅ Gallery with image/video support
- ✅ Donation system with card validation
- ✅ Newsletter subscription
- ✅ Contact form management
- ✅ Responsive design
- ✅ Statistics and analytics
- ✅ JSON API endpoints
- ✅ Session-based authorization

Perfect for a non-profit focused on education and product promotion in Congo!

