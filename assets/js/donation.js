/**
 * Donation Page JavaScript
 * Handles payment modals, form validation, and submissions
 */

// Open payment modal
function openPaymentModal(paymentType) {
  const modalId = paymentType === 'mspsa' ? 'mpsaModal' : paymentType + 'Modal';
  const modal = document.getElementById(modalId);
  
  if (modal) {
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
  }
}

// Close payment modal
function closePaymentModal(paymentType) {
  const modalId = paymentType === 'mspsa' ? 'mpsaModal' : paymentType + 'Modal';
  const modal = document.getElementById(modalId);
  
  if (modal) {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form
    const form = modal.querySelector('form');
    if (form) {
      form.reset();
    }
  }
}

// Close success modal
function closeSuccessModal() {
  const modal = document.getElementById('successModal');
  if (modal) {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
  }
}

// Show success modal
function showSuccessModal() {
  const modal = document.getElementById('successModal');
  if (modal) {
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }
}

// Close modal when clicking outside
window.onclick = function(event) {
  if (event.target.classList.contains('payment-modal')) {
    event.target.style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Reset form
    const form = event.target.querySelector('form');
    if (form) {
      form.reset();
    }
  }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    const modals = document.querySelectorAll('.payment-modal');
    modals.forEach(modal => {
      if (modal.style.display === 'block') {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Reset form
        const form = modal.querySelector('form');
        if (form) {
          form.reset();
        }
      }
    });
  }
});

// Format card number input (add spaces every 4 digits)
function formatCardNumber(input) {
  let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
  let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
  input.value = formattedValue;
}

// Format expiry date (MM/YY)
function formatExpiryDate(input) {
  let value = input.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
  
  if (value.length >= 2) {
    value = value.substring(0, 2) + '/' + value.substring(2, 4);
  }
  
  input.value = value;
}

// Validate card number using Luhn algorithm
function validateCardNumber(cardNumber) {
  const digits = cardNumber.replace(/\s+/g, '');
  
  if (!/^\d{13,19}$/.test(digits)) {
    return false;
  }
  
  let sum = 0;
  let isEven = false;
  
  for (let i = digits.length - 1; i >= 0; i--) {
    let digit = parseInt(digits[i]);
    
    if (isEven) {
      digit *= 2;
      if (digit > 9) {
        digit -= 9;
      }
    }
    
    sum += digit;
    isEven = !isEven;
  }
  
  return sum % 10 === 0;
}

// Validate expiry date
function validateExpiryDate(expiryDate) {
  const parts = expiryDate.split('/');
  
  if (parts.length !== 2) {
    return false;
  }
  
  const month = parseInt(parts[0]);
  const year = parseInt('20' + parts[1]);
  
  if (month < 1 || month > 12) {
    return false;
  }
  
  const now = new Date();
  const currentYear = now.getFullYear();
  const currentMonth = now.getMonth() + 1;
  
  if (year < currentYear || (year === currentYear && month < currentMonth)) {
    return false;
  }
  
  return true;
}

// Validate CVV
function validateCVV(cvv) {
  return /^\d{3,4}$/.test(cvv);
}

// Show error message
function showError(form, message) {
  let errorDiv = form.querySelector('.error-message');
  
  if (!errorDiv) {
    errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    form.insertBefore(errorDiv, form.firstChild);
  }
  
  errorDiv.textContent = message;
  errorDiv.classList.add('show');
  
  setTimeout(() => {
    errorDiv.classList.remove('show');
  }, 5000);
}

