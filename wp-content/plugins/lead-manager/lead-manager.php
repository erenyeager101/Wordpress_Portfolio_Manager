<?php
/**
 * Plugin Name: Advanced Lead Manager Pro
 * Plugin URI: https://example.com/lead-manager-pro
 * Description: Professional lead management system with analytics, export functionality, and advanced features for WordPress portfolio sites.
 * Version: 2.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: lead-manager-pro
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('LMP_VERSION', '2.0.0');
define('LMP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LMP_PLUGIN_URL', plugin_dir_url(__FILE__));

class Advanced_Lead_Manager_Pro
{

    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // AJAX handlers
        add_action('wp_ajax_lmp_submit_lead', array($this, 'ajax_submit_lead'));
        add_action('wp_ajax_nopriv_lmp_submit_lead', array($this, 'ajax_submit_lead'));
        add_action('wp_ajax_lmp_export_leads', array($this, 'export_leads_csv'));
        add_action('wp_ajax_lmp_get_stats', array($this, 'get_lead_stats'));

        // Shortcodes
        add_shortcode('lead_form', array($this, 'contact_form_shortcode'));
        add_shortcode('contact_form', array($this, 'contact_form_shortcode'));
        add_shortcode('lead_stats', array($this, 'lead_stats_shortcode'));
    }

    public function init()
    {
        // Handle non-AJAX form submission (fallback)
        if (isset($_POST['lmp_submit_lead']) && wp_verify_nonce($_POST['lmp_nonce'], 'lmp_submit_lead')) {
            $this->handle_form_submission();
        }
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('lead-manager-pro', LMP_PLUGIN_URL . 'assets/css/lead-manager-pro.css', array(), LMP_VERSION);
        wp_enqueue_script('lead-manager-pro', LMP_PLUGIN_URL . 'assets/js/lead-manager-pro.js', array('jquery'), LMP_VERSION, true);

        wp_localize_script('lead-manager-pro', 'lmpData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lmp_ajax_nonce'),
            'messages' => array(
                'success' => __('Thank you! Your message has been sent successfully.', 'lead-manager-pro'),
                'error' => __('Oops! Something went wrong. Please try again.', 'lead-manager-pro'),
                'validationError' => __('Please fill in all required fields correctly.', 'lead-manager-pro'),
                'fileTooBig' => __('File size must be less than 5MB.', 'lead-manager-pro'),
                'invalidFileType' => __('Invalid file type. Allowed: PDF, DOC, DOCX, JPG, PNG.', 'lead-manager-pro'),
            ),
            'maxFileSize' => 5 * 1024 * 1024, // 5MB
            'allowedFileTypes' => array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'),
        ));
    }

    public function enqueue_admin_scripts($hook)
    {
        if (strpos($hook, 'lead-manager') === false && $hook !== 'edit.php' && $hook !== 'post.php') {
            return;
        }

        wp_enqueue_style('lead-manager-admin', LMP_PLUGIN_URL . 'assets/css/admin.css', array(), LMP_VERSION);
        wp_enqueue_script('lead-manager-admin', LMP_PLUGIN_URL . 'assets/js/admin.js', array('jquery', 'chart-js'), LMP_VERSION, true);
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js', array(), '4.4.0', true);

        wp_localize_script('lead-manager-admin', 'lmpAdminData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('lmp_admin_nonce'),
        ));
    }

    /**
     * Enhanced contact form with file upload
     */
    public function contact_form_shortcode($atts)
    {
        $atts = shortcode_atts(array(
            'title' => __('Get in Touch', 'lead-manager-pro'),
            'subtitle' => __('We\'d love to hear from you', 'lead-manager-pro'),
            'show_file_upload' => 'yes',
            'show_budget' => 'yes',
            'show_timeline' => 'yes',
        ), $atts);

        ob_start();
        ?>
        <div class="lmp-contact-section" id="contact">
            <?php if ($atts['title']): ?>
                <h2 class="text-center lmp-fade-in"><?php echo esc_html($atts['title']); ?></h2>
            <?php endif; ?>

            <?php if ($atts['subtitle']): ?>
                <p class="text-center lmp-subtitle lmp-fade-in">
                    <?php echo esc_html($atts['subtitle']); ?>
                </p>
            <?php endif; ?>

            <form id="lmp-lead-form" class="lmp-contact-form glass-card" enctype="multipart/form-data">
                <?php wp_nonce_field('lmp_submit_lead', 'lmp_nonce'); ?>

                <div class="lmp-form-row">
                    <div class="lmp-form-group">
                        <label for="lmp_name">
                            <?php _e('Your Name', 'lead-manager-pro'); ?>
                            <span class="required">*</span>
                        </label>
                        <input type="text" id="lmp_name" name="lmp_name"
                            placeholder="<?php esc_attr_e('John Doe', 'lead-manager-pro'); ?>" required autocomplete="name"
                            class="lmp-input" />
                        <span class="lmp-error-message"></span>
                    </div>

                    <div class="lmp-form-group">
                        <label for="lmp_email">
                            <?php _e('Email Address', 'lead-manager-pro'); ?>
                            <span class="required">*</span>
                        </label>
                        <input type="email" id="lmp_email" name="lmp_email"
                            placeholder="<?php esc_attr_e('john@example.com', 'lead-manager-pro'); ?>" required
                            autocomplete="email" class="lmp-input" />
                        <span class="lmp-error-message"></span>
                    </div>
                </div>

                <div class="lmp-form-row">
                    <div class="lmp-form-group">
                        <label for="lmp_phone">
                            <?php _e('Phone Number', 'lead-manager-pro'); ?>
                        </label>
                        <input type="tel" id="lmp_phone" name="lmp_phone"
                            placeholder="<?php esc_attr_e('+1 (555) 123-4567', 'lead-manager-pro'); ?>" autocomplete="tel"
                            class="lmp-input" />
                    </div>

                    <div class="lmp-form-group">
                        <label for="lmp_company">
                            <?php _e('Company', 'lead-manager-pro'); ?>
                        </label>
                        <input type="text" id="lmp_company" name="lmp_company"
                            placeholder="<?php esc_attr_e('Your Company', 'lead-manager-pro'); ?>" autocomplete="organization"
                            class="lmp-input" />
                    </div>
                </div>

                <div class="lmp-form-group">
                    <label for="lmp_subject">
                        <?php _e('Subject', 'lead-manager-pro'); ?>
                    </label>
                    <input type="text" id="lmp_subject" name="lmp_subject"
                        placeholder="<?php esc_attr_e('Project Inquiry', 'lead-manager-pro'); ?>" class="lmp-input" />
                </div>

                <?php if ($atts['show_budget'] === 'yes'): ?>
                    <div class="lmp-form-row">
                        <div class="lmp-form-group">
                            <label for="lmp_budget">
                                <?php _e('Budget Range', 'lead-manager-pro'); ?>
                            </label>
                            <select id="lmp_budget" name="lmp_budget" class="lmp-input">
                                <option value=""><?php _e('Select budget range', 'lead-manager-pro'); ?></option>
                                <option value="< $5,000"><?php _e('< $5,000', 'lead-manager-pro'); ?></option>
                                <option value="$5,000 - $10,000"><?php _e('$5,000 - $10,000', 'lead-manager-pro'); ?></option>
                                <option value="$10,000 - $25,000"><?php _e('$10,000 - $25,000', 'lead-manager-pro'); ?></option>
                                <option value="$25,000 - $50,000"><?php _e('$25,000 - $50,000', 'lead-manager-pro'); ?></option>
                                <option value="$50,000+"><?php _e('$50,000+', 'lead-manager-pro'); ?></option>
                            </select>
                        </div>

                        <?php if ($atts['show_timeline'] === 'yes'): ?>
                            <div class="lmp-form-group">
                                <label for="lmp_timeline">
                                    <?php _e('Project Timeline', 'lead-manager-pro'); ?>
                                </label>
                                <select id="lmp_timeline" name="lmp_timeline" class="lmp-input">
                                    <option value=""><?php _e('Select timeline', 'lead-manager-pro'); ?></option>
                                    <option value="ASAP"><?php _e('ASAP', 'lead-manager-pro'); ?></option>
                                    <option value="1-3 months"><?php _e('1-3 months', 'lead-manager-pro'); ?></option>
                                    <option value="3-6 months"><?php _e('3-6 months', 'lead-manager-pro'); ?></option>
                                    <option value="6+ months"><?php _e('6+ months', 'lead-manager-pro'); ?></option>
                                    <option value="Just exploring"><?php _e('Just exploring', 'lead-manager-pro'); ?></option>
                                </select>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="lmp-form-group">
                    <label for="lmp_message">
                        <?php _e('Message', 'lead-manager-pro'); ?>
                        <span class="required">*</span>
                    </label>
                    <textarea id="lmp_message" name="lmp_message" rows="6"
                        placeholder="<?php esc_attr_e('Tell us about your project...', 'lead-manager-pro'); ?>" required
                        class="lmp-input" maxlength="2000"></textarea>
                    <div class="lmp-char-counter">
                        <span class="lmp-char-count">0</span> / 2000
                    </div>
                    <span class="lmp-error-message"></span>
                </div>

                <?php if ($atts['show_file_upload'] === 'yes'): ?>
                    <div class="lmp-form-group">
                        <label for="lmp_attachment">
                            <?php _e('Attachment (Optional)', 'lead-manager-pro'); ?>
                            <span
                                class="lmp-file-info"><?php _e('Max 5MB - PDF, DOC, DOCX, JPG, PNG', 'lead-manager-pro'); ?></span>
                        </label>
                        <div class="lmp-file-upload-wrapper">
                            <input type="file" id="lmp_attachment" name="lmp_attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                class="lmp-file-input" />
                            <label for="lmp_attachment" class="lmp-file-label">
                                <span class="lmp-file-icon">📎</span>
                                <span class="lmp-file-text"><?php _e('Choose file or drag here', 'lead-manager-pro'); ?></span>
                            </label>
                            <div class="lmp-file-preview" style="display: none;">
                                <span class="lmp-file-name"></span>
                                <button type="button" class="lmp-file-remove">×</button>
                            </div>
                        </div>
                        <span class="lmp-error-message"></span>
                    </div>
                <?php endif; ?>

                <div class="lmp-form-group lmp-checkbox-group">
                    <label class="lmp-checkbox-label">
                        <input type="checkbox" name="lmp_newsletter" id="lmp_newsletter" value="1">
                        <span><?php _e('Subscribe to our newsletter for updates', 'lead-manager-pro'); ?></span>
                    </label>
                </div>

                <button type="submit" class="btn btn--primary lmp-submit-btn">
                    <span class="lmp-btn-text"><?php _e('Send Message', 'lead-manager-pro'); ?></span>
                    <span class="lmp-btn-loader" style="display: none;">
                        <span class="lmp-spinner"></span>
                    </span>
                </button>

                <div class="lmp-message" style="display: none;"></div>

                <div class="lmp-progress-bar" style="display: none;">
                    <div class="lmp-progress-fill"></div>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * AJAX form submission handler
     */
    public function ajax_submit_lead()
    {
        check_ajax_referer('lmp_ajax_nonce', 'nonce');

        // Sanitize and validate
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $company = sanitize_text_field($_POST['company'] ?? '');
        $subject = sanitize_text_field($_POST['subject'] ?? '');
        $message = sanitize_textarea_field($_POST['message'] ?? '');
        $budget = sanitize_text_field($_POST['budget'] ?? '');
        $timeline = sanitize_text_field($_POST['timeline'] ?? '');
        $newsletter = isset($_POST['newsletter']) ? 1 : 0;

        // Validation
        if (empty($name) || empty($email) || empty($message)) {
            wp_send_json_error(array('message' => __('Please fill in all required fields.', 'lead-manager-pro')));
        }

        if (!is_email($email)) {
            wp_send_json_error(array('message' => __('Please enter a valid email address.', 'lead-manager-pro')));
        }

        // Handle file upload
        $attachment_id = 0;
        if (!empty($_FILES['attachment']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            $attachment_id = media_handle_upload('attachment', 0);

            if (is_wp_error($attachment_id)) {
                wp_send_json_error(array('message' => $attachment_id->get_error_message()));
            }
        }

        // Create lead
        $post_id = wp_insert_post(array(
            'post_type' => 'lead',
            'post_title' => $name . ' - ' . ($subject ?: __('Contact Form Submission', 'lead-manager-pro')),
            'post_status' => 'publish',
            'meta_input' => array(
                '_lmp_name' => $name,
                '_lmp_email' => $email,
                '_lmp_phone' => $phone,
                '_lmp_company' => $company,
                '_lmp_subject' => $subject,
                '_lmp_message' => $message,
                '_lmp_budget' => $budget,
                '_lmp_timeline' => $timeline,
                '_lmp_newsletter' => $newsletter,
                '_lmp_attachment_id' => $attachment_id,
                '_lmp_ip' => $this->get_client_ip(),
                '_lmp_user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                '_lmp_submitted_at' => current_time('mysql'),
                '_lmp_status' => 'new',
                '_lmp_source' => 'contact_form',
            ),
        ));

        if ($post_id) {
            // Send notification
            $this->send_notification_email($post_id, array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'company' => $company,
                'subject' => $subject,
                'message' => $message,
                'budget' => $budget,
                'timeline' => $timeline,
                'attachment_id' => $attachment_id,
            ));

            wp_send_json_success(array(
                'message' => __('Thank you! Your message has been sent successfully. We\'ll get back to you soon!', 'lead-manager-pro'),
            ));
        } else {
            wp_send_json_error(array('message' => __('Something went wrong. Please try again.', 'lead-manager-pro')));
        }
    }

    /**
     * Fallback non-AJAX submission
     */
    private function handle_form_submission()
    {
        // Similar to AJAX handler but with redirect
        // ... (implementation similar to above)
    }

    /**
     * Send enhanced notification email
     */
    private function send_notification_email($post_id, $data)
    {
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');

        $subject = sprintf(__('[%s] New Lead: %s', 'lead-manager-pro'), $site_name, $data['subject'] ?: 'Contact Form');

        // HTML email
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <meta charset="UTF-8">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                }

                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }

                .header {
                    background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
                    color: white;
                    padding: 30px;
                    text-align: center;
                    border-radius: 10px 10px 0 0;
                }

                .content {
                    background: #f9fafb;
                    padding: 30px;
                    border-radius: 0 0 10px 10px;
                }

                .field {
                    margin-bottom: 20px;
                }

                .label {
                    font-weight: bold;
                    color: #6366f1;
                    margin-bottom: 5px;
                }

                .value {
                    background: white;
                    padding: 10px;
                    border-radius: 5px;
                    border-left: 3px solid #6366f1;
                }

                .button {
                    display: inline-block;
                    background: #6366f1;
                    color: white;
                    padding: 12px 24px;
                    text-decoration: none;
                    border-radius: 5px;
                    margin-top: 20px;
                }

                .footer {
                    text-align: center;
                    margin-top: 20px;
                    color: #6b7280;
                    font-size: 12px;
                }
            </style>
        </head>

        <body>
            <div class="container">
                <div class="header">
                    <h1>🎉 New Lead Received!</h1>
                </div>
                <div class="content">
                    <div class="field">
                        <div class="label">Name:</div>
                        <div class="value"><?php echo esc_html($data['name']); ?></div>
                    </div>
                    <div class="field">
                        <div class="label">Email:</div>
                        <div class="value"><a
                                href="mailto:<?php echo esc_attr($data['email']); ?>"><?php echo esc_html($data['email']); ?></a>
                        </div>
                    </div>
                    <?php if ($data['phone']): ?>
                        <div class="field">
                            <div class="label">Phone:</div>
                            <div class="value"><a
                                    href="tel:<?php echo esc_attr($data['phone']); ?>"><?php echo esc_html($data['phone']); ?></a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($data['company']): ?>
                        <div class="field">
                            <div class="label">Company:</div>
                            <div class="value"><?php echo esc_html($data['company']); ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($data['budget']): ?>
                        <div class="field">
                            <div class="label">Budget:</div>
                            <div class="value"><?php echo esc_html($data['budget']); ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if ($data['timeline']): ?>
                        <div class="field">
                            <div class="label">Timeline:</div>
                            <div class="value"><?php echo esc_html($data['timeline']); ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="field">
                        <div class="label">Message:</div>
                        <div class="value"><?php echo nl2br(esc_html($data['message'])); ?></div>
                    </div>
                    <a href="<?php echo admin_url('post.php?post=' . $post_id . '&action=edit'); ?>" class="button">
                        View in Dashboard →
                    </a>
                </div>
                <div class="footer">
                    <p>This email was sent from <?php echo esc_html($site_name); ?></p>
                    <p>IP: <?php echo esc_html($this->get_client_ip()); ?> | Time: <?php echo current_time('Y-m-d H:i:s'); ?>
                    </p>
                </div>
            </div>
        </body>

        </html>
        <?php
        $message = ob_get_clean();

        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $site_name . ' <' . $admin_email . '>',
            'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>',
        );

        wp_mail($admin_email, $subject, $message, $headers);
    }

    /**
     * Get client IP
     */
    private function get_client_ip()
    {
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        }
        return sanitize_text_field($ip);
    }

    /**
     * Export leads to CSV
     */
    public function export_leads_csv()
    {
        check_ajax_referer('lmp_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $leads = get_posts(array(
            'post_type' => 'lead',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="leads-' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        // Headers
        fputcsv($output, array('Date', 'Name', 'Email', 'Phone', 'Company', 'Subject', 'Budget', 'Timeline', 'Status', 'Message'));

        foreach ($leads as $lead) {
            fputcsv($output, array(
                get_the_date('Y-m-d H:i:s', $lead->ID),
                get_post_meta($lead->ID, '_lmp_name', true),
                get_post_meta($lead->ID, '_lmp_email', true),
                get_post_meta($lead->ID, '_lmp_phone', true),
                get_post_meta($lead->ID, '_lmp_company', true),
                get_post_meta($lead->ID, '_lmp_subject', true),
                get_post_meta($lead->ID, '_lmp_budget', true),
                get_post_meta($lead->ID, '_lmp_timeline', true),
                get_post_meta($lead->ID, '_lmp_status', true),
                get_post_meta($lead->ID, '_lmp_message', true),
            ));
        }

        fclose($output);
        exit;
    }

    /**
     * Get lead statistics
     */
    public function get_lead_stats()
    {
        check_ajax_referer('lmp_admin_nonce', 'nonce');

        $stats = array(
            'total' => wp_count_posts('lead')->publish,
            'this_month' => $this->count_leads_this_month(),
            'this_week' => $this->count_leads_this_week(),
            'today' => $this->count_leads_today(),
            'by_status' => $this->count_leads_by_status(),
            'by_budget' => $this->count_leads_by_budget(),
            'chart_data' => $this->get_leads_chart_data(),
        );

        wp_send_json_success($stats);
    }

    private function count_leads_this_month()
    {
        $args = array(
            'post_type' => 'lead',
            'date_query' => array(
                array(
                    'after' => date('Y-m-01'),
                ),
            ),
        );
        return count(get_posts($args));
    }

    private function count_leads_this_week()
    {
        $args = array(
            'post_type' => 'lead',
            'date_query' => array(
                array(
                    'after' => '1 week ago',
                ),
            ),
        );
        return count(get_posts($args));
    }

    private function count_leads_today()
    {
        $args = array(
            'post_type' => 'lead',
            'date_query' => array(
                array(
                    'year' => date('Y'),
                    'month' => date('m'),
                    'day' => date('d'),
                ),
            ),
        );
        return count(get_posts($args));
    }

    private function count_leads_by_status()
    {
        // Implementation for status counting
        return array(
            'new' => 0,
            'contacted' => 0,
            'qualified' => 0,
            'converted' => 0,
        );
    }

    private function count_leads_by_budget()
    {
        // Implementation for budget counting
        return array();
    }

    private function get_leads_chart_data()
    {
        // Get last 30 days data
        $data = array();
        for ($i = 29; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $count = count(get_posts(array(
                'post_type' => 'lead',
                'date_query' => array(
                    array(
                        'year' => date('Y', strtotime($date)),
                        'month' => date('m', strtotime($date)),
                        'day' => date('d', strtotime($date)),
                    ),
                ),
            )));
            $data[] = array('date' => $date, 'count' => $count);
        }
        return $data;
    }

    /**
     * Admin menu
     */
    public function add_admin_menu()
    {
        add_menu_page(
            __('Lead Manager Pro', 'lead-manager-pro'),
            __('Leads', 'lead-manager-pro'),
            'manage_options',
            'lead-manager-dashboard',
            array($this, 'dashboard_page'),
            'dashicons-email',
            25
        );

        add_submenu_page(
            'lead-manager-dashboard',
            __('Dashboard', 'lead-manager-pro'),
            __('Dashboard', 'lead-manager-pro'),
            'manage_options',
            'lead-manager-dashboard',
            array($this, 'dashboard_page')
        );

        add_submenu_page(
            'lead-manager-dashboard',
            __('All Leads', 'lead-manager-pro'),
            __('All Leads', 'lead-manager-pro'),
            'manage_options',
            'edit.php?post_type=lead'
        );

        add_submenu_page(
            'lead-manager-dashboard',
            __('Settings', 'lead-manager-pro'),
            __('Settings', 'lead-manager-pro'),
            'manage_options',
            'lead-manager-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Dashboard page with analytics
     */
    public function dashboard_page()
    {
        $stats = array(
            'total' => wp_count_posts('lead')->publish,
            'this_month' => $this->count_leads_this_month(),
            'this_week' => $this->count_leads_this_week(),
            'today' => $this->count_leads_today(),
        );

        include LMP_PLUGIN_DIR . 'templates/dashboard.php';
    }

    /**
     * Settings page
     */
    public function settings_page()
    {
        include LMP_PLUGIN_DIR . 'templates/settings.php';
    }

    /**
     * Meta boxes
     */
    public function add_meta_boxes()
    {
        add_meta_box(
            'lmp_lead_details',
            __('Lead Details', 'lead-manager-pro'),
            array($this, 'lead_details_meta_box'),
            'lead',
            'normal',
            'high'
        );

        add_meta_box(
            'lmp_lead_status',
            __('Lead Status', 'lead-manager-pro'),
            array($this, 'lead_status_meta_box'),
            'lead',
            'side',
            'high'
        );
    }

    public function lead_details_meta_box($post)
    {
        include LMP_PLUGIN_DIR . 'templates/meta-box-details.php';
    }

    public function lead_status_meta_box($post)
    {
        include LMP_PLUGIN_DIR . 'templates/meta-box-status.php';
    }

    /**
     * Lead stats shortcode
     */
    public function lead_stats_shortcode($atts)
    {
        if (!current_user_can('manage_options')) {
            return '';
        }

        $stats = array(
            'total' => wp_count_posts('lead')->publish,
            'this_month' => $this->count_leads_this_month(),
        );

        ob_start();
        ?>
        <div class="lmp-stats-widget glass-card">
            <h3><?php _e('Lead Statistics', 'lead-manager-pro'); ?></h3>
            <div class="lmp-stats-grid">
                <div class="lmp-stat-item">
                    <div class="lmp-stat-value"><?php echo esc_html($stats['total']); ?></div>
                    <div class="lmp-stat-label"><?php _e('Total Leads', 'lead-manager-pro'); ?></div>
                </div>
                <div class="lmp-stat-item">
                    <div class="lmp-stat-value"><?php echo esc_html($stats['this_month']); ?></div>
                    <div class="lmp-stat-label"><?php _e('This Month', 'lead-manager-pro'); ?></div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize
Advanced_Lead_Manager_Pro::get_instance();
