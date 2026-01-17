<?php
/**
 * Lead Status Meta Box Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$status = get_post_meta($post->ID, '_lmp_status', true) ?: 'new';
?>

<div class="lmp-meta-box">
    <div class="lmp-meta-box-field">
        <label for="lmp_status">
            <?php _e('Lead Status:', 'lead-manager-pro'); ?>
        </label>
        <select id="lmp_status" name="lmp_status" class="lmp-status-select" data-post-id="<?php echo $post->ID; ?>">
            <option value="new" <?php selected($status, 'new'); ?>>
                <?php _e('New', 'lead-manager-pro'); ?>
            </option>
            <option value="contacted" <?php selected($status, 'contacted'); ?>>
                <?php _e('Contacted', 'lead-manager-pro'); ?>
            </option>
            <option value="qualified" <?php selected($status, 'qualified'); ?>>
                <?php _e('Qualified', 'lead-manager-pro'); ?>
            </option>
            <option value="converted" <?php selected($status, 'converted'); ?>>
                <?php _e('Converted', 'lead-manager-pro'); ?>
            </option>
            <option value="lost" <?php selected($status, 'lost'); ?>>
                <?php _e('Lost', 'lead-manager-pro'); ?>
            </option>
        </select>
    </div>

    <div class="lmp-meta-box-field" style="margin-top: 1.5rem;">
        <label>
            <?php _e('Quick Actions:', 'lead-manager-pro'); ?>
        </label>
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <a href="mailto:<?php echo esc_attr(get_post_meta($post->ID, '_lmp_email', true)); ?>"
                class="button button-secondary" style="text-align: center;">
                <span class="dashicons dashicons-email" style="vertical-align: middle;"></span>
                <?php _e('Send Email', 'lead-manager-pro'); ?>
            </a>
            <?php
            $phone = get_post_meta($post->ID, '_lmp_phone', true);
            if ($phone):
                ?>
                <a href="tel:<?php echo esc_attr($phone); ?>" class="button button-secondary" style="text-align: center;">
                    <span class="dashicons dashicons-phone" style="vertical-align: middle;"></span>
                    <?php _e('Call', 'lead-manager-pro'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>