// Initialize event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
  
  // Card number formatting
  const cardNumberInput = document.getElementById('visa-number');
  if (cardNumberInput) {
    cardNumberInput.addEventListener('input', function() {
      formatCardNumber(this);
    });
  }
  
  // Expiry date formatting
  const expiryInput = document.getElementById('visa-expiry');
  if (expiryInput) {
    expiryInput.addEventListener('input', function() {
      formatExpiryDate(this);
    });
  }
  
  // CVV validation (numbers only)
  const cvvInput = document.getElementById('visa-cvv');
  if (cvvInput) {
    cvvInput.addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
    });
  }
  
  // PayPal Form Submission
  const paypalForm = document.getElementById('paypalForm');
  if (paypalForm) {
    paypalForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const amount = document.getElementById('paypal-amount').value;
      const name = document.getElementById('paypal-name').value;
      const email = document.getElementById('paypal-email').value;
      
      if (!amount || amount <= 0) {
        showError(this, 'Please enter a valid donation amount.');
        return;
      }
      
      if (!name || !email) {
        showError(this, 'Please fill in all required fields.');
        return;
      }
      
      // Close PayPal modal
      closePaymentModal('paypal');
      
      // Show success message
      setTimeout(() => {
        showSuccessModal();
      }, 300);
    });
  }
  
  // M-Pesa Form Submission
  const mpsaForm = document.getElementById('mpsaForm');
  if (mpsaForm) {
    mpsaForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const amount = document.getElementById('mpsa-amount').value;
      const name = document.getElementById('mpsa-name').value;
      const phone = document.getElementById('mpsa-phone').value;
      
      if (!amount || amount <= 0) {
        showError(this, 'Please enter a valid donation amount.');
        return;
      }
      
      if (!name || !phone) {
        showError(this, 'Please fill in all required fields.');
        return;
      }
      
      // Close M-Pesa modal
      closePaymentModal('mspsa');
      
      // Show success message
      setTimeout(() => {
        showSuccessModal();
      }, 300);
    });
  }
  
  // Orange Money Form Submission
  const orangeForm = document.getElementById('orangeForm');
  if (orangeForm) {
    orangeForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const amount = document.getElementById('orange-amount').value;
      const name = document.getElementById('orange-name').value;
      const phone = document.getElementById('orange-phone').value;
      
      if (!amount || amount <= 0) {
        showError(this, 'Please enter a valid donation amount.');
        return;
      }
      
      if (!name || !phone) {
        showError(this, 'Please fill in all required fields.');
        return;
      }
      
      // Close Orange modal
      closePaymentModal('orange');
      
      // Show success message
      setTimeout(() => {
        showSuccessModal();
      }, 300);
    });
  }
  
  // Visa Form Submission
  const visaForm = document.getElementById('visaForm');
  if (visaForm) {
    visaForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const amount = document.getElementById('visa-amount').value;
      const cardholderName = document.getElementById('visa-name').value;
      const cardNumber = document.getElementById('visa-number').value;
      const expiryDate = document.getElementById('visa-expiry').value;
      const cvv = document.getElementById('visa-cvv').value;
      const email = document.getElementById('visa-email').value;
      const phone = document.getElementById('visa-phone').value;
      
      // Validate amount
      if (!amount || amount <= 0) {
        showError(this, 'Please enter a valid donation amount.');
        return;
      }
      
      // Validate cardholder name
      if (!cardholderName || cardholderName.length < 3) {
        showError(this, 'Please enter a valid cardholder name.');
        return;
      }
      
      // Validate card number
      if (!validateCardNumber(cardNumber)) {
        showError(this, 'Please enter a valid card number.');
        return;
      }
      
      // Validate expiry date
      if (!validateExpiryDate(expiryDate)) {
        showError(this, 'Please enter a valid expiry date (MM/YY).');
        return;
      }
      
      // Validate CVV
      if (!validateCVV(cvv)) {
        showError(this, 'Please enter a valid CVV (3-4 digits).');
        return;
      }
      
      // Validate email and phone
      if (!email || !phone) {
        showError(this, 'Please fill in all required fields.');
        return;
      }
      
      // Show loading state
      const submitBtn = this.querySelector('.btn-submit');
      const originalText = submitBtn.textContent;
      submitBtn.classList.add('loading');
      submitBtn.disabled = true;
      
      // Prepare form data
      const formData = new FormData(this);
      
      // Submit to backend
      fetch('php/process_visa_payment.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        // Remove loading state
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        
        if (data.success) {
          // Close Visa modal
          closePaymentModal('visa');
          
          // Show success message
          setTimeout(() => {
            showSuccessModal();
          }, 300);
        } else {
          showError(visaForm, data.message || 'Payment processing failed. Please try again.');
        }
      })
      .catch(error => {
        // Remove loading state
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
        
        console.error('Error:', error);
        showError(visaForm, 'An error occurred. Please try again later.');
      });
    });
  }
  
});

// Phone number formatting (optional enhancement)
function formatPhoneNumber(input) {
  let value = input.value.replace(/\D/g, '');
  
  if (value.length > 0) {
    if (value.startsWith('243')) {
      // Format: +243 XXX XXX XXX
      value = value.substring(0, 12);
      if (value.length > 3) {
        value = '+243 ' + value.substring(3);
      } else {
        value = '+' + value;
      }
    } else if (value.startsWith('0')) {
      // Format: 0XXX XXX XXX
      value = value.substring(0, 10);
    }
  }
  
  input.value = value;
}

// Add phone formatting to phone inputs
document.addEventListener('DOMContentLoaded', function() {
  const phoneInputs = document.querySelectorAll('input[type="tel"]');
  phoneInputs.forEach(input => {
    input.addEventListener('blur', function() {
      formatPhoneNumber(this);
    });
  });
});
