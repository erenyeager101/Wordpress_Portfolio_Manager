<?php
/**
 * Template for displaying single posts
 *
 * @package Smart_Portfolio
 */

get_header();
?>

<?php while (have_posts()):
    the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
        <div class="container">
            <header class="entry-header" style="padding: 8rem 0 2rem; text-align: center;">
                <h1 class="entry-title">
                    <?php the_title(); ?>
                </h1>

                <div class="entry-meta" style="color: var(--color-text-muted); margin-top: 1rem;">
                    <span class="posted-on">
                        <?php echo get_the_date(); ?>
                    </span>
                    <span class="byline">
                        <?php _e('by', 'smart-portfolio'); ?>
                        <?php the_author(); ?>
                    </span>
                    <?php if (get_the_category()): ?>
                        <span class="categories">
                            <?php _e('in', 'smart-portfolio'); ?>
                            <?php the_category(', '); ?>
                        </span>
                    <?php endif; ?>
                </div>
            </header>

            <?php if (has_post_thumbnail()): ?>
                <div class="entry-featured-image" style="margin: 2rem 0; border-radius: var(--radius-xl); overflow: hidden;">
                    <?php the_post_thumbnail('project-hero'); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content glass-card" style="max-width: 800px; margin: 2rem auto; padding: 3rem;">
                <?php the_content(); ?>

                <?php
                wp_link_pages(array(
                    'before' => '<div class="page-links">' . __('Pages:', 'smart-portfolio'),
                    'after' => '</div>',
                ));
                ?>
            </div>

            <?php if (get_the_tags()): ?>
                <div class="entry-tags" style="max-width: 800px; margin: 2rem auto;">
                    <h3>
                        <?php _e('Tags', 'smart-portfolio'); ?>
                    </h3>
                    <?php the_tags('<div class="tag-list">', '', '</div>'); ?>
                </div>
            <?php endif; ?>

            <?php
            if (comments_open() || get_comments_number()):
                comments_template();
            endif;
            ?>
        </div>
    </article>
<?php endwhile; ?>

<?php get_footer(); ?>