</main>

<footer class="site-footer">
    <div class="site-footer__container">
        <div class="site-footer__content">
            <div class="footer-column">
                <h3>
                    <?php bloginfo('name'); ?>
                </h3>
                <p>
                    <?php bloginfo('description'); ?>
                </p>
            </div>

            <div class="footer-column">
                <h4>
                    <?php _e('Quick Links', 'smart-portfolio'); ?>
                </h4>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container' => false,
                    'menu_class' => 'footer-menu',
                    'fallback_cb' => false,
                ));
                ?>
            </div>

            <div class="footer-column">
                <h4>
                    <?php _e('Connect', 'smart-portfolio'); ?>
                </h4>
                <div class="social-links">
                    <!-- Add your social media links here -->
                    <a href="#" aria-label="GitHub">GitHub</a>
                    <a href="#" aria-label="LinkedIn">LinkedIn</a>
                    <a href="#" aria-label="Twitter">Twitter</a>
                </div>
            </div>
        </div>

        <div class="site-footer__bottom">
            <p>&copy;
                <?php echo date('Y'); ?>
                <?php bloginfo('name'); ?>.
                <?php _e('All rights reserved.', 'smart-portfolio'); ?>
            </p>
            <p>
                <?php _e('Built with WordPress & Smart Portfolio Theme', 'smart-portfolio'); ?>
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>

</html>