// Simple i18n utility for FR/EN toggle across pages
// Usage: add data-i18n, data-i18n-placeholder, or data-i18n-value attributes.

(function () {
  const I18N = {
    fr: {
      // Navbar
      'nav.home': 'Accueil',
      'nav.about': 'À propos',
      'nav.services': 'Nous Faisons',
      'nav.testimonials': 'Témoignages',
      'nav.blog': 'Blog',
      'nav.activities': 'Activités',
      'nav.gallery': 'Galerie',
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

      // Footer headings
      'footer.useful_links': 'Liens utiles',
      'footer.what_we_do': 'Ce que nous faisons',
      'footer.activities': 'Activités',
      'footer.our_activities': 'Nos activités',

      // Footer nav links
      'footer.home': 'Accueil',
      'footer.about': 'À propos',
      'footer.services': 'Nous Faisons',
      'footer.contact': 'Contact',
      'footer.donate': 'Faire un don',
      'footer.blog': 'Blog',

      // Page titles
      'page.activities': 'Nos Activités',
      'page.about': 'À propos',
      'page.services': 'Nous Faisons',
      'page.contact': 'Contact',

      // Breadcrumbs current page
      'breadcrumbs.about': 'À propos',
      'breadcrumbs.services': 'Nous Faisons',
      'breadcrumbs.activities': 'Activités',
      'breadcrumbs.contact': 'Contact',
      'breadcrumbs.blog': 'Blog',
      'breadcrumbs.event_details': 'Détails de l\'événement',
      'breadcrumbs.gallery': 'Galerie',
      'breadcrumbs.testimonials': 'Témoignages',
      'breadcrumbs.donation': 'Don',

      // Page titles
      'page.blog': 'Blog',
      'page.gallery': 'Notre Galerie',
      'page.event_details': 'Détails de l\'événement',
      'page.testimonials': 'Témoignages',

      // Event details page
      'event.back_to_activities': 'Retour aux activités',

      // Call-to-action / newsletter section
      'cta.join_event': 'Rejoignez notre prochain événement',
      'cta.stay_updated': 'Restez informé de nos dernières activités et événements !',
      'cta.sent': 'Votre demande d\'abonnement a été envoyée. Merci !',
      'cta.loading': 'Chargement...',
      'cta.locations_label': 'Emplacements :',
      'cta.categories_label': 'Catégories :',

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
      'donation.paypal.modal_title': 'Don via PayPal',
      'donation.mpesa.modal_title': 'Don via M-Pesa',
      'donation.orange.modal_title': 'Don via Orange Money',
      'donation.label.amount_usd': 'Montant du don ($)',
      'donation.label.amount_fc': 'Montant du don (FC/$)',
      'donation.label.name': 'Votre nom',
      'donation.label.email': 'Votre e-mail',
      'donation.label.phone': 'Votre numéro de téléphone',
      'donation.send_to': 'Envoyer le paiement à :',
      'donation.paypal.note': 'Veuillez inclure votre nom dans la note de paiement',
      'donation.mobile.note': 'Référence : DON + Votre Nom',
      'donation.confirm': 'Confirmer le don',
      'donation.success.title': 'Merci !',
      'donation.success.msg1': 'Votre don a été reçu avec succès.',
      'donation.success.msg2': 'Nous apprécions votre généreux soutien !',
      'section.testimonials.title': 'TÉMOIGNAGES',
      'section.testimonials.desc': 'Les mots de nos merveilleuses femmes',

      // Hero Carousel
      'hero.slide1.title': 'Plantation',
      'hero.slide1.desc': 'Nous plantons des boutures am\u00e9lior\u00e9es, r\u00e9sistantes \u00e0 la mosa\u00efque, pour un manioc de haute qualit\u00e9 et \u00e0 fort rendement.',
      'hero.slide2.title': 'Entretien du champ',
      'hero.slide2.desc': 'Le sarclage, le pa\u00efllage et le binage sont nos pratiques naturelles de pr\u00e9dilection et constituent des piliers de l\u2019entretien du sol. Elles am\u00e9liorent la sant\u00e9 de la terre, limitent l\u2019usage de produits chimiques et favorisent une culture de manioc durable et \u00e9co-responsable.',
      'hero.slide3.title': 'R\u00e9colte',
      'hero.slide3.desc': 'Le manioc est r\u00e9colt\u00e9 \u00e0 maturit\u00e9 pour garantir une qualit\u00e9 optimale. Vari\u00e9t\u00e9s am\u00e9lior\u00e9es du manioc, RAV (R\u00e9sistante \u00e0 la mosa\u00efque africaine du manioc).',
      'hero.slide4.title': 'Transformation',
      'hero.slide6.title': 'Notre Coop\u00e9rative',
      'hero.slide6.desc': 'Depuis 2022, la Coop\u00e9rative des Femmes d\u2019Ign\u00ed\u00e9 \u0153uvre ensemble pour un avenir durable.',

      // Index About Stats
      'index.about.tagline': 'NOS R\u00c9ALISATIONS EN CHIFFRES',
      'index.about.title': 'Depuis 2022, la CooFi transforme <strong>l\u2019effort collectif en impact durable</strong>',
      'index.about.desc': 'La Coop\u00e9rative des Femmes d\u2019Ign\u00ed\u00e9 de Notre Dame du Perp\u00e9tuel Secours, CooFI en sigle, est une coop\u00e9rative paysanne qui op\u00e8re dans le domaine de l\u2019agro-transformation du manioc dans la zone p\u00e9ri-urbaine d\u2019Ign\u00ed\u00e9 situ\u00e9e dans le d\u00e9partement du Pool, en R\u00e9publique du Congo.',
      'about.stat1.title': '109\u202f000 chikwangues produites',
      'about.stat1.desc': 'Un produit local de qualit\u00e9, au c\u0153ur de l\u2019alimentation de nombreuses familles.',
      'about.stat2.title': '217\u202f850 personnes-jours servies',
      'about.stat2.desc': 'Un apport concret \u00e0 la s\u00e9curit\u00e9 alimentaire.',
      'about.stat3.title': '105 tonnes de manioc transform\u00e9es',
      'about.stat3.desc': 'Soit 600 sacs de farine, symbole d\u2019une fili\u00e8re locale qui se renforce ann\u00e9e apr\u00e8s ann\u00e9e.',
      'about.stat4.title': '40 emplois cr\u00e9\u00e9s, plus 1 meunier permanent',
      'about.stat4.desc': 'Une contribution directe \u00e0 l\u2019\u00e9conomie locale, port\u00e9e par le travail des femmes.',
      'about.stat5.title': '+10,9 millions FCFA g\u00e9n\u00e9r\u00e9s',
      'about.stat5.desc': 'Une activit\u00e9 qui prouve que solidarit\u00e9 rime avec rentabilit\u00e9 et durabilit\u00e9.',
      'about.stat6.title': 'Z\u00e9ro d\u00e9chet\u202f: les r\u00e9sidus deviennent du compost',
      'about.stat6.desc': 'Rien ne se perd.',
      'about.closing': 'La CooFi, c\u2019est la force des femmes, l\u2019impact local et la transformation durable.',

      // Modal
      'modal.coming_soon.title': 'Bient\u00f4t disponible',
      'modal.coming_soon.text': 'Cette vid\u00e9o sera bient\u00f4t disponible. Merci pour votre patience.',
      'modal.close': 'Fermer',

      // Section titles
      'section.activities.title': 'Activit\u00e9s',
      'section.activities.desc': 'Nos activit\u00e9s pour la communaut\u00e9 et notre \u00e9quipe f\u00e9minine',
      'section.recent_posts.title': 'Articles r\u00e9cents',
      'section.recent_posts.desc': 'D\u00e9couvrez nos derni\u00e8res publications',
      'section.partners.title': 'Nos partenaires',
      'section.partners.desc': 'Nous collaborons avec des institutions et organisations engag\u00e9es pour renforcer notre impact local.',

      // Newsletter CTA descriptions
      'cta.newsletter.index.desc': 'Rejoignez notre communaut\u00e9\u202f! Actualit\u00e9s de la ferme, \u00e9v\u00e9nements de la CooFI, et surprises gourmandes vous attendent. Un clic suffit pour rester connect\u00e9 \u00e0 la terre.',
      'cta.newsletter.about.desc': 'Rejoignez notre communaut\u00e9\u202f! Actualit\u00e9s, \u00e9v\u00e9nements de la CooFI et surprises vous attendent.',
      'cta.newsletter.services.heading': 'Abonnez-vous \u00e0 notre blog',
      'cta.newsletter.services.desc': 'D\u00e9couvrez des histoires inspirantes, des nouveaut\u00e9s et des voix de notre communaut\u00e9 \u2014 restez connect\u00e9s\u202f!',

      // About page content
      'about.page.subtitle': 'La CooFi, c\u2019est la force des femmes, l\u2019impact local et la transformation durable.',
      'about.section.heading': '\u00c0 propos de la structure',
      'about.section.desc': 'La Coop\u00e9rative des Femmes d\u2019Ign\u00ed\u00e9 de Notre Dame du Perp\u00e9tuel Secours, CooFI en sigle, est une coop\u00e9rative paysanne qui op\u00e8re dans le domaine de l\u2019agro-transformation du manioc dans la zone p\u00e9ri-urbaine d\u2019Ign\u00ed\u00e9 situ\u00e9e dans le d\u00e9partement du Pool, en R\u00e9publique du Congo.',
      'about.creation.heading': 'Date de la cr\u00e9ation',
      'about.creation.text': 'La CooFI a acquis sa personnalit\u00e9 juridique, \u00e0 la suite de l\u2019obtention, le 22 novembre 2022, de l\u2019attestation d\u2019agr\u00e9ment num\u00e9ro 953/MAEP/DGA-DAPR.',
      'about.vision.title': 'Vision',
      'about.vision.desc': 'Notre vision est de devenir une source d\u2019inspiration pour l\u2019autonomisation \u00e9conomique des femmes dans le secteur agricole, tout en \u00e9tant un pilier du d\u00e9veloppement agricole et \u00e9conomique de la r\u00e9gion d\u2019Ign\u00ed\u00e9.',
      'about.mission.title': 'Mission',
      'about.mission.desc': 'Notre mission est de promouvoir l\u2019autonomie et d\u2019am\u00e9liorer les conditions socio-\u00e9conomiques des membres, de contribuer au d\u00e9veloppement local tout en favorisant des pratiques agricoles durables.',
      'about.values.label': 'Valeurs',
      'about.values.empowerment': 'Autonomisation',
      'about.values.empowerment.desc': 'Nous cherchons \u00e0 autonomiser les femmes \u00e9conomiquement en offrant des opportunit\u00e9s d\u2019entrepreneuriat, d\u2019apprentissage et de leadership dans le secteur agricole.',
      'about.values.solidarity': 'Solidarit\u00e9',
      'about.values.solidarity.desc': 'Nous croyons en la force de la collaboration et de l\u2019entraide entre les femmes de notre coop\u00e9rative, ainsi que les membres de la communaut\u00e9.',
      'about.values.sustainability': 'Durabilit\u00e9',
      'about.values.sustainability.desc': 'Nous nous effor\u00e7ons de pratiquer une agriculture respectueuse de l\u2019environnement et socialement responsable pour pr\u00e9server les ressources naturelles pour les g\u00e9n\u00e9rations futures.',
      'team.title': 'Notre \u00c9quipe',
      'team.committee': 'Comit\u00e9 de gestion',
      'team.control': 'Commission de contr\u00f4le et de v\u00e9rification',
      'team.role.president': 'Pr\u00e9sidente',
      'team.role.vice_president': 'Vice-Pr\u00e9sidente',
      'team.role.secretary': 'Secr\u00e9taire G\u00e9n\u00e9rale',
      'team.role.treasurer': 'Tr\u00e9sori\u00e8re',

      // Services page
      'services.section.heading': 'Ce que nous faisons',
      'service.1': 'Transformation du manioc en chikwangue',
      'service.2': 'Production durable de manioc',
      'service.3': 'Formations, renforcement des capacit\u00e9s & r\u00e9seautage',
    },
    en: {
      // Navbar
      'nav.home': 'Home',
      'nav.about': 'About Us',
      'nav.services': 'Our Services',
      'nav.testimonials': 'Testimonials',
      'nav.blog': 'Blog',
      'nav.activities': 'Activities',
      'nav.gallery': 'Gallery',
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

      // Footer headings
      'footer.useful_links': 'Useful Links',
      'footer.what_we_do': 'What we do',
      'footer.activities': 'Activities',
      'footer.our_activities': 'Our Activities',

      // Footer nav links
      'footer.home': 'Home',
      'footer.about': 'About us',
      'footer.services': 'Our Services',
      'footer.contact': 'Contact',
      'footer.donate': 'Donate',
      'footer.blog': 'Blog',

      // Page titles
      'page.activities': 'Activities & Events',
      'page.about': 'About Us',
      'page.services': 'Our Services',
      'page.contact': 'Contact',

      // Breadcrumbs current page
      'breadcrumbs.about': 'About',
      'breadcrumbs.services': 'Our Services',
      'breadcrumbs.activities': 'Activities',
      'breadcrumbs.contact': 'Contact',
      'breadcrumbs.blog': 'Blog',
      'breadcrumbs.event_details': 'Event Details',
      'breadcrumbs.gallery': 'Gallery',
      'breadcrumbs.testimonials': 'Testimonials',
      'breadcrumbs.donation': 'Donate',

      // Page titles
      'page.blog': 'Blog',
      'page.gallery': 'Our Gallery',
      'page.event_details': 'Event Details',
      'page.testimonials': 'Testimonials',

      // Event details page
      'event.back_to_activities': 'Back to Activities',

      // Call-to-action / newsletter section
      'cta.join_event': 'Join Our Next Event',
      'cta.stay_updated': 'Stay updated with our latest activities and events!',
      'cta.sent': 'Your subscription request has been sent. Thank you!',
      'cta.loading': 'Loading',
      'cta.locations_label': 'Locations:',
      'cta.categories_label': 'Categories:',

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
      'donation.paypal.modal_title': 'PayPal Donation',
      'donation.mpesa.modal_title': 'M-Pesa Donation',
      'donation.orange.modal_title': 'Orange Money Donation',
      'donation.label.amount_usd': 'Donation Amount ($)',
      'donation.label.amount_fc': 'Donation Amount (FC/$)',
      'donation.label.name': 'Your Name',
      'donation.label.email': 'Your Email',
      'donation.label.phone': 'Your Phone Number',
      'donation.send_to': 'Send Payment To:',
      'donation.paypal.note': 'Please include your name in the payment note',
      'donation.mobile.note': 'Use reference: DONATION + Your Name',
      'donation.confirm': 'Confirm Donation',
      'donation.success.title': 'Thank You!',
      'donation.success.msg1': 'Your donation has been received successfully.',
      'donation.success.msg2': 'We appreciate your generous support!',
      'section.testimonials.title': 'TESTIMONIALS',
      'section.testimonials.desc': 'Words from our wonderful women',

      // Hero Carousel
      'hero.slide1.title': 'Cultivation',
      'hero.slide1.desc': 'We plant improved cuttings, resistant to mosaic disease, for high-quality, high-yield cassava.',
      'hero.slide2.title': 'Field Maintenance',
      'hero.slide2.desc': 'Weeding, mulching and hoeing are our preferred natural practices and form the pillars of soil maintenance. They improve soil health, limit the use of chemicals and promote sustainable, eco-responsible cassava cultivation.',
      'hero.slide3.title': 'Harvest',
      'hero.slide3.desc': 'Cassava is harvested at maturity to ensure optimal quality. Improved cassava varieties, RAV (Resistant to African Cassava Mosaic).',
      'hero.slide4.title': 'Processing',
      'hero.slide6.title': 'Our Cooperative',
      'hero.slide6.desc': 'Since 2022, the Women\u2019s Cooperative of Ign\u00ed\u00e9 has been working together for a sustainable future.',

      // Index About Stats
      'index.about.tagline': 'OUR ACHIEVEMENTS IN NUMBERS',
      'index.about.title': 'Since 2022, CooFi transforms <strong>collective effort into lasting impact</strong>',
      'index.about.desc': 'The Women\u2019s Cooperative of Ign\u00ed\u00e9 of Our Lady of Perpetual Help, CooFI for short, is a peasant cooperative that operates in agro-processing of cassava in the peri-urban area of Ign\u00ed\u00e9, located in the Pool department of the Republic of Congo.',
      'about.stat1.title': '109,000 chikwangues produced',
      'about.stat1.desc': 'A quality local product, at the heart of many families\u2019 nutrition.',
      'about.stat2.title': '217,850 person-days served',
      'about.stat2.desc': 'A concrete contribution to food security.',
      'about.stat3.title': '105 tonnes of cassava processed',
      'about.stat3.desc': 'That\u2019s 600 bags of flour, a symbol of a local industry growing stronger year after year.',
      'about.stat4.title': '40 jobs created, plus 1 permanent miller',
      'about.stat4.desc': 'A direct contribution to the local economy, driven by women\u2019s work.',
      'about.stat5.title': '+10.9 million FCFA generated',
      'about.stat5.desc': 'An activity proving that solidarity rhymes with profitability and sustainability.',
      'about.stat6.title': 'Zero waste: residues become compost',
      'about.stat6.desc': 'Nothing is wasted.',
      'about.closing': 'CooFi is the strength of women, local impact, and sustainable transformation.',

      // Modal
      'modal.coming_soon.title': 'Coming Soon',
      'modal.coming_soon.text': 'This video will be available soon. Thank you for your patience.',
      'modal.close': 'Close',

      // Section titles
      'section.activities.title': 'Activities',
      'section.activities.desc': 'Our activities for the community and our women\u2019s team',
      'section.recent_posts.title': 'Recent Posts',
      'section.recent_posts.desc': 'Discover our latest publications',
      'section.partners.title': 'Our Partners',
      'section.partners.desc': 'We collaborate with committed institutions and organizations to strengthen our local impact.',

      // Newsletter CTA descriptions
      'cta.newsletter.index.desc': 'Join our community! Farm news, CooFI events, and tasty surprises await you. One click is all it takes to stay connected to the land.',
      'cta.newsletter.about.desc': 'Join our community! News, CooFI events and surprises await you.',
      'cta.newsletter.services.heading': 'Subscribe to our blog',
      'cta.newsletter.services.desc': 'Discover inspiring stories, news and voices from our community \u2014 stay connected!',

      // About page content
      'about.page.subtitle': 'CooFi is the strength of women, local impact, and sustainable transformation.',
      'about.section.heading': 'About the organization',
      'about.section.desc': 'The Women\u2019s Cooperative of Ign\u00ed\u00e9 of Our Lady of Perpetual Help, CooFI for short, is a peasant cooperative that operates in agro-processing of cassava in the peri-urban area of Ign\u00ed\u00e9, located in the Pool department of the Republic of Congo.',
      'about.creation.heading': 'Date of establishment',
      'about.creation.text': 'CooFI obtained its legal status following the issuance, on November 22, 2022, of approval certificate number 953/MAEP/DGA-DAPR.',
      'about.vision.title': 'Vision',
      'about.vision.desc': 'Our vision is to become a source of inspiration for the economic empowerment of women in the agricultural sector, while being a pillar of agricultural and economic development in the Ign\u00ed\u00e9 region.',
      'about.mission.title': 'Mission',
      'about.mission.desc': 'Our mission is to promote autonomy and improve the socio-economic conditions of members, to contribute to local development while fostering sustainable agricultural practices.',
      'about.values.label': 'Values',
      'about.values.empowerment': 'Empowerment',
      'about.values.empowerment.desc': 'We seek to economically empower women by providing entrepreneurship, learning and leadership opportunities in the agricultural sector.',
      'about.values.solidarity': 'Solidarity',
      'about.values.solidarity.desc': 'We believe in the power of collaboration and mutual support among the women of our cooperative and community members.',
      'about.values.sustainability': 'Sustainability',
      'about.values.sustainability.desc': 'We strive to practice environmentally and socially responsible agriculture to preserve natural resources for future generations.',
      'team.title': 'Our Team',
      'team.committee': 'Management Committee',
      'team.control': 'Control and Audit Committee',
      'team.role.president': 'Chairwoman',
      'team.role.vice_president': 'Vice-Chairwoman',
      'team.role.secretary': 'Secretary General',
      'team.role.treasurer': 'Treasurer',

      // Services page
      'services.section.heading': 'What we do',
      'service.1': 'Cassava to Chikwangue Transformation',
      'service.2': 'Sustainable Cassava Production',
      'service.3': 'Training, capacity building & networking',
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

    // Handle elements with HTML markup in translations (safe: translations are dev-controlled)
    document.querySelectorAll('[data-i18n-html]').forEach(el => {
      const key = el.getAttribute('data-i18n-html');
      if (dict[key] !== undefined) el.innerHTML = dict[key];
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
