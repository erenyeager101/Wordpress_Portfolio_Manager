<?php
/**
 * Admin Dashboard Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap lmp-dashboard">
    <h1 class="lmp-dashboard-title">
        <?php _e('Lead Manager Pro Dashboard', 'lead-manager-pro'); ?>
        <span class="lmp-version">v2.0.0</span>
    </h1>

    <!-- Stats Cards -->
    <div class="lmp-stats-grid">
        <div class="lmp-stat-card lmp-stat-card--primary">
            <div class="lmp-stat-icon">📊</div>
            <div class="lmp-stat-content">
                <div class="lmp-stat-value">
                    <?php echo esc_html($stats['total']); ?>
                </div>
                <div class="lmp-stat-label">
                    <?php _e('Total Leads', 'lead-manager-pro'); ?>
                </div>
            </div>
        </div>

        <div class="lmp-stat-card lmp-stat-card--success">
            <div class="lmp-stat-icon">📅</div>
            <div class="lmp-stat-content">
                <div class="lmp-stat-value">
                    <?php echo esc_html($stats['this_month']); ?>
                </div>
                <div class="lmp-stat-label">
                    <?php _e('This Month', 'lead-manager-pro'); ?>
                </div>
            </div>
        </div>

        <div class="lmp-stat-card lmp-stat-card--info">
            <div class="lmp-stat-icon">📆</div>
            <div class="lmp-stat-content">
                <div class="lmp-stat-value">
                    <?php echo esc_html($stats['this_week']); ?>
                </div>
                <div class="lmp-stat-label">
                    <?php _e('This Week', 'lead-manager-pro'); ?>
                </div>
            </div>
        </div>

        <div class="lmp-stat-card lmp-stat-card--warning">
            <div class="lmp-stat-icon">⏰</div>
            <div class="lmp-stat-content">
                <div class="lmp-stat-value">
                    <?php echo esc_html($stats['today']); ?>
                </div>
                <div class="lmp-stat-label">
                    <?php _e('Today', 'lead-manager-pro'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="lmp-dashboard-row">
        <div class="lmp-dashboard-col-8">
            <div class="lmp-card">
                <div class="lmp-card-header">
                    <h2>
                        <?php _e('Leads Over Time (Last 30 Days)', 'lead-manager-pro'); ?>
                    </h2>
                </div>
                <div class="lmp-card-body">
                    <canvas id="lmp-leads-chart" height="80"></canvas>
                </div>
            </div>
        </div>

        <div class="lmp-dashboard-col-4">
            <div class="lmp-card">
                <div class="lmp-card-header">
                    <h2>
                        <?php _e('Lead Sources', 'lead-manager-pro'); ?>
                    </h2>
                </div>
                <div class="lmp-card-body">
                    <canvas id="lmp-sources-chart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Leads -->
    <div class="lmp-card">
        <div class="lmp-card-header">
            <h2>
                <?php _e('Recent Leads', 'lead-manager-pro'); ?>
            </h2>
            <div class="lmp-card-actions">
                <a href="<?php echo admin_url('edit.php?post_type=lead'); ?>" class="button">
                    <?php _e('View All', 'lead-manager-pro'); ?>
                </a>
                <button id="lmp-export-leads" class="button button-primary">
                    <?php _e('Export CSV', 'lead-manager-pro'); ?>
                </button>
            </div>
        </div>
        <div class="lmp-card-body">
            <?php
            $recent_leads = get_posts(array(
                'post_type' => 'lead',
                'posts_per_page' => 10,
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            if ($recent_leads):
                ?>
                <table class="wp-list-table widefat fixed striped lmp-leads-table">
                    <thead>
                        <tr>
                            <th>
                                <?php _e('Date', 'lead-manager-pro'); ?>
                            </th>
                            <th>
                                <?php _e('Name', 'lead-manager-pro'); ?>
                            </th>
                            <th>
                                <?php _e('Email', 'lead-manager-pro'); ?>
                            </th>
                            <th>
                                <?php _e('Company', 'lead-manager-pro'); ?>
                            </th>
                            <th>
                                <?php _e('Budget', 'lead-manager-pro'); ?>
                            </th>
                            <th>
                                <?php _e('Status', 'lead-manager-pro'); ?>
                            </th>
                            <th>
                                <?php _e('Actions', 'lead-manager-pro'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_leads as $lead):
                            $name = get_post_meta($lead->ID, '_lmp_name', true);
                            $email = get_post_meta($lead->ID, '_lmp_email', true);
                            $company = get_post_meta($lead->ID, '_lmp_company', true);
                            $budget = get_post_meta($lead->ID, '_lmp_budget', true);
                            $status = get_post_meta($lead->ID, '_lmp_status', true) ?: 'new';
                            ?>
                            <tr>
                                <td>
                                    <?php echo get_the_date('M j, Y', $lead->ID); ?>
                                </td>
                                <td><strong>
                                        <?php echo esc_html($name); ?>
                                    </strong></td>
                                <td><a href="mailto:<?php echo esc_attr($email); ?>">
                                        <?php echo esc_html($email); ?>
                                    </a></td>
                                <td>
                                    <?php echo esc_html($company ?: '—'); ?>
                                </td>
                                <td>
                                    <?php echo esc_html($budget ?: '—'); ?>
                                </td>
                                <td>
                                    <span class="lmp-status-badge lmp-status-<?php echo esc_attr($status); ?>">
                                        <?php echo esc_html(ucfirst($status)); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo get_edit_post_link($lead->ID); ?>" class="button button-small">
                                        <?php _e('View', 'lead-manager-pro'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="lmp-empty-state">
                    <div class="lmp-empty-icon">📭</div>
                    <h3>
                        <?php _e('No leads yet', 'lead-manager-pro'); ?>
                    </h3>
                    <p>
                        <?php _e('Leads will appear here once visitors submit the contact form.', 'lead-manager-pro'); ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="lmp-dashboard-row">
        <div class="lmp-dashboard-col-6">
            <div class="lmp-card">
                <div class="lmp-card-header">
                    <h2>
                        <?php _e('Quick Actions', 'lead-manager-pro'); ?>
                    </h2>
                </div>
                <div class="lmp-card-body">
                    <div class="lmp-quick-actions">
                        <a href="<?php echo admin_url('edit.php?post_type=lead'); ?>" class="lmp-quick-action">
                            <span class="dashicons dashicons-list-view"></span>
                            <?php _e('View All Leads', 'lead-manager-pro'); ?>
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=lead-manager-settings'); ?>"
                            class="lmp-quick-action">
                            <span class="dashicons dashicons-admin-settings"></span>
                            <?php _e('Settings', 'lead-manager-pro'); ?>
                        </a>
                        <a href="#" id="lmp-export-leads-action" class="lmp-quick-action">
                            <span class="dashicons dashicons-download"></span>
                            <?php _e('Export Leads', 'lead-manager-pro'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="lmp-dashboard-col-6">
            <div class="lmp-card">
                <div class="lmp-card-header">
                    <h2>
                        <?php _e('Shortcode', 'lead-manager-pro'); ?>
                    </h2>
                </div>
                <div class="lmp-card-body">
                    <p>
                        <?php _e('Use this shortcode to display the contact form:', 'lead-manager-pro'); ?>
                    </p>
                    <div class="lmp-shortcode-box">
                        <code>[lead_form]</code>
                        <button class="button lmp-copy-shortcode" data-shortcode="[lead_form]">
                            <?php _e('Copy', 'lead-manager-pro'); ?>
                        </button>
                    </div>
                    <p class="description">
                        <?php _e('You can also customize it:', 'lead-manager-pro'); ?>
                    </p>
                    <div class="lmp-shortcode-box">
                        <code>[lead_form title="Contact Us" subtitle="Get in touch"]</code>
                        <button class="button lmp-copy-shortcode"
                            data-shortcode='[lead_form title="Contact Us" subtitle="Get in touch"]'>
                            <?php _e('Copy', 'lead-manager-pro'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>