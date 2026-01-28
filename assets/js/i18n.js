// Simple i18n utility for FR/EN toggle across pages
// Usage: add data-i18n, data-i18n-placeholder, or data-i18n-value attributes.

(function () {
  const I18N = {
    fr: {
      // Navbar
      'nav.home': 'Accueil',
      'nav.about': 'À propos',
      'nav.services': 'Nos Services',
      'nav.testimonials': 'Témoignages',
      'nav.blog': 'Blog',
      'nav.activities': 'Activités',
      'nav.contact': 'Contact',
      'nav.donate': 'Faire un don',
      'lang.current': 'Français',

      // Breadcrumbs
      'breadcrumbs.home': 'Accueil',

      // Contact page
      'contact.title': 'Contact',
      'contact.get_in_touch': 'Contactez-nous',
      'contact.location': 'Localisation :',
      'contact.email': 'Email :',
      'contact.call': 'Téléphone :',
      'contact.placeholder.name': 'Votre nom',
      'contact.placeholder.email': 'Votre e-mail',
      'contact.placeholder.subject': 'Sujet',
      'contact.placeholder.message': 'Message',
      'contact.send_message': 'Envoyer',

      // Newsletter
      'newsletter.title': 'Abonnez-vous à notre newsletter',
      'newsletter.placeholder.email': 'Entrez votre e-mail',
      'newsletter.subscribe': "S'abonner",
      'newsletter.loading': 'Chargement',
      'newsletter.sent': 'Votre demande d\'abonnement a été envoyée. Merci !',

      // Footer
      'footer.useful_links': 'Liens utiles',
      'footer.what_we_do': 'Ce que nous faisons',
      'footer.activities': 'Activités',
      'footer.our_activities': 'Nos activités',

      // Donation page
      'donation.choose_method': 'Choisissez votre méthode de paiement',
      'donation.choose_method_desc': 'Sélectionnez l\'option de paiement pour faire un don sécurisé',
      'donation.paypal': 'PayPal',
      'donation.paypal_desc': 'Paiement rapide et sécurisé via PayPal',
      'donation.mpesa': 'M-Pesa',
      'donation.mpesa_desc': 'Transfert d\'argent mobile via M-Pesa',
      'donation.orange': 'Orange Money',
      'donation.orange_desc': 'Paiement mobile via Orange Money',
      'donation.donate_now': 'Faire un don',
      'donation.page_title': 'Soutenez notre mission',
      'donation.page_desc': 'Votre générosité nous aide à faire la différence dans notre communauté',
      'donation.breadcrumbs.current': 'Don',
    },
    en: {
      // Navbar
      'nav.home': 'Home',
      'nav.about': 'About Us',
      'nav.services': 'Our Services',
      'nav.testimonials': 'Testimonials',
      'nav.blog': 'Blog',
      'nav.activities': 'Activities',
      'nav.contact': 'Contact',
      'nav.donate': 'Donate',
      'lang.current': 'English',

      // Breadcrumbs
      'breadcrumbs.home': 'Home',

      // Contact page
      'contact.title': 'Contact',
      'contact.get_in_touch': 'Get in touch',
      'contact.location': 'Location:',
      'contact.email': 'Email:',
      'contact.call': 'Call:',
      'contact.placeholder.name': 'Your Name',
      'contact.placeholder.email': 'Your Email',
      'contact.placeholder.subject': 'Subject',
      'contact.placeholder.message': 'Message',
      'contact.send_message': 'Send Message',

      // Newsletter
      'newsletter.title': 'Subscribe To Our Newsletter',
      'newsletter.placeholder.email': 'Enter your e-mail',
      'newsletter.subscribe': 'Subscribe',
      'newsletter.loading': 'Loading',
      'newsletter.sent': 'Your subscription request has been sent. Thank you!',

      // Footer
      'footer.useful_links': 'Useful Links',
      'footer.what_we_do': 'What we do',
      'footer.activities': 'Activities',
      'footer.our_activities': 'Our Activities',

      // Donation page
      'donation.choose_method': 'Choose Your Payment Method',
      'donation.choose_method_desc': 'Select your preferred payment option to make a secure donation',
      'donation.paypal': 'PayPal',
      'donation.paypal_desc': 'Quick and secure payment via PayPal',
      'donation.mpesa': 'M-Pesa',
      'donation.mpesa_desc': 'Mobile money transfer via M-Pesa',
      'donation.orange': 'Orange Money',
      'donation.orange_desc': 'Mobile payment via Orange Money',
      'donation.donate_now': 'Donate Now',
      'donation.page_title': 'Support Our Mission',
      'donation.page_desc': 'Your generosity helps us make a difference in our community',
      'donation.breadcrumbs.current': 'Donate',
    }
  };

  const STATE = {
    lang: 'fr'
  };

  function getSavedLang() {
    try {
      const l = localStorage.getItem('lang');
      if (l && (l === 'fr' || l === 'en')) return l;
    } catch (_) {}
    // Fallback to document lang or default fr
    const docLang = document.documentElement.getAttribute('lang');
    if (docLang && (docLang.startsWith('fr') || docLang.startsWith('en'))) {
      return docLang.startsWith('en') ? 'en' : 'fr';
    }
    return 'fr';
  }

  function saveLang(lang) {
    try { localStorage.setItem('lang', lang); } catch (_) {}
  }

  function translateElement(el, key, lang) {
    const dict = I18N[lang] || {};
    if (dict[key] !== undefined) {
      el.textContent = dict[key];
    }
  }

  function applyI18n(lang) {
    const dict = I18N[lang] || {};

    document.querySelectorAll('[data-i18n]').forEach(el => {
      const key = el.getAttribute('data-i18n');
      translateElement(el, key, lang);
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
      const key = el.getAttribute('data-i18n-placeholder');
      if (dict[key] !== undefined) el.setAttribute('placeholder', dict[key]);
    });

    document.querySelectorAll('[data-i18n-value]').forEach(el => {
      const key = el.getAttribute('data-i18n-value');
      if (dict[key] !== undefined) el.setAttribute('value', dict[key]);
    });

    // Update active label on dropdown toggle, if present
    const currentLabel = document.querySelector('.language-dropdown [data-i18n="lang.current"]');
    if (currentLabel) currentLabel.textContent = dict['lang.current'] || (lang === 'fr' ? 'Français' : 'English');

    // Update <html lang>
    document.documentElement.setAttribute('lang', lang);
  }

  function setLanguage(lang) {
    if (!I18N[lang]) return;
    STATE.lang = lang;
    saveLang(lang);
    applyI18n(lang);
  }

  // Event delegation for language menu clicks
  document.addEventListener('click', function (e) {
    const a = e.target.closest('[data-lang]');
    if (a) {
      e.preventDefault();
      const lang = a.getAttribute('data-lang');
      setLanguage(lang);
      // Close any open dropdowns if using Bootstrap
      try { const dropdown = bootstrap.Dropdown.getInstance(a.closest('.dropdown-menu')?.previousElementSibling); dropdown?.hide(); } catch (_) {}
    }
  });

  // Initialize on DOM ready
  document.addEventListener('DOMContentLoaded', function () {
    const lang = getSavedLang();
    STATE.lang = lang;
    applyI18n(lang);
  });

  // Expose globally (optional)
  window.setLanguage = setLanguage;
})();
