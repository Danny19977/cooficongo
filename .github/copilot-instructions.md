# CooFICongo AI Coding Instructions

## Project Overview
**CooFICongo** is a PHP/MySQL content management and donation platform for a non-profit organization focused on manioc and chikwangue production in Congo. The architecture separates concerns into frontend HTML/JS and backend PHP handlers.

**Tech Stack:** PHP 7+, MySQL/MariaDB, Bootstrap 5.3.3, Vanilla JavaScript, Ajax (no frameworks)

**Key Database:** `cooficongo` on localhost (root, no password via XAMPP)

---

## Architecture & Data Flow

### Entry Point Pattern
- **Public pages** (HTML) live in root: `index.html`, `about.html`, `blog.html`, etc.
- **Dynamic pages** (PHP) in root handle display: `blog.php`, `event.php`, `dashboard.php`, etc.
- **Backend logic** in `/php/` folder handles CRUD operations and business logic
- **Forms** submit to `/php/` handlers which redirect back with error/success params

**Example Flow:** User visits `blog.html` → displays via `blog.php` (PHP handler) → fetches data from `php/blogdisplay.php` or calls JSON API

### Database Connection Pattern
- All PHP files include `php/connection.php` for MySQLi connection
- UUID (v4) used as PRIMARY KEY for users, blogposts, activitiespost, gallery, contacts
- Auto-increment IDs only used for donations and newsletter
- Timestamps (`created_at`, `updated_at`) on all main tables

### Authentication Model
- **Session-based** with optional "Remember Me" (30-day token)
- Protected pages include `php/auth_check.php` at top to verify session or restore from remember token
- Session variables: `user_uuid`, `username`, `role`
- Users identified by email OR phone (both unique)
- Passwords use `password_hash()` with fallback for legacy plain-text (phase out fallback when migrating)

---

## Key Components & Patterns

### 1. **Blog Management** (`/php/addpost.php`, `blogdisplay.php`, `editpost.php`, `deletepost.php`)
- **Image Upload Pattern:** Unique filenames via `uniqid('blog_', true)`, destination `/assets/img/blog/`
- **Validation:** Title, category, body required; images JPEG/PNG/GIF/WebP max 5MB; main image mandatory, 3 optional
- **Table:** `blogposts` (uuid, user_uuid FK, title, category, image, image_1-3, body, timestamps)
- **Stats Endpoint:** `php/getstats.php` returns JSON with total/today/week/month post counts

### 2. **Events/Activities** (`/php/addevent.php`, `activitiesdisplay.php`, `editevent.php`, `deleteevent.php`)
- **Table:** `activitiespost` (uuid, user_uuid FK, title, image[2-5], summary, description, activity_date, location, timestamps)
- **Upload Path:** `/assets/img/events/` with same uniqid pattern
- **Stats Endpoint:** `php/geteventstats.php` returns JSON with total/upcoming/past event counts
- **Features:** Location-based grouping, date filtering for upcoming/past

### 3. **Gallery System** (`/php/addgallery.php`, `gallerydisplay.php`, `editgallery.php`, `deletegallery.php`)
- **Dual Media:** Supports up to 10 image fields AND 10 video URL fields in single record
- **Table:** `gallery` (uuid, user_uuid FK, title, image_1-10, video_1-10, description, timestamps)
- **Upload Path:** `/assets/img/gallery/`
- **Stats:** Total galleries, image count, video count

### 4. **Donation Processing** (`/php/process_visa_payment.php`)
- **Validation:** Luhn algorithm for card numbers, expiry date check, card type detection by BIN
- **Table:** `donations` (id, transaction_id UNIQUE, donor_name, email, phone, amount DECIMAL, payment_method, card_type, masked_card_number, status [pending/completed/failed], timestamps)
- **Critical:** Currently mock payment (no real gateway) - status logic is in `process_visa_payment.php`
- **API Response:** JSON with success flag, transaction_id, status
- **Masking:** Store masked card (last 4 digits), never store full number

