<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header class="site-header">
        <div class="site-header__container">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
                <?php bloginfo('name'); ?>
            </a>

            <nav class="main-nav" role="navigation"
                aria-label="<?php esc_attr_e('Primary Navigation', 'smart-portfolio'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => '',
                    'fallback_cb' => false,
                ));
                ?>
            </nav>

            <button class="menu-toggle" aria-expanded="false"
                aria-label="<?php esc_attr_e('Toggle menu', 'smart-portfolio'); ?>">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Theme toggle will be added by JavaScript -->
        </div>
    </header>

    <main id="main-content" class="site-main">