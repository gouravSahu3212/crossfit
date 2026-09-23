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
            $price       = get_post_meta( get_the_ID(), '_cw_rec_price', true );
            $tag         = get_post_meta( get_the_ID(), '_cw_rec_tag', true );
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'recommendation-article' ); ?>>
                <header class="page-hero" style="border-bottom: 1px solid var(--border); padding: 50px 0 35px;">
                    <?php if ( $icon ) : ?>
                        <div style="font-size: 42px; margin-bottom: 12px; line-height: 1;"><?php echo esc_html( $icon ); ?></div>
                    <?php endif; ?>
                    <?php if ( $tag ) : ?>
                        <span class="event-card-tag" style="margin-bottom: 12px;"><?php echo esc_html( $tag ); ?></span>
                    <?php else : ?>
                        <p class="page-hero-label">Recommended Training</p>
                    <?php endif; ?>
                    <h1 class="page-hero-title"><?php the_title(); ?></h1>
                    <?php if ( $price ) : ?>
                        <div style="font-family: var(--font-head); font-size: 26px; font-weight: 800; color: var(--gold); margin-bottom: 14px;"><?php echo esc_html( $price ); ?></div>
                    <?php endif; ?>
                    <?php if ( $description ) : ?>
                        <p class="page-hero-desc"><?php echo esc_html( $description ); ?></p>
                    <?php endif; ?>
                </header>

                <div class="entry-content" style="font-size: 16px; line-height: 1.8; color: var(--text-muted); padding: 40px 0 20px;">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="recommendation-featured-media" style="margin-bottom: 30px; border-radius: var(--radius); overflow: hidden;">
                            <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; max-height:460px; object-fit:cover; display:block;' ) ); ?>
                        </div>
                    <?php endif; ?>
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