### 5. **User Management** (`/php/reg.php`, `login.php`, `profile.php`, `updateprofile.php`, `changepassword.php`)
- **Registration:** Email & phone must be unique, username required
- **Login:** Accepts email OR phone + password, creates session + optional remember token
- **Table:** `users` (uuid PK, username UNIQUE, email UNIQUE, phone UNIQUE, password hashed, role, timestamps)
- **Remember Me Table:** `remember_tokens` (user_uuid FK, token_hash, expires_at, created_at)
- **Pattern:** Session regenerated after login, token hashing via `password_hash()` for security

### 6. **Newsletter** (`/php/newsletter.php`, `newsletters.php`)
- **Auto-creates table** `newsletter_emails` on first subscription (id, email UNIQUE, subscribed_at, updated_at)
- **Workflow:** Anonymous signup on frontend → post to `newsletter.php` → JSON response
- **Management:** Admin view in `newsletters.php` with delete capability

### 7. **Contact Form** (`/php/submitcontact.php`, `contactdisplay.php`, `deletecontact.php`)
- **Table:** `contacts` (uuid PK, name, email, phone, subject, message, timestamps) - auto-created if missing
- **Submission:** Form in `contact.html` → `submitcontact.php` → stored in DB

---

## Code Patterns & Conventions

### File Upload Pattern
```php
function uploadImage($file, $upload_dir, $allowed_types, $max_size) {
    if (!isset($file) || $file['error'] != 0) return null; // Not provided
    if (!in_array($file['type'], $allowed_types)) return false; // Validation failed
    if ($file['size'] > $max_size) return false; // Too large
    
    $unique_filename = uniqid('prefix_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $target_file = $upload_dir . $unique_filename;
    
    return move_uploaded_file($file['tmp_name'], $target_file) 
        ? 'assets/img/[category]/' . $unique_filename 
        : false;
}
```

### Error Handling Pattern
- **Form Validation Errors:** Redirect with query param `?error=error_type` (e.g., `?error=empty_fields`, `?error=upload_failed`)
- **Success:** Redirect with `?success=true` or message parameter
- **JSON Endpoints:** Always return `{'success': boolean, ...}` structure

### Database Query Pattern
- **Prepared Statements:** Used in authentication, avoid string concatenation for user input
- **Known Issue:** Some endpoints like `geteventstats.php` use string concatenation for dynamic table names - acceptable for internal control, but avoid for user input
- **Connection Closure:** `$conn->close()` at end of script (though persistent connections would be better)

### UUID Generation
- Custom `uuidv4()` or `generateUUID()` function (RFC 4122 compliant)
- Used for users, blog posts, events, gallery, contacts
- Primary key and foreign keys reference UUID strings (VARCHAR 36)

### JSON API Pattern
```php
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'count' => count($items),
    'items' => $items,
    'stats' => $stats
]);
exit();
```

---

## Frontend Patterns

### Bootstrap & Responsive Design
- Uses Bootstrap 5.3.3 grid system with default breakpoints (xs, sm, md, lg, xl)
- Mobile-first approach: hidden sidebar on mobile, visible on md+
- Glass-morphism styling for headers/modals with green gradient (environmental theme)

### JavaScript Animation Patterns (`/assets/js/main.js`)
- **Loader:** 1.5s splash screen with logo on page load
- **Scroll Animations:** Elements with `.animate-on-scroll` trigger on viewport entry via Intersection Observer
- **Stagger Animation:** Sequential animations on grouped elements
- **Classes:** `.fade-in-down`, `.fade-in-up`, `.slide-left`, etc. for animation directions
- **AOS Library Integration:** Add `data-aos="fade-up"` to elements for scroll triggers

### CSS Conventions
- Green gradient backgrounds (env-friendly theme) in `css/main.css`
- Animations in `css/donation.css` for form states
- Custom SCSS source in `scss/` (compiled to CSS)
- Lazy load images with `loading="lazy"` attribute

---

## Common Development Tasks

