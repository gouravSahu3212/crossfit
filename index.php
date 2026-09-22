<?php
/**
 * The main template file.
 *
 * Falls back here when no more specific template is found.
 * By existing as a .php file, it forces WordPress to use
 * classic template rendering instead of block templates.
 *
 * @package Codyweb_Child
 */

get_header(); ?>

<main id="main-content" class="site-main">
    <div class="page-width">

        <?php if ( have_posts() ) : ?>

            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    </header>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>

            <?php endwhile; ?>

            <?php the_posts_navigation(); ?>

        <?php else : ?>

            <p><?php esc_html_e( 'No content found.', 'codyweb-child' ); ?></p>

        <?php endif; ?>

    </div>
</main>

<?php get_footer();