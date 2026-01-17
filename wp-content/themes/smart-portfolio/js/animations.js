/**
 * Animations - Scroll Reveal & Micro-interactions
 * Handles scroll-based animations and interactive elements
 */

(function () {
    'use strict';

    // ============================================
    // SCROLL REVEAL
    // ============================================
    const revealElements = () => {
        const reveals = document.querySelectorAll('.reveal');

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px 0px -50px 0px'
        });

        reveals.forEach(element => {
            revealObserver.observe(element);
        });
    };

    // ============================================
    // HEADER SCROLL EFFECT
    // ============================================
    const handleHeaderScroll = () => {
        const header = document.querySelector('.site-header');
        if (!header) return;

        let lastScroll = 0;

        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }

            // Hide header on scroll down, show on scroll up
            if (currentScroll > lastScroll && currentScroll > 500) {
                header.style.transform = 'translateY(-100%)';
            } else {
                header.style.transform = 'translateY(0)';
            }

            lastScroll = currentScroll;
        });
    };

    // ============================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ============================================
    const smoothScrollLinks = () => {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    const headerOffset = 100;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    };

    // ============================================
    // PARALLAX EFFECT FOR HERO
    // ============================================
    const parallaxHero = () => {
        const hero = document.querySelector('.hero');
        if (!hero) return;

        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxSpeed = 0.5;
            hero.style.transform = `translateY(${scrolled * parallaxSpeed}px)`;
        });
    };

    // ============================================
    // CARD TILT EFFECT
    // ============================================
    const cardTiltEffect = () => {
        const cards = document.querySelectorAll('.project-card, .blog-card');

        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = (y - centerY) / 20;
                const rotateY = (centerX - x) / 20;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
            });
        });
    };

    // ============================================
    // MAGNETIC BUTTONS
    // ============================================
    const magneticButtons = () => {
        const buttons = document.querySelectorAll('.btn');

        buttons.forEach(button => {
            button.addEventListener('mousemove', (e) => {
                const rect = button.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                button.style.transform = `translate(${x * 0.2}px, ${y * 0.2}px)`;
            });

            button.addEventListener('mouseleave', () => {
                button.style.transform = 'translate(0, 0)';
            });
        });
    };

    // ============================================
    // CURSOR TRAIL EFFECT (Optional)
    // ============================================
    const cursorTrail = () => {
        const cursor = document.createElement('div');
        cursor.className = 'cursor-trail';
        cursor.style.cssText = `
      position: fixed;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: var(--color-primary);
      opacity: 0.3;
      pointer-events: none;
      z-index: 9999;
      transition: transform 0.15s ease-out;
      mix-blend-mode: difference;
      display: none;
    `;
        document.body.appendChild(cursor);

        // Only show on desktop
        if (window.innerWidth > 768) {
            cursor.style.display = 'block';

            document.addEventListener('mousemove', (e) => {
                cursor.style.left = e.clientX - 10 + 'px';
                cursor.style.top = e.clientY - 10 + 'px';
            });

            document.querySelectorAll('a, button').forEach(el => {
                el.addEventListener('mouseenter', () => {
                    cursor.style.transform = 'scale(2)';
                });
                el.addEventListener('mouseleave', () => {
                    cursor.style.transform = 'scale(1)';
                });
            });
        }
    };

    // ============================================
    // FORM VALIDATION & ENHANCEMENT
    // ============================================
    const enhanceForms = () => {
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, textarea');

            inputs.forEach(input => {
                // Add floating label effect
                input.addEventListener('focus', () => {
                    input.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', () => {
                    if (!input.value) {
                        input.parentElement.classList.remove('focused');
                    }
                });

                // Real-time validation
                input.addEventListener('input', () => {
                    if (input.validity.valid) {
                        input.classList.remove('invalid');
                        input.classList.add('valid');
                    } else {
                        input.classList.remove('valid');
                        input.classList.add('invalid');
                    }
                });
            });
        });
    };

    // ============================================
    // LAZY LOAD IMAGES
    // ============================================
    const lazyLoadImages = () => {
        const images = document.querySelectorAll('img[data-src]');

        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    };

    // ============================================
    // INITIALIZE ALL ANIMATIONS
    // ============================================
    const init = () => {
        revealElements();
        handleHeaderScroll();
        smoothScrollLinks();
        parallaxHero();
        cardTiltEffect();
        magneticButtons();
        enhanceForms();
        lazyLoadImages();
        // cursorTrail(); // Uncomment if you want the cursor trail effect
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Reinitialize on dynamic content load
    window.addEventListener('load', () => {
        revealElements();
        lazyLoadImages();
    });

})();
