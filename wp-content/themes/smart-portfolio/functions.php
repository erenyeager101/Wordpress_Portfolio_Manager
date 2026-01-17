<?php
/**
 * Smart Portfolio Theme Functions
 *
 * @package Smart_Portfolio
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Setup
 */
function smart_portfolio_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 675, true);

    // Add custom image sizes
    add_image_size('project-thumbnail', 600, 400, true);
    add_image_size('project-hero', 1920, 1080, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'smart-portfolio'),
        'footer' => __('Footer Menu', 'smart-portfolio'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for Block Styles
    add_theme_support('wp-block-styles');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'smart_portfolio_setup');

/**
 * Register Custom Post Types
 */
function smart_portfolio_register_cpts() {
    // Projects CPT
    register_post_type('project', array(
        'label' => __('Projects', 'smart-portfolio'),
        'labels' => array(
            'name' => __('Projects', 'smart-portfolio'),
            'singular_name' => __('Project', 'smart-portfolio'),
            'add_new' => __('Add New Project', 'smart-portfolio'),
            'add_new_item' => __('Add New Project', 'smart-portfolio'),
            'edit_item' => __('Edit Project', 'smart-portfolio'),
            'new_item' => __('New Project', 'smart-portfolio'),
            'view_item' => __('View Project', 'smart-portfolio'),
            'search_items' => __('Search Projects', 'smart-portfolio'),
            'not_found' => __('No projects found', 'smart-portfolio'),
            'not_found_in_trash' => __('No projects found in trash', 'smart-portfolio'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'projects'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
    ));

    // Project Categories
    register_taxonomy('project_category', 'project', array(
        'label' => __('Project Categories', 'smart-portfolio'),
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'project-category'),
    ));

    // Project Tags
    register_taxonomy('project_tag', 'project', array(
        'label' => __('Project Tags', 'smart-portfolio'),
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'project-tag'),
    ));

    // Leads CPT (admin-only for contact form submissions)
    register_post_type('lead', array(
        'label' => __('Leads', 'smart-portfolio'),
        'labels' => array(
            'name' => __('Leads', 'smart-portfolio'),
            'singular_name' => __('Lead', 'smart-portfolio'),
            'view_item' => __('View Lead', 'smart-portfolio'),
            'search_items' => __('Search Leads', 'smart-portfolio'),
        ),
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'query_var' => false,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 25,
        'menu_icon' => 'dashicons-email',
        'supports' => array('title', 'custom-fields'),
    ));
}
add_action('init', 'smart_portfolio_register_cpts');

/**
 * Enqueue Scripts and Styles
 */
function smart_portfolio_enqueue_scripts() {
    // Google Fonts - Inter
    wp_enqueue_style('smart-portfolio-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap', array(), null);

    // Main stylesheet
    wp_enqueue_style('smart-portfolio-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));

    // Compiled CSS from SCSS
    if (file_exists(get_template_directory() . '/assets/css/main.css')) {
        wp_enqueue_style('smart-portfolio-main', get_template_directory_uri() . '/assets/css/main.css', array(), filemtime(get_template_directory() . '/assets/css/main.css'));
    }

    // Main JavaScript
    wp_enqueue_script('smart-portfolio-main', get_template_directory_uri() . '/js/main.js', array(), filemtime(get_template_directory() . '/js/main.js'), true);

    // Dark mode toggle
    wp_enqueue_script('smart-portfolio-theme-toggle', get_template_directory_uri() . '/js/theme-toggle.js', array(), filemtime(get_template_directory() . '/js/theme-toggle.js'), true);

    // Smooth scroll and animations
    wp_enqueue_script('smart-portfolio-animations', get_template_directory_uri() . '/js/animations.js', array(), filemtime(get_template_directory() . '/js/animations.js'), true);

    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'smart_portfolio_enqueue_scripts');

/**
 * Add custom meta boxes for projects
 */
function smart_portfolio_add_project_meta_boxes() {
    add_meta_box(
        'project_details',
        __('Project Details', 'smart-portfolio'),
        'smart_portfolio_project_details_callback',
        'project',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'smart_portfolio_add_project_meta_boxes');

function smart_portfolio_project_details_callback($post) {
    wp_nonce_field('smart_portfolio_save_project_details', 'smart_portfolio_project_details_nonce');
    
    $project_url = get_post_meta($post->ID, '_project_url', true);
    $github_url = get_post_meta($post->ID, '_github_url', true);
    $technologies = get_post_meta($post->ID, '_technologies', true);
    $client = get_post_meta($post->ID, '_client', true);
    $completion_date = get_post_meta($post->ID, '_completion_date', true);
    ?>
    <p>
        <label for="project_url"><?php _e('Project URL:', 'smart-portfolio'); ?></label><br>
        <input type="url" id="project_url" name="project_url" value="<?php echo esc_attr($project_url); ?>" style="width: 100%;" placeholder="https://example.com">
    </p>
    <p>
        <label for="github_url"><?php _e('GitHub URL:', 'smart-portfolio'); ?></label><br>
        <input type="url" id="github_url" name="github_url" value="<?php echo esc_attr($github_url); ?>" style="width: 100%;" placeholder="https://github.com/username/repo">
    </p>
    <p>
        <label for="technologies"><?php _e('Technologies Used (comma-separated):', 'smart-portfolio'); ?></label><br>
        <input type="text" id="technologies" name="technologies" value="<?php echo esc_attr($technologies); ?>" style="width: 100%;" placeholder="React, Node.js, MongoDB">
    </p>
    <p>
        <label for="client"><?php _e('Client Name:', 'smart-portfolio'); ?></label><br>
        <input type="text" id="client" name="client" value="<?php echo esc_attr($client); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="completion_date"><?php _e('Completion Date:', 'smart-portfolio'); ?></label><br>
        <input type="date" id="completion_date" name="completion_date" value="<?php echo esc_attr($completion_date); ?>">
    </p>
    <?php
}

function smart_portfolio_save_project_details($post_id) {
    if (!isset($_POST['smart_portfolio_project_details_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['smart_portfolio_project_details_nonce'], 'smart_portfolio_save_project_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array('project_url', 'github_url', 'technologies', 'client', 'completion_date');
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_project', 'smart_portfolio_save_project_details');

/**
 * Customize excerpt length
 */
function smart_portfolio_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'smart_portfolio_excerpt_length');

/**
 * Customize excerpt more string
 */
function smart_portfolio_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'smart_portfolio_excerpt_more');

/**
 * Add body classes for dark mode
 */
function smart_portfolio_body_classes($classes) {
    // Add class for JavaScript detection
    $classes[] = 'no-js';
    return $classes;
}
add_filter('body_class', 'smart_portfolio_body_classes');
