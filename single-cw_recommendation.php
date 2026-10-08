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
                    <!-- <?php if ( $price ) : ?> -->
                        <!-- <div style="font-family: var(--font-head); font-size: 26px; font-weight: 800; color: var(--gold); margin-bottom: 14px;"><?php echo esc_html( $price ); ?></div> -->
                    <!-- <?php endif; ?> -->
                    <?php if ( $description ) : ?>
                        <p class="page-hero-desc"><?php echo esc_html( $description ); ?></p>
                    <?php endif; ?>
                    </div>
                </header>

                <!-- ==================== 1. HERO SECTION ==================== -->
                <?php
                $hero = cw_get_page_hero( get_the_ID() );
                if ( ! empty( $hero['show'] ) && ! empty( $hero['cards'] ) ) :
                    $col_count = count( $hero['cards'] );
                ?>
                    <section class="hp-hero hp-hero--cols-<?php echo esc_attr( $col_count ); ?>" style="--hero-cols: <?php echo esc_attr( $col_count ); ?>;">
                       <div class="page-width">
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
                       </div>
                    </section>
                <?php endif; ?>

                <!-- ==================== 2. CONTENT / SEO SECTION ==================== -->
                <?php
                $content_raw = get_the_content();
                if ( ! empty( $content_raw ) ) :
                    $full_content = apply_filters( 'the_content', $content_raw );

                    // Separate first paragraph/block from remaining content
                    $first_p  = '';
                    $rest_p   = '';
                    $has_more = false;

                    // 1. Support standard WordPress <!--more--> quicktag
                    if ( preg_match( '/<!--more(.*?)?-->/i', $full_content ) ) {
                        $parts    = preg_split( '/<!--more(.*?)?-->/i', $full_content, 2 );
                        $first_p  = trim( $parts[0] );
                        $rest_p   = trim( $parts[1] );
                        $has_more = ! empty( trim( strip_tags( $rest_p, '<img><iframe><video><audio>' ) ) );
                    }
                    // 2. Support initial container blocks such as <div ...>...</div>, <p ...>...</p>, or <section ...>...</section>
                    elseif ( preg_match( '#^(\s*<(div|p|section)\b[^>]*>.*?</\2>)(.*)$#is', trim( $full_content ), $matches ) && ! empty( trim( strip_tags( $matches[3], '<img><iframe><video><audio>' ) ) ) ) {
                        $first_p  = trim( $matches[1] );
                        $rest_p   = trim( $matches[3] );
                        $has_more = true;
                    }
                    // 3. Fallback: check for earliest closing </p> or </div> tag
                    else {
                        $pos_p   = stripos( $full_content, '</p>' );
                        $pos_div = stripos( $full_content, '</div>' );
                        $split_pos = false;
                        $tag_len   = 0;

                        if ( false !== $pos_p && false !== $pos_div ) {
                            if ( $pos_p < $pos_div ) {
                                $split_pos = $pos_p;
                                $tag_len   = 4;
                            } else {
                                $split_pos = $pos_div;
                                $tag_len   = 6;
                            }
                        } elseif ( false !== $pos_p ) {
                            $split_pos = $pos_p;
                            $tag_len   = 4;
                        } elseif ( false !== $pos_div ) {
                            $split_pos = $pos_div;
                            $tag_len   = 6;
                        }

                        if ( false !== $split_pos ) {
                            $temp_first = substr( $full_content, 0, $split_pos + $tag_len );
                            $temp_rest  = trim( substr( $full_content, $split_pos + $tag_len ) );
                            if ( ! empty( trim( strip_tags( $temp_rest, '<img><iframe><video><audio>' ) ) ) ) {
                                $first_p  = $temp_first;
                                $rest_p   = $temp_rest;
                                $has_more = true;
                            }
                        }

                        // 4. Fallback for plain text: split on first double newline
                        if ( ! $has_more && preg_match( '#^(.*?)(?:\r?\n\s*\r?\n)(.*)$#s', trim( $full_content ), $nl_matches ) ) {
                            $temp_rest = trim( $nl_matches[2] );
                            if ( ! empty( trim( strip_tags( $temp_rest, '<img><iframe><video><audio>' ) ) ) ) {
                                $first_p  = wpautop( trim( $nl_matches[1] ) );
                                $rest_p   = wpautop( $temp_rest );
                                $has_more = true;
                            }
                        }

                        // Fallback if no splitting occurred
                        if ( ! $has_more ) {
                            $first_p  = $full_content;
                            $has_more = false;
                        }
                    }
                ?>
                    <section class="hp-quote hp-content-section" id="recommendation-content">
                        <div class="page-width">
                            <h2 class="hp-quote-text">Interested in <?php the_title(); ?>?</h2>
                            <div class="hp-quote-body cw-read-more-container">
                                <div class="cw-content-excerpt">
                                    <?php echo $first_p; ?>
                                </div>
                                <?php if ( $has_more ) : ?>
                                    <div class="cw-content-rest">
                                        <?php echo $rest_p; ?>
                                    </div>
                                    <div class="cw-content-actions" style="margin-top: 28px;">
                                        <button type="button" class="btn-gold cw-read-more-btn">Read More</button>
                                        <button type="button" class="btn-gold cw-read-less-btn">Read Less</button>
                                    </div>
                                <?php endif; ?>
                            </div>
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
                <div class="page-width">
                    <?php echo cw_render_page_faq(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
