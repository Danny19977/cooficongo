# Donation System - Cooficongo

## Overview
Complete donation system with multiple payment methods including PayPal, M-Pesa, Orange Money, and Visa/Credit Card payments.

## Files Created

### Frontend Files
1. **donation.html** - Main donation page with payment method selection
2. **assets/css/donation.css** - Styling for donation page and modals
3. **assets/js/donation.js** - JavaScript for modal interactions and form validation

### Backend Files
1. **php/process_visa_payment.php** - Visa payment processing backend
2. **php/donationsdisplay.php** - Admin panel to view and manage donations
3. **php/create_donations_table.sql** - Database schema for donations table

## Features

### Payment Methods

#### 1. PayPal
- User enters donation amount, name, and email
- Displays PayPal email where payment should be sent
- Manual verification process

#### 2. M-Pesa (MSPSA)
- User enters donation amount, name, and phone number
- Displays M-Pesa phone number for payment
- Uses reference code: DONATION + Name

#### 3. Orange Money
- User enters donation amount, name, and phone number
- Displays Orange Money phone number for payment
- Uses reference code: DONATION + Name

#### 4. Visa/Credit Card
- Full payment form with:
  - Donation amount
  - Cardholder name
  - Card number (with auto-formatting)
  - Expiry date (MM/YY format)
  - CVV (3-4 digits)
  - Email and phone number
- Real-time validation:
  - Luhn algorithm for card number validation
  - Expiry date validation
  - CVV format validation
- Secure backend processing
- Automatic transaction ID generation
- Database storage of donation records

## Setup Instructions

### 1. Database Setup
Run the SQL script to create the donations table:

```sql
-- Option 1: Use phpMyAdmin
1. Open phpMyAdmin
2. Select 'cooficongo' database
3. Go to SQL tab
4. Copy and paste content from php/create_donations_table.sql
5. Click 'Go'

-- Option 2: Use MySQL command line
mysql -u root -p cooficongo < php/create_donations_table.sql
```

### 2. Configure Payment Details
Edit `donation.html` to update payment contact information:

**Line ~170 (PayPal email):**
```html
<p class="email-display">donations@cooficongo.org</p>
```

**Line ~199 (M-Pesa phone number):**
```html
<p class="phone-display">+243 XXX XXX XXX</p>
```

**Line ~228 (Orange Money phone number):**
```html
<p class="phone-display">+243 YYY YYY YYY</p>
```

### 3. Test the System
1. Open `donation.html` in your browser
2. Click on any payment method
3. Fill in the required information
4. Submit the form
5. Check the database for the donation record

### 4. View Donations (Admin)
Access the donations management page:
```
http://localhost/cooficongo/php/donationsdisplay.php
```

This page shows:
- Total donations count
- Total amount collected
- Completed vs pending donations
- Breakdown by payment method
- Full transaction details

## Security Features

### Implemented Security
1. **Input Sanitization** - All user inputs are sanitized
2. **SQL Injection Protection** - Prepared statements used throughout
3. **XSS Protection** - HTML special characters escaped
4. **Card Data Protection** - Only last 4 digits stored
5. **Luhn Algorithm** - Card number validation
6. **Expiry Date Validation** - Prevents expired cards
7. **Email Validation** - Server-side email format checking

### Important Security Notes
⚠️ **PRODUCTION REQUIREMENTS:**

