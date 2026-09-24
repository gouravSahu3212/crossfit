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
    <section class="hp-pricing">
        <div class="page-width">
            <div class="hp-pricing-header">
                <p class="hp-section-label">Plans</p>
                <h2 class="hp-section-title">Pricing</h2>
            </div>
            <div class="hp-pricing-grid">

                <div class="hp-price-card hp-price-card--featured">
                    <h3 class="hp-price-name">Full membership</h3>
                    <p class="hp-price-amount">89 &euro; <span>/ kk</span></p>
                    <ul class="hp-price-features">
                        <li>Unlimited classes</li>
                        <li>CrossFit, HYROX &amp; Easy WOD</li>
                        <li>Open gym access</li>
                    </ul>
                </div>

                <div class="hp-price-card">
                    <h3 class="hp-price-name">10-session pass</h3>
                    <p class="hp-price-amount">139 &euro;</p>
                    <ul class="hp-price-features">
                        <li>10 class visits</li>
                        <li>Valid for 3 months</li>
                        <li>All class types</li>
                    </ul>
                </div>

                <div class="hp-price-card">
                    <h3 class="hp-price-name">Student / senior</h3>
                    <p class="hp-price-amount">69 &euro; <span>/ kk</span></p>
                    <ul class="hp-price-features">
                        <li>Unlimited classes</li>
                        <li>Valid ID required</li>
                        <li>Open gym access</li>
                    </ul>
                </div>

                <div class="hp-price-card">
                    <h3 class="hp-price-name">Drop-in</h3>
                    <p class="hp-price-amount">20 &euro;</p>
                    <ul class="hp-price-features">
                        <li>One single class</li>
                        <li>Visiting athletes welcome</li>
                        <li>Book in advance</li>
                    </ul>
                </div>

            </div>
            <div class="hp-pricing-link">
                <a href="/hinnasto" class="btn-outline">View all pricing</a>
            </div>
        </div>
    </section>

    <!-- ==================== 6. ABOUT / COMMUNITY ==================== -->
    <section class="hp-about">
        <div class="page-width">
            <div class="hp-about-inner">
                <p class="hp-section-label">Our story</p>
                <h2 class="hp-section-title">Training at CrossFit Kouvola</h2>
                <p class="hp-about-body">Our gym has been part of the Kouvola community since 2013. Everything we do is built around coached group classes, honest work and a room where people know your name.</p>
            </div>
        </div>
    </section>

    <!-- ==================== 7. FAQ ==================== -->
    <?php echo cw_render_page_faq(); ?>


</main>

<?php get_footer();