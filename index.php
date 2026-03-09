<?php
/**
 * Index file with multilingual support
 * Heavenly Harvest Finance Ltd Website
 */

// Include language handler
require_once 'includes/language.php';
// Include database config
require_once 'includes/config.php';

// Get current language
$currentLang = getCurrentLanguage();
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php t('hero.title0', 'Heavenly Harvest Finance Ltd'); ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body data-bs-spy="scroll" data-bs-target="#navbar" data-bs-offset="100">

    
<!-- Header Section -->
    <header id="header" class="fixed-top">
        <!-- Social Media Top Bar -->
<div class="social-top-bar py-1" style="width: 100%; background-color: #f8f9fa;">
    <div class="d-flex justify-content-left align-items-left" style="width: 100%;">
     <div class="container">
        <div class="social-icons">
            <a href="https://www.facebook.com/"  target="_blank" class="me-3 text-dark"><i class="fab fa-facebook-f"></i></a>
            <a href="https://twitter.com/" target="_blank" class="me-3 text-dark"><i class="fab fa-twitter"></i></a>
            <a href="https://www.linkedin.com/" target="_blank" class="me-3 text-dark"><i class="fab fa-linkedin-in"></i></a>
            <a href="https://www.instagram.com/" target="_blank" class="text-dark"><i class="fab fa-instagram"></i></a>
        <p1 style="float:right"><i class="fas fa-phone"></i>0788452502</p1>
 
        </div>
        
    </div>
    </div>   
    
