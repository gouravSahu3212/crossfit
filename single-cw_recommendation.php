<?php
/**
 * Single template for cw_recommendation CPT
 *
 * @package Codyweb_Child
 */

get_header();
?>

<main id="primary" class="site-main inner-page single-recommendation">
    <div class="cat-banner" >
        <?php
        while ( have_posts() ) :
            the_post();
            $icon        = get_post_meta( get_the_ID(), '_cw_rec_icon', true );
            $description = get_post_meta( get_the_ID(), '_cw_rec_description', true );
            $price       = get_post_meta( get_the_ID(), '_cw_rec_price', true );
            $tag         = get_post_meta( get_the_ID(), '_cw_rec_tag', true );
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'recommendation-article' ); ?>>
                <header class="page-hero" style="background-image:url(<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>)">
                    <div class="page-width">
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
                    </div>
                </header>
                <?php if ( get_the_content() ) : ?>
                <div class="entry-content" style="font-size: 16px; line-height: 1.8; color: var(--text-muted); padding: 40px 0 20px;">
                    <!--<?php if ( has_post_thumbnail() ) : ?>-->
                    <!--    <div class="recommendation-featured-media" style="margin-bottom: 30px; border-radius: var(--radius); overflow: hidden;">-->
                            <!--<?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; max-height:460px; object-fit:cover; display:block;' ) ); ?>-->
                    <!--    </div>-->
                    <!--<?php endif; ?>-->
                    <?php the_content(); ?>
                </div>
                <?php endif; ?>

                <div class="page-cta-banner page-width" style="margin-top: 40px;">
                    <h2>Interested in <?php the_title(); ?>?</h2>
                    <p>Contact our coaching team or book your trial session to get started.</p>
                    <a href="/yhteystiedot" class="btn-gold">Get In Touch</a>
                </div>

                <!-- ==================== 1. HERO SECTION ==================== -->
                <?php
                $hero = cw_get_page_hero( get_the_ID() );
                if ( ! empty( $hero['show'] ) && ! empty( $hero['cards'] ) ) :
                    $col_count = count( $hero['cards'] );
                ?>
                    <section class="hp-hero hp-hero--cols-<?php echo esc_attr( $col_count ); ?>" style="--hero-cols: <?php echo esc_attr( $col_count ); ?>;">
                        <?php foreach ( $hero['cards'] as $index => $card ) :
                            $bg_img = ! empty( $card['image'] ) ? $card['image'] : '';
                            $panel_class = ( 0 === $index ) ? 'hp-hero-panel--hyrox' : ( ( 1 === $index ) ? 'hp-hero-panel--crossfit' : '' );
                        ?>
                            <div class="hp-hero-panel <?php echo esc_attr( $panel_class ); ?>" <?php echo ! empty( $bg_img ) ? 'style="--panel-bg: url(' . esc_url( $bg_img ) . ');"' : ''; ?>>
                                <?php if ( ! empty( $card['heading'] ) ) : ?>
                                    <h2 class="hp-hero-heading"><?php echo esc_html( $card['heading'] ); ?></h2>
                                <?php endif; ?>
                                <?php if ( ! empty( $card['description'] ) ) : ?>
                                    <p class="hp-hero-desc"><?php echo esc_html( $card['description'] ); ?></p>
                                <?php endif; ?>
                                <?php if ( ! empty( $card['btn_text'] ) && ! empty( $card['btn_url'] ) ) : ?>
                                    <a href="<?php echo esc_url( $card['btn_url'] ); ?>" class="btn-gold"><?php echo esc_html( $card['btn_text'] ); ?></a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </section>
                <?php endif; ?>

                <!-- ==================== 2. RICH TEXT SECTION ==================== -->
                <?php
                $rich_text = cw_get_page_rich_text( get_the_ID() );
                if ( ! empty( $rich_text['show'] ) && ( ! empty( $rich_text['heading'] ) || ! empty( $rich_text['description'] ) ) ) :
                ?>
                    <section class="hp-quote hp-rich-text">
                        <div class="page-width">
                            <?php if ( ! empty( $rich_text['heading'] ) ) : ?>
                                <h2 class="hp-quote-text"><?php echo esc_html( $rich_text['heading'] ); ?></h2>
                            <?php endif; ?>
                            <?php if ( ! empty( $rich_text['description'] ) ) : ?>
                                <div class="hp-quote-body"><?php echo wp_kses_post( wpautop( $rich_text['description'] ) ); ?></div>
                            <?php endif; ?>
                            <?php if ( ! empty( $rich_text['btn_text'] ) && ! empty( $rich_text['btn_url'] ) ) : ?>
                                <div class="hp-quote-cta" style="margin-top: 28px;">
                                    <a href="<?php echo esc_url( $rich_text['btn_url'] ); ?>" class="btn-gold"><?php echo esc_html( $rich_text['btn_text'] ); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- ==================== 3. CURRENT PASSES & COURSES ==================== -->
                <section class="hp-passes">
                    <?php echo do_shortcode( '[upcoming_events]' ); ?>
                </section>

                <!-- ==================== 4. WEEKLY SCHEDULE ==================== -->
                <?php echo do_shortcode( '[weekly_schedule]' ); ?>

                <!-- ==================== 5. PRICING ==================== -->
                <section class="hp-pricing page-section">
                    <?php echo do_shortcode( '[pricing_table]' ); ?>
                </section>

                <!-- ==================== 6. ABOUT / COMMUNITY ==================== -->
                <?php echo cw_render_page_about(); ?>


                <!-- ==================== 7. FAQ ==================== -->
                <?php echo cw_render_page_faq(); ?>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