### Adding a New Content Type (e.g., "Resources")
1. Create HTML form page in root: `resources.html`
2. Create PHP handler in `/php/addsresource.php` with image upload logic (use uniqid pattern)
3. Create display handler: `/php/resourcesdisplay.php` (fetchall + JSON endpoint)
4. Add table creation in handler or separate `.sql` file: uuid, user_uuid FK, title, image[1-n], description, timestamps
5. Add auth check at top: `require_once 'php/auth_check.php'`
6. Add stats endpoint: `/php/getresourcestats.php`

### Adding a New User Field
1. Alter `users` table: `ALTER TABLE users ADD COLUMN field_name VARCHAR(255);`
2. Update `reg.php` to accept new field
3. Update `profile.php` and `updateprofile.php` to display/edit
4. Update login session assignment if needed (stored in `$_SESSION`)

### Fixing Security Issues
- **CSRF Tokens:** Not currently implemented in forms - should add hidden token validation
- **Password Fallback:** In `login.php`, remove the `$password === $hash` fallback once all passwords are hashed
- **Image Directory Permissions:** Ensure `/assets/img/` subdirs are writable (0755)
- **SQL Injection:** Migrate all dynamic queries to prepared statements where not already used

---

## Performance & Optimization Notes

- **Indexed Fields:** Common queries on uuid, user_uuid, created_at, email, phone, status (in donations)
- **Skeleton Loaders:** Use gradient backgrounds while loading async content (implemented in main.js)
- **Lazy Load:** Images use `loading="lazy"` attribute
- **Minified Vendors:** Bootstrap, AOS, Swiper already minified
- **Connection Pooling:** Not implemented; single connection per script (acceptable for small org use)

---

## Testing & Debugging

- **Test System:** `/php/test_system.php` - system diagnostics utility
- **Local Server:** XAMPP on localhost, phpmyadmin available for DB inspection
- **JSON Endpoints:** Can test directly in browser (e.g., `/php/getstats.php`, `/php/geteventstats.php`)
- **Session Debugging:** Check `$_SESSION` variables in any protected page

---

## External Dependencies

| Vendor | Version | Usage | Notes |
|--------|---------|-------|-------|
| Bootstrap | 5.3.3 | UI framework, responsive grid | In `/vendor/bootstrap/` |
| AOS | Latest | Scroll animations | Triggered via `.animate-on-scroll` class |
| Swiper | Latest | Image carousel | Initialized in main.js |
| GLightbox | Latest | Image lightbox/gallery view | Used for gallery.html |
| Bootstrap Icons | Latest | Icon set | Icon library classes |

---

## Critical Files Reference

| File | Purpose | Key Points |
|------|---------|-----------|
| [php/connection.php](php/connection.php) | Database connection | MySQLi, localhost, root, no password |
| [php/auth_check.php](php/auth_check.php) | Auth middleware | Session + Remember Me token validation |
| [php/addpost.php](php/addpost.php) | Blog creation | Image upload pattern exemplar |
| [php/process_visa_payment.php](php/process_visa_payment.php) | Payment processing | Luhn validation, card masking, JSON response |
| [assets/js/main.js](assets/js/main.js) | Frontend animations | Loader, scroll animations, stagger effects |
| [PROJECT_ANALYSIS.md](PROJECT_ANALYSIS.md) | Full documentation | Complete schema, workflows, known issues |

---

## Quick Reference: Error Query Parameters

When redirecting with errors, use these params:
- `?error=not_logged_in` - User session expired
- `?error=empty_fields` - Required form fields missing
- `?error=no_image` - Required image not provided
- `?error=upload_failed` - File upload error
- `?error=invalid_credentials` - Login failed
- `?success=true` - Operation completed successfully

---

## Notes for AI Agents

- Always check [PROJECT_ANALYSIS.md](PROJECT_ANALYSIS.md) first for complete context
- Use UUID (not serial IDs) when adding new content types that need user association
- Test image uploads to ensure `/assets/img/[category]/` directories exist and are writable
- Verify session starts with `session_start()` before any `$_SESSION` access
- Remember this is a non-profit site focused on education; features should prioritize usability and accessibility
- The codebase is small enough for monolithic PHP - no microservices or APIs outside JSON endpoints
