/**
 * Main JavaScript
 * General functionality and utilities
 */

(function () {
    'use strict';

    // ============================================
    // MOBILE MENU TOGGLE
    // ============================================
    const mobileMenu = () => {
        const menuToggle = document.querySelector('.menu-toggle');
        const mainNav = document.querySelector('.main-nav');

        if (!menuToggle || !mainNav) return;

        menuToggle.addEventListener('click', () => {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            menuToggle.setAttribute('aria-expanded', !isExpanded);
            mainNav.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });

        // Close menu on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mainNav.classList.contains('active')) {
                menuToggle.setAttribute('aria-expanded', 'false');
                mainNav.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!mainNav.contains(e.target) && !menuToggle.contains(e.target)) {
                if (mainNav.classList.contains('active')) {
                    menuToggle.setAttribute('aria-expanded', 'false');
                    mainNav.classList.remove('active');
                    document.body.classList.remove('menu-open');
                }
            }
        });
    };

    // ============================================
    // SEARCH FUNCTIONALITY
    // ============================================
    const searchToggle = () => {
        const searchToggle = document.querySelector('.search-toggle');
        const searchForm = document.querySelector('.search-form');

        if (!searchToggle || !searchForm) return;

        searchToggle.addEventListener('click', () => {
            searchForm.classList.toggle('active');
            if (searchForm.classList.contains('active')) {
                searchForm.querySelector('input').focus();
            }
        });
    };

    // ============================================
    // BACK TO TOP BUTTON
    // ============================================
    const backToTop = () => {
        const backToTopBtn = document.createElement('button');
        backToTopBtn.className = 'back-to-top';
        backToTopBtn.innerHTML = '↑';
        backToTopBtn.setAttribute('aria-label', 'Back to top');
        backToTopBtn.style.cssText = `
      position: fixed;
      bottom: 2rem;
      right: 2rem;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: var(--gradient-primary);
      color: white;
      border: none;
      font-size: 1.5rem;
      cursor: pointer;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
      z-index: 1000;
      box-shadow: var(--shadow-lg);
    `;

        document.body.appendChild(backToTopBtn);

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 500) {
                backToTopBtn.style.opacity = '1';
                backToTopBtn.style.visibility = 'visible';
            } else {
                backToTopBtn.style.opacity = '0';
                backToTopBtn.style.visibility = 'hidden';
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    };

    // ============================================
    // READING PROGRESS BAR
    // ============================================
    const readingProgress = () => {
        if (!document.body.classList.contains('single-post')) return;

        const progressBar = document.createElement('div');
        progressBar.className = 'reading-progress';
        progressBar.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 0%;
      height: 3px;
      background: var(--gradient-primary);
      z-index: 9999;
      transition: width 0.1s ease;
    `;

        document.body.appendChild(progressBar);

        window.addEventListener('scroll', () => {
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight - windowHeight;
            const scrolled = window.pageYOffset;
            const progress = (scrolled / documentHeight) * 100;

            progressBar.style.width = progress + '%';
        });
    };

    // ============================================
    // COPY CODE BUTTON FOR CODE BLOCKS
    // ============================================
    const copyCodeButtons = () => {
        const codeBlocks = document.querySelectorAll('pre code');

        codeBlocks.forEach(codeBlock => {
            const pre = codeBlock.parentElement;
            const button = document.createElement('button');
            button.className = 'copy-code-btn';
            button.textContent = 'Copy';
            button.style.cssText = `
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        padding: 0.25rem 0.75rem;
        background: var(--color-surface-elevated);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        font-size: 0.75rem;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
      `;

            pre.style.position = 'relative';
            pre.appendChild(button);

            pre.addEventListener('mouseenter', () => {
                button.style.opacity = '1';
            });

            pre.addEventListener('mouseleave', () => {
                button.style.opacity = '0';
            });

            button.addEventListener('click', async () => {
                const code = codeBlock.textContent;
                try {
                    await navigator.clipboard.writeText(code);
                    button.textContent = 'Copied!';
                    setTimeout(() => {
                        button.textContent = 'Copy';
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy code:', err);
                }
            });
        });
    };

    // ============================================
    // EXTERNAL LINKS
    // ============================================
    const externalLinks = () => {
        const links = document.querySelectorAll('a[href^="http"]');

        links.forEach(link => {
            if (!link.href.includes(window.location.hostname)) {
                link.setAttribute('target', '_blank');
                link.setAttribute('rel', 'noopener noreferrer');
            }
        });
    };

    // ============================================
    // LIGHTBOX FOR IMAGES
    // ============================================
    const lightbox = () => {
        const images = document.querySelectorAll('.entry-content img, .project-hero img');

        if (images.length === 0) return;

        // Create lightbox elements
        const lightboxContainer = document.createElement('div');
        lightboxContainer.className = 'lightbox';

        const lightboxImage = document.createElement('img');

        const closeButton = document.createElement('button');
        closeButton.className = 'lightbox__close';
        closeButton.innerHTML = '&times;';

        lightboxContainer.appendChild(lightboxImage);
        lightboxContainer.appendChild(closeButton);
        document.body.appendChild(lightboxContainer);

        // Open lightbox
        images.forEach(img => {
            img.style.cursor = 'zoom-in';
            img.addEventListener('click', (e) => {
                e.preventDefault();
                lightboxImage.src = img.src;
                lightboxContainer.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        // Close lightbox
        const closeLightbox = () => {
            lightboxContainer.classList.remove('active');
            document.body.style.overflow = '';
        };

        closeButton.addEventListener('click', closeLightbox);

        lightboxContainer.addEventListener('click', (e) => {
            if (e.target === lightboxContainer) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && lightboxContainer.classList.contains('active')) {
                closeLightbox();
            }
        });
    };

    // ============================================
    // TESTIMONIAL SLIDER
    // ============================================
    const testimonialSlider = () => {
        const slider = document.querySelector('.testimonial-slider');
        if (!slider) return;

        const slides = slider.querySelectorAll('.testimonial-slide');
        const prevBtn = document.querySelector('.prev-testimonial');
        const nextBtn = document.querySelector('.next-testimonial');

        let currentSlide = 0;

        const showSlide = (index) => {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
        };

        const nextSlide = () => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        };

        const prevSlide = () => {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        };

        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);

        // Auto slide
        setInterval(nextSlide, 5000);
    };

    // ============================================
    // SKILL BAR ANIMATION
    // ============================================
    const skillBars = () => {
        const bars = document.querySelectorAll('.skill-bar__progress');

        if (bars.length === 0) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const width = entry.target.dataset.width;
                    entry.target.style.width = width;
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        bars.forEach(bar => observer.observe(bar));
    };

    // ============================================
    // INITIALIZE
    // ============================================
    const init = () => {
        mobileMenu();
        searchToggle();
        backToTop();
        readingProgress();
        copyCodeButtons();
        externalLinks();
        lightbox();
        testimonialSlider();
        skillBars();
    };

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
