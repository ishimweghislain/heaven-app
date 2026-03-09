/**
 * Success Microfinance Institution S.C. Website
 * Main JavaScript File
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // Back to top button
    const backToTop = document.querySelector('.back-to-top');
    
    if (backToTop) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                backToTop.classList.add('active');
            } else {
                backToTop.classList.remove('active');
            }
        });

        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Smooth scroll for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const headerOffset = 80;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    document.querySelector('.navbar-toggler').click();
                }
            }
        });
    });

    // Add active class to nav items on scroll
    const sections = document.querySelectorAll('section[id]');
    
    function highlightNavItem() {
        const scrollY = window.pageYOffset;
        
        sections.forEach(current => {
            const sectionHeight = current.offsetHeight;
            const sectionTop = current.offsetTop - 100;
            const sectionId = current.getAttribute('id');
            
            if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                document.querySelector('.navbar-nav a[href*=' + sectionId + ']').classList.add('active');
            } else {
                document.querySelector('.navbar-nav a[href*=' + sectionId + ']').classList.remove('active');
            }
        });
    }
    
    window.addEventListener('scroll', highlightNavItem);

    // Animation on scroll
    function animateOnScroll() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight - 50) {
                const animationClass = element.getAttribute('data-animation') || 'fade-in';
                element.classList.add(animationClass);
            }
        });
    }
    
    // Add animation classes to elements
    document.querySelectorAll('.about-card').forEach(card => {
        card.classList.add('animate-on-scroll');
        card.setAttribute('data-animation', 'slide-in-up');
    });
    
    document.querySelectorAll('.service-card').forEach(card => {
        card.classList.add('animate-on-scroll');
        card.setAttribute('data-animation', 'slide-in-up');
    });
    
    document.querySelectorAll('.news-card').forEach(card => {
        card.classList.add('animate-on-scroll');
        card.setAttribute('data-animation', 'slide-in-up');
    });
    
    document.querySelectorAll('.partner-logo').forEach(logo => {
        logo.classList.add('animate-on-scroll');
        logo.setAttribute('data-animation', 'fade-in');
    });
    
    // Run animation check on load and scroll
    window.addEventListener('load', animateOnScroll);
    window.addEventListener('scroll', animateOnScroll);

    // Form validation
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic form validation
            let isValid = true;
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const subject = document.getElementById('subject');
            const message = document.getElementById('message');
            
            if (!name.value.trim()) {
                isValid = false;
                name.classList.add('is-invalid');
            } else {
                name.classList.remove('is-invalid');
            }
            
            if (!email.value.trim() || !isValidEmail(email.value)) {
                isValid = false;
                email.classList.add('is-invalid');
            } else {
                email.classList.remove('is-invalid');
            }
            
            if (!subject.value.trim()) {
                isValid = false;
                subject.classList.add('is-invalid');
            } else {
                subject.classList.remove('is-invalid');
            }
            
            if (!message.value.trim()) {
                isValid = false;
                message.classList.add('is-invalid');
            } else {
                message.classList.remove('is-invalid');
            }
            
            if (isValid) {
                // In a real implementation, this would send the form data to the server
                // For now, we'll just show a success message
                const formContainer = contactForm.parentElement;
                formContainer.innerHTML = `
                    <div class="alert alert-success">
                        <h4>Thank you for your message!</h4>
                        <p>We have received your inquiry and will respond as soon as possible.</p>
                    </div>
                `;
            }
        });
    }
    
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    // Language switcher
    const languageItems = document.querySelectorAll('[data-lang]');
    
    if (languageItems.length > 0) {
        languageItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const lang = this.getAttribute('data-lang');
                
                // In a real implementation, this would load language-specific content
                // For now, we'll just store the language preference
                localStorage.setItem('preferredLanguage', lang);
                
                // Update language display in dropdown
                const languageDropdown = document.getElementById('languageDropdown');
                if (languageDropdown) {
                    languageDropdown.innerHTML = `<i class="fas fa-globe"></i> ${lang === 'en' ? 'English' : 'አማርኛ'}`;
                }
                
                // In the future, this would trigger content translation
                console.log(`Language switched to: ${lang}`);
            });
        });
        
        // Set initial language based on stored preference or default to English
        const storedLang = localStorage.getItem('preferredLanguage') || 'en';
        const languageDropdown = document.getElementById('languageDropdown');
        if (languageDropdown) {
            languageDropdown.innerHTML = `<i class="fas fa-globe"></i> ${storedLang === 'en' ? 'English' : 'አማርኛ'}`;
        }
    }
});
