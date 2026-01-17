<?php
/**
 * Template for displaying project archive
 *
 * @package Smart_Portfolio
 */

get_header();
?>

<section class="hero" style="min-height: 50vh;">
    <div class="hero__content">
        <h1 class="hero__title">
            <?php _e('Our Projects', 'smart-portfolio'); ?>
        </h1>
        <p class="hero__subtitle">
            <?php _e('Explore our portfolio of amazing work', 'smart-portfolio'); ?>
        </p>
    </div>
</section>

<section id="projects" class="section">
    <div class="container">
        <?php if (have_posts()): ?>
            <div class="projects-grid">
                <?php while (have_posts()):
                    the_post(); ?>
                    <article id="project-<?php the_ID(); ?>" <?php post_class('project-card reveal'); ?>>
                        <?php if (has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('project-thumbnail', array('class' => 'project-card__image')); ?>
                            </a>
                        <?php endif; ?>

                        <h3 class="project-card__title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <div class="project-card__excerpt">
                            <?php the_excerpt(); ?>
                        </div>

                        <?php
                        $technologies = get_post_meta(get_the_ID(), '_technologies', true);
                        if ($technologies):
                            $tech_array = array_map('trim', explode(',', $technologies));
                            ?>
                            <div class="project-card__meta">
                                <?php foreach ($tech_array as $tech): ?>
                                    <span class="project-card__tag">
                                        <?php echo esc_html($tech); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="project-card__footer">
                            <a href="<?php the_permalink(); ?>" class="btn btn--outline">
                                <span>
                                    <?php _e('View Project', 'smart-portfolio'); ?>
                                </span>
                            </a>

                            <?php
                            $project_url = get_post_meta(get_the_ID(), '_project_url', true);
                            if ($project_url):
                                ?>
                                <a href="<?php echo esc_url($project_url); ?>" class="btn btn--secondary" target="_blank"
                                    rel="noopener">
                                    <span>
                                        <?php _e('Live Demo', 'smart-portfolio'); ?>
                                    </span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('&laquo; Previous', 'smart-portfolio'),
                'next_text' => __('Next &raquo;', 'smart-portfolio'),
            ));
            ?>
        <?php else: ?>
            <div class="text-center">
                <h2>
                    <?php _e('No projects found', 'smart-portfolio'); ?>
                </h2>
                <p>
                    <?php _e('Check back soon for new projects!', 'smart-portfolio'); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>