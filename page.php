<?php
/**
 * Template for displaying pages.
 *
 * @package Codyweb_Child
 */

get_header(); ?>

<main id="main-content" class="site-main">
    <div class="page-width">
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

    </div>
</main>

<?php get_footer();