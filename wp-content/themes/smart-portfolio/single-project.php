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
            <div class="project-hero">
                <?php the_post_thumbnail('project-hero'); ?>
                <div class="project-hero__overlay">
                    <div class="container">
                        <h1 class="project-hero__title"><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="section">
            <div class="container project-grid">
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
                            <div class="detail-item">
                                <strong><?php _e('Client:', 'smart-portfolio'); ?></strong>
                                <p class="mb-0"><?php echo esc_html($client); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($completion_date) : ?>
                            <div class="detail-item">
                                <strong><?php _e('Completed:', 'smart-portfolio'); ?></strong>
                                <p class="mb-0"><?php echo esc_html(date('F Y', strtotime($completion_date))); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($technologies) : ?>
                            <div class="detail-item">
                                <strong><?php _e('Technologies:', 'smart-portfolio'); ?></strong>
                                <div class="tech-tags">
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
                            <div class="project-links">
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
                        <div class="glass-card mt-4" style="margin-top: 2rem;">
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
