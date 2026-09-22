<?php
/**
 * Single template for cw_recommendation CPT
 *
 * @package Codyweb_Child
 */

get_header();
?>

<main id="primary" class="site-main inner-page single-recommendation">
    <div class="page-width">
        <?php
        while ( have_posts() ) :
            the_post();
            $icon        = get_post_meta( get_the_ID(), '_cw_rec_icon', true );
            $description = get_post_meta( get_the_ID(), '_cw_rec_description', true );
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'recommendation-article' ); ?>>
                <header class="page-hero" style="border-bottom: 1px solid var(--border); padding: 50px 0 35px;">
                    <?php if ( $icon ) : ?>
                        <div style="font-size: 42px; margin-bottom: 12px; line-height: 1;"><?php echo esc_html( $icon ); ?></div>
                    <?php endif; ?>
                    <p class="page-hero-label">Recommended Training</p>
                    <h1 class="page-hero-title"><?php the_title(); ?></h1>
                    <?php if ( $description ) : ?>
                        <p class="page-hero-desc"><?php echo esc_html( $description ); ?></p>
                    <?php endif; ?>
                </header>

                <div class="entry-content" style="font-size: 16px; line-height: 1.8; color: var(--text-muted); padding: 40px 0 20px;">
                    <?php the_content(); ?>
                </div>

                <div class="page-cta-banner" style="margin-top: 40px;">
                    <h2>Interested in <?php the_title(); ?>?</h2>
                    <p>Contact our coaching team or book your trial session to get started.</p>
                    <a href="/yhteystiedot" class="btn-gold">Get In Touch</a>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
