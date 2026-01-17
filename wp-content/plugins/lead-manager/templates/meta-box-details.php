<?php
/**
 * Lead Details Meta Box Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$name = get_post_meta($post->ID, '_lmp_name', true);
$email = get_post_meta($post->ID, '_lmp_email', true);
$phone = get_post_meta($post->ID, '_lmp_phone', true);
$company = get_post_meta($post->ID, '_lmp_company', true);
$subject = get_post_meta($post->ID, '_lmp_subject', true);
$message = get_post_meta($post->ID, '_lmp_message', true);
$budget = get_post_meta($post->ID, '_lmp_budget', true);
$timeline = get_post_meta($post->ID, '_lmp_timeline', true);
$newsletter = get_post_meta($post->ID, '_lmp_newsletter', true);
$ip = get_post_meta($post->ID, '_lmp_ip', true);
$user_agent = get_post_meta($post->ID, '_lmp_user_agent', true);
$submitted_at = get_post_meta($post->ID, '_lmp_submitted_at', true);
$attachment_id = get_post_meta($post->ID, '_lmp_attachment_id', true);
?>

<div class="lmp-meta-box">
    <div class="lmp-meta-box-field">
        <label>
            <?php _e('Name:', 'lead-manager-pro'); ?>
        </label>
        <div class="value"><strong>
                <?php echo esc_html($name); ?>
            </strong></div>
    </div>

    <div class="lmp-meta-box-field">
        <label>
            <?php _e('Email:', 'lead-manager-pro'); ?>
        </label>
        <div class="value">
            <a href="mailto:<?php echo esc_attr($email); ?>">
                <?php echo esc_html($email); ?>
            </a>
        </div>
    </div>

    <?php if ($phone): ?>
        <div class="lmp-meta-box-field">
            <label>
                <?php _e('Phone:', 'lead-manager-pro'); ?>
            </label>
            <div class="value">
                <a href="tel:<?php echo esc_attr($phone); ?>">
                    <?php echo esc_html($phone); ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($company): ?>
        <div class="lmp-meta-box-field">
            <label>
                <?php _e('Company:', 'lead-manager-pro'); ?>
            </label>
            <div class="value">
                <?php echo esc_html($company); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($subject): ?>
        <div class="lmp-meta-box-field">
            <label>
                <?php _e('Subject:', 'lead-manager-pro'); ?>
            </label>
            <div class="value">
                <?php echo esc_html($subject); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($budget): ?>
        <div class="lmp-meta-box-field">
            <label>
                <?php _e('Budget:', 'lead-manager-pro'); ?>
            </label>
            <div class="value">
                <?php echo esc_html($budget); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($timeline): ?>
        <div class="lmp-meta-box-field">
            <label>
                <?php _e('Timeline:', 'lead-manager-pro'); ?>
            </label>
            <div class="value">
                <?php echo esc_html($timeline); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="lmp-meta-box-field">
        <label>
            <?php _e('Message:', 'lead-manager-pro'); ?>
        </label>
        <div class="value">
            <?php echo nl2br(esc_html($message)); ?>
        </div>
    </div>

    <?php if ($attachment_id): ?>
        <div class="lmp-meta-box-field">
            <label>
                <?php _e('Attachment:', 'lead-manager-pro'); ?>
            </label>
            <div class="value">
                <a href="<?php echo wp_get_attachment_url($attachment_id); ?>" target="_blank">
                    <?php echo basename(get_attached_file($attachment_id)); ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($newsletter): ?>
        <div class="lmp-meta-box-field">
            <label>
                <?php _e('Newsletter:', 'lead-manager-pro'); ?>
            </label>
            <div class="value">✓
                <?php _e('Subscribed', 'lead-manager-pro'); ?>
            </div>
        </div>
    <?php endif; ?>

    <hr style="margin: 1.5rem 0;">

    <div class="lmp-meta-box-field">
        <label>
            <?php _e('IP Address:', 'lead-manager-pro'); ?>
        </label>
        <div class="value"><code><?php echo esc_html($ip); ?></code></div>
    </div>

    <div class="lmp-meta-box-field">
        <label>
            <?php _e('Submitted:', 'lead-manager-pro'); ?>
        </label>
        <div class="value">
            <?php echo esc_html($submitted_at); ?>
        </div>
    </div>

    <?php if ($user_agent): ?>
        <div class="lmp-meta-box-field">
            <label>
                <?php _e('User Agent:', 'lead-manager-pro'); ?>
            </label>
            <div class="value"><small>
                    <?php echo esc_html($user_agent); ?>
                </small></div>
        </div>
    <?php endif; ?>
</div>