</div>

        <nav id="navbar" class="navbar navbar-expand-lg navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="#hero">
                    <img src="images/logo.png" alt="Heavenly Hervest Finance Logo" class="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#hero"><i class="fas fa-home"></i> <?php t('navigation.home'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about"><i class="fas fa-bookmark"></i> <?php t('navigation.about'); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#services"><i class="fas fa-book"></i> <?php t('navigation.services'); ?></a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#news"><?php t('navigation.news'); ?></a>
                        </li> -->
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#partners"><?php t('navigation.partners'); ?></a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="#contact"><i class="fas fa-map"></i> <?php t('navigation.contact'); ?></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-globe"></i> <?php t('navigation.language'); ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                <li><a class="dropdown-item <?php echo $currentLang === 'en' ? 'active' : ''; ?>" href="?lang=en"><img src="images/uk.ico" width="16" alt="UK Flag"> <?php t('navigation.english'); ?></a></li>
                                <li><a class="dropdown-item <?php echo $currentLang === 'kinya' ? 'active' : ''; ?>" href="?lang=rw"><img src="images/rw.ico" width="16" alt="RW Flag"> <?php t('navigation.kinyarwanda'); ?></a></li>
                                <li><a class="dropdown-item <?php echo $currentLang === 'francais' ? 'active' : ''; ?>" href="?lang=fr"><img src="images/fr.ico" width="16" alt="FR Flag"> <?php t('navigation.francais'); ?></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="d-flex align-items-center" style="padding-top: 100px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h4 class="text-white mb-5"><?php t('hero.title'); ?></h4>
                    <p class="text-white mb-5 lead">
                        <?php 
                        t('hero.tagline'); 
                        ?>
                    </p>
                    <a href="#about" class="btn btn-primary btn-lg"><?php t('hero.cta'); ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section-padding mt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-2">
                    <h2 class="section-title"><?php t('about.title'); ?></h2>
                    <div class="section-divider"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <div class="card h-100 about-card">
                        <div class="card-body text-center">
                            <div class="icon-box">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="card-title"><?php t('about.vision_title'); ?></h3>
                            <p class="card-text"><?php t('about.vision_text'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="card h-100 about-card">
                        <div class="card-body text-center">
                            <div class="icon-box">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h3 class="card-title"><?php t('about.mission_title'); ?></h3>
                            <p class="card-text"><?php t('about.mission_text'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="card h-100 about-card">
                        <div class="card-body text-center">
                            <div class="icon-box">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h3 class="card-title"><?php t('about.values_title'); ?></h3>
                            <p class="card-text"><?php t('about.values_text'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section-padding bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-2">
                    <h2 class="section-title"><?php t('services.title'); ?></h2>
                    <div class="section-divider"></div>
                </div>
            </div>
            
            
             
            
            <!-- Loan Products -->
            <div class="row mb-4 mt-3">
                <div class="col-12">
                    <h3 class="service-category"><?php t('services.loans_category'); ?></h3>
                </div>
            </div>
            <div class="row">
                <?php
                // Fetch loan products from database
                $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'loans' AND status = 1");
                $stmt->execute();
                $products = $stmt->fetchAll();
                foreach ($products as $product):
                ?>
                <div class="col-md-6 col-lg-4 mb-2">
                    <div class="card service-card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name_' . $currentLang]); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($product['description_' . $currentLang]); ?></p>
                            <button 
                                class="btn btn-outline-primary btn-sm mt-2 view-req-btn" 
                                type="button"
                                data-bs-toggle="modal"
                                data-bs-target="#requirementsModal"
                                data-title="<?php echo htmlspecialchars($product['name_' . $currentLang]); ?>"
                                data-requirements="<?php echo htmlspecialchars($product['requirements_' . $currentLang]); ?>"
                            >
                                <?php t('services.view_requirements'); ?>
                            </button>
                            <!-- Removed direct requirements display -->
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Modal for Requirements -->
    <div class="modal fade" id="requirementsModal" tabindex="-1" aria-labelledby="requirementsModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="requirementsModalLabel"><?php t('services.requirements'); ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h6 id="modalServiceTitle"></h6>
            <ul id="modalRequirementsList"></ul>
          </div>
        </div>
      </div>
    </div>

     

    <!-- Contact Section -->
    <section id="contact" class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-3">
                    <h4 class="section-title"><?php t('contact.title'); ?></h4>
                    <div class="section-divider"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-5 mb-2">
                    <div class="contact-info">
                        <h3><?php t('contact.get_in_touch'); ?></h3>
                        <p><?php t('contact.contact_intro'); ?></p>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <h4><?php t('contact.email_title'); ?></h4>
                                <p>info@heavenlyharvest-finance.com</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <h4><?php t('contact.phone_title'); ?></h4>
                                <p>+250788452502<br>+250788259486</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h4><?php t('contact.address_title'); ?></h4>
                                <p>Rwanda,Kigali<br>
                            Kicukiro,Nyarugunga<br>kamashashi,Mukoni</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-form">
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control" id="name" placeholder="<?php t('contact.form_name'); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" class="form-control" id="email" placeholder="<?php t('contact.form_email'); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="subject" placeholder="<?php t('contact.form_subject'); ?>" required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="message" rows="5" placeholder="<?php t('contact.form_message'); ?>" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><?php t('contact.form_submit'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3987.4368894686254!2d30.17209697496729!3d-1.9797103980023911!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMcKwNTgnNDcuMCJTIDMwwrAxMCcyOC44IkU!5e0!3m2!1sen!2ske!4v1752868863723!5m2!1sen!2ske" width="1200" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>        
                </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-2 mb-md-0">
                        <div class="footer-info">
                            <h4>Heavenly Harvest Finance Ltd</h4>
                            <p>
                                Rwanda,Kigali<br>
                                Kicukiro,Nyarugunga<br>Kamashashi,Mukoni
                                <br><br><strong><?php t('contact.phone_title'); ?>:</strong><br>+250788452502<br>+250788259486<br>
                                <br><strong><?php t('contact.email_title'); ?>:</strong><br>info@heavenlyharvest-finance.com<br>
                            </p>
                            <div class="social-links mt-2">
                                <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 mb-2 mb-md-0">
                        <h4><?php t('footer.quick_links'); ?></h4>
                        <ul class="footer-links">
                            <li><a href="#hero"><?php t('navigation.home'); ?></a></li>
                            <li><a href="#about"><?php t('navigation.about'); ?></a></li>
                            <li><a href="#services"><?php t('navigation.services'); ?></a></li>
                            <li><a href="#news"><?php t('navigation.news'); ?></a></li>
                            <li><a href="#contact"><?php t('navigation.contact'); ?></a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-2 mb-md-0">
                        <h4><?php t('footer.our_services'); ?></h4>
                        <ul class="footer-links">
                            <li><a href="#services"><?php t('services.savings_category'); ?></a></li>
                            <li><a href="#services"><?php t('services.loans_category'); ?></a></li>
                             
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <h4><?php t('footer.legal'); ?></h4>
                        <ul class="footer-links">
                            <li><a href="#"><?php t('footer.privacy_policy'); ?></a></li>
                            <li><a href="#"><?php t('footer.terms_of_service'); ?></a></li>
                            <li><a href="#"><?php t('footer.sitemap'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container py-4">
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> <strong><span>Heavenly Harvest Finance Ltd</span></strong>. <?php t('footer.all_rights_reserved'); ?>
            </div>
        </div>
    </footer>

    <!-- Back to top button -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="fas fa-arrow-up"></i></a>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/main.js"></script>
    <script>
    // Modal requirements population
    document.addEventListener('DOMContentLoaded', function() {
        var requirementsModal = document.getElementById('requirementsModal');
        requirementsModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var title = button.getAttribute('data-title');
            var requirements = button.getAttribute('data-requirements') || '';
            var reqList = requirements.split(';').map(function(req) { return req.trim(); }).filter(Boolean);

            document.getElementById('modalServiceTitle').textContent = title;
            var ul = document.getElementById('modalRequirementsList');
            ul.innerHTML = '';
            if (reqList.length > 0) {
                reqList.forEach(function(req) {
                    var li = document.createElement('li');
                    li.textContent = req;
                    ul.appendChild(li);
                });
            } else {
                var li = document.createElement('li');
                li.textContent = '<?php t('services.no_requirements'); ?>';
                ul.appendChild(li);
            }
        });
    });
    </script>
</body>
</html>
