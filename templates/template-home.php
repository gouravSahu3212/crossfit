<?php
/*
Template Name: Home Page
*/

get_header();
?>

<main id="primary" class="site-main home-page">

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


</main>

<?php get_footer();