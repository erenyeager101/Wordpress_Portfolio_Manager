<?php
/**
 * Settings Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap lmp-settings">
    <h1>
        <?php _e('Lead Manager Pro Settings', 'lead-manager-pro'); ?>
    </h1>

    <div class="lmp-settings-grid">
        <div class="lmp-settings-main">
            <div class="lmp-card">
                <div class="lmp-card-header">
                    <h2>
                        <?php _e('Form Settings', 'lead-manager-pro'); ?>
                    </h2>
                </div>
                <div class="lmp-card-body">
                    <p>
                        <?php _e('Configure your contact form settings and notifications.', 'lead-manager-pro'); ?>
                    </p>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label>
                                    <?php _e('Email Notifications', 'lead-manager-pro'); ?>
                                </label>
                            </th>
                            <td>
                                <p class="description">
                                    <?php _e('Notifications are sent to:', 'lead-manager-pro'); ?>
                                    <strong>
                                        <?php echo get_option('admin_email'); ?>
                                    </strong>
                                </p>
                                <p class="description">
                                    <?php _e('Change this in Settings → General', 'lead-manager-pro'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label>
                                    <?php _e('Form Shortcode', 'lead-manager-pro'); ?>
                                </label>
                            </th>
                            <td>
                                <code>[lead_form]</code>
                                <p class="description">
                                    <?php _e('Use this shortcode to display the contact form on any page.', 'lead-manager-pro'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="lmp-card">
                <div class="lmp-card-header">
                    <h2>
                        <?php _e('Shortcode Options', 'lead-manager-pro'); ?>
                    </h2>
                </div>
                <div class="lmp-card-body">
                    <h3>
                        <?php _e('Basic Usage', 'lead-manager-pro'); ?>
                    </h3>
                    <div class="lmp-shortcode-box">
                        <code>[lead_form]</code>
                        <button class="button lmp-copy-shortcode" data-shortcode="[lead_form]">
                            <?php _e('Copy', 'lead-manager-pro'); ?>
                        </button>
                    </div>

                    <h3>
                        <?php _e('With Custom Title', 'lead-manager-pro'); ?>
                    </h3>
                    <div class="lmp-shortcode-box">
                        <code>[lead_form title="Contact Us" subtitle="Get in touch"]</code>
                        <button class="button lmp-copy-shortcode"
                            data-shortcode='[lead_form title="Contact Us" subtitle="Get in touch"]'>
                            <?php _e('Copy', 'lead-manager-pro'); ?>
                        </button>
                    </div>

                    <h3>
                        <?php _e('Without File Upload', 'lead-manager-pro'); ?>
                    </h3>
                    <div class="lmp-shortcode-box">
                        <code>[lead_form show_file_upload="no"]</code>
                        <button class="button lmp-copy-shortcode" data-shortcode='[lead_form show_file_upload="no"]'>
                            <?php _e('Copy', 'lead-manager-pro'); ?>
                        </button>
                    </div>

                    <h3>
                        <?php _e('Simple Form (No Budget/Timeline)', 'lead-manager-pro'); ?>
                    </h3>
                    <div class="lmp-shortcode-box">
                        <code>[lead_form show_budget="no" show_timeline="no"]</code>
                        <button class="button lmp-copy-shortcode"
                            data-shortcode='[lead_form show_budget="no" show_timeline="no"]'>
                            <?php _e('Copy', 'lead-manager-pro'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="lmp-settings-sidebar">
            <div class="lmp-card">
                <div class="lmp-card-header">
                    <h2>
                        <?php _e('Quick Stats', 'lead-manager-pro'); ?>
                    </h2>
                </div>
                <div class="lmp-card-body">
                    <?php
                    $total = wp_count_posts('lead')->publish;
                    $this_month = 0; // You can implement this
                    ?>
                    <div class="lmp-stat-item">
                        <div class="lmp-stat-value">
                            <?php echo esc_html($total); ?>
                        </div>
                        <div class="lmp-stat-label">
                            <?php _e('Total Leads', 'lead-manager-pro'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lmp-card">
                <div class="lmp-card-header">
                    <h2>
                        <?php _e('Support', 'lead-manager-pro'); ?>
                    </h2>
                </div>
                <div class="lmp-card-body">
                    <p>
                        <?php _e('Need help? Check out these resources:', 'lead-manager-pro'); ?>
                    </p>
                    <ul style="list-style: disc; padding-left: 1.5rem;">
                        <li><a href="#">
                                <?php _e('Documentation', 'lead-manager-pro'); ?>
                            </a></li>
                        <li><a href="#">
                                <?php _e('Support Forum', 'lead-manager-pro'); ?>
                            </a></li>
                        <li><a href="#">
                                <?php _e('Report a Bug', 'lead-manager-pro'); ?>
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .lmp-settings-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-top: 2rem;
    }

    @media (max-width: 1024px) {
        .lmp-settings-grid {
            grid-template-columns: 1fr;
        }
    }
</style>