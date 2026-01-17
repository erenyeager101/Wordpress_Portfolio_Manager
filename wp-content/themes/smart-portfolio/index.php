<?php
/**
 * The main template file
 *
 * @package Smart_Portfolio
 */

get_header();
?>

<section class="hero">
    <div class="hero__content">
        <h1 class="hero__title">
            <?php bloginfo('name'); ?>
        </h1>
        <p class="hero__subtitle">
            <?php bloginfo('description'); ?>
        </p>
        <div class="hero__cta">
            <a href="#projects" class="btn btn--primary">
                <span>
                    <?php _e('View Projects', 'smart-portfolio'); ?>
                </span>
            </a>
            <a href="#contact" class="btn btn--secondary">
                <span>
                    <?php _e('Get in Touch', 'smart-portfolio'); ?>
                </span>
            </a>
        </div>
    </div>
</section>

<?php if (have_posts()): ?>
    <section id="blog" class="section">
        <div class="container">
            <h2 class="text-center">
                <?php _e('Latest Blog Posts', 'smart-portfolio'); ?>
            </h2>

            <div class="blog-grid">
                <?php while (have_posts()):
                    the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-card reveal'); ?>>
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
                                foreach ($categories as $category) {
                                    echo '<span class="blog-card__tag">' . esc_html($category->name) . '</span>';
                                }
                            }
                            ?>
                        </div>

                        <div class="blog-card__excerpt">
                            <?php the_excerpt(); ?>
                        </div>

                        <div class="blog-card__footer">
                            <a href="<?php the_permalink(); ?>" class="btn btn--outline">
                                <span>
                                    <?php _e('Read More', 'smart-portfolio'); ?>
                                </span>
                            </a>
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
        </div>
    </section>
<?php else: ?>
    <section class="section">
        <div class="container text-center">
            <h2>
                <?php _e('No posts found', 'smart-portfolio'); ?>
            </h2>
            <p>
                <?php _e('It seems we can\'t find what you\'re looking for.', 'smart-portfolio'); ?>
            </p>
        </div>
    </section>
<?php endif; ?>

<?php get_footer(); ?>