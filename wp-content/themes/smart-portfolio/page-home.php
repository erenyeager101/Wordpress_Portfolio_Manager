<?php
/**
 * Template Name: Home Page
 * 
 * Custom home page template with projects and contact form
 *
 * @package Smart_Portfolio
 */

get_header();
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero__content">
        <h1 class="hero__title">Build Amazing Things</h1>
        <p class="hero__subtitle">Transforming ideas into beautiful, functional digital experiences</p>
        <div class="hero__cta">
            <a href="#projects" class="btn btn--primary">
                <span>View Our Work</span>
            </a>
            <a href="#contact" class="btn btn--secondary">
                <span>Start a Project</span>
            </a>
        </div>
    </div>
</section>

<!-- Projects Section -->
<section id="projects" class="section">
    <div class="container">
        <h2 class="text-center" style="margin-bottom: 1rem;">Featured Projects</h2>
        <p class="text-center"
            style="color: var(--color-text-secondary); margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto;">
            Explore our portfolio of successful projects and see how we've helped businesses achieve their goals.
        </p>

        <div class="projects-grid">
            <?php
            $projects = new WP_Query(array(
                'post_type' => 'project',
                'posts_per_page' => 6,
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            if ($projects->have_posts()):
                while ($projects->have_posts()):
                    $projects->the_post();
                    ?>
                    <article class="project-card reveal">
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
                                <?php foreach (array_slice($tech_array, 0, 3) as $tech): ?>
                                    <span class="project-card__tag">
                                        <?php echo esc_html($tech); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="project-card__footer">
                            <a href="<?php the_permalink(); ?>" class="btn btn--outline">
                                <span>Learn More</span>
                            </a>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                ?>
                <div class="text-center" style="grid-column: 1 / -1;">
                    <p>No projects yet. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center" style="margin-top: 3rem;">
            <a href="<?php echo get_post_type_archive_link('project'); ?>" class="btn btn--primary">
                <span>View All Projects</span>
            </a>
        </div>
    </div>
</section>

<!-- Blog Section -->
<section id="blog" class="section" style="background: var(--color-bg-secondary);">
    <div class="container">
        <h2 class="text-center" style="margin-bottom: 1rem;">Latest Insights</h2>
        <p class="text-center"
            style="color: var(--color-text-secondary); margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto;">
            Stay updated with our latest thoughts on design, development, and digital strategy.
        </p>

        <div class="blog-grid">
            <?php
            $blog_posts = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 3,
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            if ($blog_posts->have_posts()):
                while ($blog_posts->have_posts()):
                    $blog_posts->the_post();
                    ?>
                    <article class="blog-card reveal">
                        <?php if (has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('project-thumbnail', array('class' => 'blog-card__image')); ?>
                            </a>
                        <?php endif; ?>

                        <h3 class="blog-card__title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <div class="blog-card__meta">
                            <span class="blog-card__date">
                                <?php echo get_the_date(); ?>
                            </span>
                            <?php
                            $categories = get_the_category();
                            if ($categories) {
                                echo '<span class="blog-card__tag">' . esc_html($categories[0]->name) . '</span>';
                            }
                            ?>
                        </div>

                        <div class="blog-card__excerpt">
                            <?php the_excerpt(); ?>
                        </div>

                        <div class="blog-card__footer">
                            <a href="<?php the_permalink(); ?>" class="btn btn--outline">
                                <span>Read More</span>
                            </a>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else:
                ?>
                <div class="text-center" style="grid-column: 1 / -1;">
                    <p>No blog posts yet. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center" style="margin-top: 3rem;">
            <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="btn btn--primary">
                <span>View All Posts</span>
            </a>
        </div>
    </div>
</section>

<!-- Contact Section -->
<?php echo do_shortcode('[lead_form title="Let\'s Work Together" subtitle="Have a project in mind? We\'d love to hear about it."]'); ?>

<?php get_footer(); ?>