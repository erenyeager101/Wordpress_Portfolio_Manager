<?php
/**
 * Template for displaying single projects
 *
 * @package Smart_Portfolio
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <article id="project-<?php the_ID(); ?>" <?php post_class('single-project'); ?>>
        <?php if (has_post_thumbnail()) : ?>
            <div class="project-hero" style="width: 100%; height: 60vh; position: relative; overflow: hidden;">
                <?php the_post_thumbnail('project-hero', array('style' => 'width: 100%; height: 100%; object-fit: cover;')); ?>
                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, transparent, var(--color-bg-primary)); display: flex; align-items: flex-end; padding: 3rem;">
                    <div class="container">
                        <h1 class="entry-title" style="color: white; font-size: 4rem; margin: 0;"><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="container" style="padding: 3rem 1.5rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; max-width: 1400px; margin: 0 auto;">
                <div class="project-content">
                    <div class="entry-content glass-card">
                        <?php the_content(); ?>
                    </div>
                </div>
                
                <aside class="project-sidebar">
                    <div class="glass-card">
                        <h3><?php _e('Project Details', 'smart-portfolio'); ?></h3>
                        
                        <?php
                        $client = get_post_meta(get_the_ID(), '_client', true);
                        $completion_date = get_post_meta(get_the_ID(), '_completion_date', true);
                        $technologies = get_post_meta(get_the_ID(), '_technologies', true);
                        $project_url = get_post_meta(get_the_ID(), '_project_url', true);
                        $github_url = get_post_meta(get_the_ID(), '_github_url', true);
                        ?>
                        
                        <?php if ($client) : ?>
                            <div class="detail-item" style="margin-bottom: 1.5rem;">
                                <strong><?php _e('Client:', 'smart-portfolio'); ?></strong>
                                <p><?php echo esc_html($client); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($completion_date) : ?>
                            <div class="detail-item" style="margin-bottom: 1.5rem;">
                                <strong><?php _e('Completed:', 'smart-portfolio'); ?></strong>
                                <p><?php echo esc_html(date('F Y', strtotime($completion_date))); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($technologies) : ?>
                            <div class="detail-item" style="margin-bottom: 1.5rem;">
                                <strong><?php _e('Technologies:', 'smart-portfolio'); ?></strong>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                                    <?php
                                    $tech_array = array_map('trim', explode(',', $technologies));
                                    foreach ($tech_array as $tech) :
                                    ?>
                                        <span class="project-card__tag"><?php echo esc_html($tech); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($project_url || $github_url) : ?>
                            <div class="project-links" style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 2rem;">
                                <?php if ($project_url) : ?>
                                    <a href="<?php echo esc_url($project_url); ?>" class="btn btn--primary" target="_blank" rel="noopener">
                                        <span><?php _e('View Live Site', 'smart-portfolio'); ?></span>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($github_url) : ?>
                                    <a href="<?php echo esc_url($github_url); ?>" class="btn btn--secondary" target="_blank" rel="noopener">
                                        <span><?php _e('View on GitHub', 'smart-portfolio'); ?></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php
                    $terms = get_the_terms(get_the_ID(), 'project_category');
                    if ($terms && !is_wp_error($terms)) :
                    ?>
                        <div class="glass-card" style="margin-top: 1.5rem;">
                            <h4><?php _e('Categories', 'smart-portfolio'); ?></h4>
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                <?php foreach ($terms as $term) : ?>
                                    <a href="<?php echo get_term_link($term); ?>" class="project-card__tag">
                                        <?php echo esc_html($term->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </article>
<?php endwhile; ?>

<?php get_footer(); ?>