1. **SSL/HTTPS Required** - Must use HTTPS for credit card processing
2. **PCI DSS Compliance** - Never store full card numbers, CVV, or PINs
3. **Payment Gateway Integration** - Integrate with real payment processor:
   - Stripe (https://stripe.com)
   - PayPal API (https://developer.paypal.com)
   - Square (https://squareup.com)
   - Authorize.net (https://www.authorize.net)

4. **Current Implementation** - Simulated payment for development only
5. **Database Encryption** - Consider encrypting sensitive donation data
6. **Access Control** - Add authentication to donationsdisplay.php

## Payment Gateway Integration

### To Integrate Stripe (Recommended):
```php
// Install Stripe PHP library
composer require stripe/stripe-php

// In process_visa_payment.php, add:
require_once 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('your_secret_key');

$charge = \Stripe\Charge::create([
    'amount' => $amount * 100, // Convert to cents
    'currency' => 'usd',
    'source' => $token, // Obtained from Stripe.js
    'description' => 'Donation from ' . $cardholder_name,
]);
```

### To Integrate PayPal API:
```php
// Use PayPal REST API SDK
composer require paypal/rest-api-sdk-php

// Create payment request
$payment = new Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions([$transaction]);
```

## Customization

### Change Currency
Edit JavaScript validation and PHP processing:
```javascript
// In donation.js (line ~10)
<label for="visa-amount">Donation Amount (€)</label>

// In process_visa_payment.php
$currency = 'EUR'; // or 'CDF', 'USD', etc.
```

### Add New Payment Method
1. Add payment card to `donation.html`
2. Create modal with appropriate form fields
3. Add form submission handler in `donation.js`
4. Create PHP backend processor if needed

### Styling Customization
Edit `assets/css/donation.css`:
- Colors: Search for `#2ea359` to change primary color
- Card hover effects: Modify `.payment-card:hover`
- Modal appearance: Adjust `.modal-content` styles

## Email Notifications

To enable email notifications, uncomment in `process_visa_payment.php` (lines ~193-205):

```php
$to = $email;
$subject = "Donation Confirmation - Transaction #$transaction_id";
$message = "Thank you for your donation...";
$headers = "From: donations@cooficongo.org";
mail($to, $subject, $message, $headers);
```

Configure PHP mail or use libraries like PHPMailer for better email handling.

## API Endpoints

### Get Donations (AJAX)
```javascript
fetch('php/donationsdisplay.php?action=get_donations&limit=50&offset=0')
    .then(response => response.json())
    .then(data => console.log(data.donations));
```

### Get Statistics (AJAX)
```javascript
fetch('php/donationsdisplay.php?action=get_stats')
    .then(response => response.json())
    .then(data => console.log(data.stats));
```

### Get Single Donation (AJAX)
```javascript
fetch('php/donationsdisplay.php?action=get_donation&id=123')
    .then(response => response.json())
    .then(data => console.log(data.donation));
```

## Browser Compatibility
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers supported

## Responsive Design
- Desktop: Full 4-column layout
- Tablet: 2-column layout
- Mobile: Single column stack
- Touch-optimized modals

## Testing Checklist
- [ ] All payment modals open correctly
- [ ] Forms validate input properly
- [ ] Visa card validation works (Luhn algorithm)
- [ ] Expiry date validation prevents old dates
- [ ] CVV only accepts 3-4 digits
- [ ] Success modal displays after submission
- [ ] Database records are created correctly
- [ ] Admin panel displays donations
- [ ] Responsive design works on mobile
- [ ] Modal closes on Escape key
- [ ] Modal closes on outside click

## Troubleshooting

### Modal Not Opening
- Check browser console for JavaScript errors
- Verify donation.js is loaded correctly
- Check onclick handlers in HTML

### Form Not Submitting
- Check browser console for validation errors
- Verify all required fields are filled
- Check network tab for failed requests

### Database Errors
- Verify donations table exists
- Check database connection in connection.php
- Ensure proper permissions for database user

### Payment Processing Fails
- Check PHP error logs
- Verify all POST data is being received
- Test with different card numbers

## Support & Maintenance
- Regular database backups recommended
- Monitor failed transactions
- Review donation records monthly
- Update security patches regularly
- Test payment flows after updates

## Future Enhancements
- [ ] Integration with real payment gateways
- [ ] Automated email receipts
- [ ] Recurring donation options
- [ ] Donation goals/progress bars
- [ ] Donor dashboard
- [ ] Export donations to CSV/PDF
- [ ] Multi-currency support
- [ ] Tax receipt generation
- [ ] Donation analytics dashboard
- [ ] Social sharing after donation

## License
© 2025 Cooficongo. All rights reserved.

## Contact
For technical support or questions:
- Website: https://cooficongo.org
- Email: support@cooficongo.org
