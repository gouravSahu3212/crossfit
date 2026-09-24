<?php
/**
 * Shortcodes for Codyweb Child Theme.
 *
 * @package Codyweb_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shortcode to display upcoming courses and events from the "cw_recommendation" post type.
 *
 * Usage:
 *   [upcoming_events]
 *   [upcoming_events limit="6" columns="3" orderby="date" order="ASC" button_text="Read More" title="Current: passes and courses"]
 *   [cw_recommendations]
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cw_upcoming_events_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'limit'       => -1,
            'columns'     => '3',
            'orderby'     => 'date',
            'order'       => 'ASC',
            'button_text' => 'Read More',
            'title'       => 'Current: passes and courses',
        ),
        $atts,
        'upcoming_events'
    );

    $limit       = intval( $atts['limit'] );
    $columns     = in_array( $atts['columns'], array( '2', '3', '4' ), true ) ? $atts['columns'] : '3';
    $grid_class  = 'cards-grid-' . $columns;
    $button_text = sanitize_text_field( $atts['button_text'] );
    $title       = sanitize_text_field( $atts['title'] );

    $query = new WP_Query( array(
        'post_type'      => 'cw_recommendation',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'orderby'        => sanitize_key( $atts['orderby'] ),
        'order'          => strtoupper( $atts['order'] ) === 'DESC' ? 'DESC' : 'ASC',
    ) );

    ob_start();
    ?>
    <div class="page-width">
        <?php if ( ! empty( $title ) ) : ?>
            <div class="hp-passes-header">
                <h2 class="hp-section-title"><?php echo esc_html( $title ); ?></h2>
            </div>
        <?php endif; ?>
        <?php
        if ( $query->have_posts() ) :
            ?>
            <div class="<?php echo esc_attr( $grid_class ); ?>">
                <?php
                while ( $query->have_posts() ) :
                    $query->the_post();
                    $post_id     = get_the_ID();
                    $price       = get_post_meta( $post_id, '_cw_rec_price', true );
                    $tag         = get_post_meta( $post_id, '_cw_rec_tag', true );
                    $description = get_post_meta( $post_id, '_cw_rec_description', true );

                    if ( ! $description ) {
                        $description = get_the_excerpt();
                    }
                    ?>
                    <div class="event-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="event-card-thumb">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div>
                            <?php if ( ! empty( $tag ) ) : ?>
                                <span class="event-card-tag"><?php echo esc_html( $tag ); ?></span>
                            <?php endif; ?>
                            <h3 class="event-card-title"><?php the_title(); ?></h3>
                            <?php if ( ! empty( $description ) ) : ?>
                                <p class="event-card-desc"><?php echo esc_html( $description ); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="event-card-footer">
                            <span class="event-card-price"><?php echo esc_html( $price ); ?></span>
                            <a href="<?php the_permalink(); ?>" class="btn-gold"><?php echo esc_html( $button_text ); ?></a>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
            <?php
        else :
            ?>
            <p style="color: var(--text-muted); font-size: 15px; padding: 20px 0;">No upcoming courses or events found.</p>
            <?php
        endif;
        ?>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode( 'upcoming_events', 'cw_upcoming_events_shortcode' );
add_shortcode( 'cw_recommendations', 'cw_upcoming_events_shortcode' );

/**
 * Shortcode to display the Weekly Gym Schedule.
 *
 * Usage:
 *   [weekly_schedule]
 *   [weekly_schedule title="Weekly Schedule"]
 *   [weekly_schedule title="Weekly Schedule" subtitle="All classes for the week." label="Timetable"]
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cw_weekly_schedule_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'  => 'Weekly Schedule',
            'subtitle' => 'All classes for the week. Book your spot in WODconnect.',
            'label'    => 'Timetable',
        ),
        $atts,
        'weekly_schedule'
    );

    $heading  = $atts['title'];
    $subtitle = $atts['subtitle'];
    $label    = $atts['label'];

    $schedule = function_exists( 'cw_get_weekly_schedule' ) ? cw_get_weekly_schedule() : array();

    ob_start();
    ?>
    <section class="hp-schedule">
        <div class="page-width">
            <?php if ( ! empty( $heading ) || ! empty( $subtitle ) || ! empty( $label ) ) : ?>
                <div class="hp-schedule-header">
                    <?php if ( ! empty( $label ) ) : ?>
                        <p class="hp-section-label"><?php echo esc_html( $label ); ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $heading ) ) : ?>
                        <h2 class="hp-section-title"><?php echo esc_html( $heading ); ?></h2>
                    <?php endif; ?>
                    <?php if ( ! empty( $subtitle ) ) : ?>
                        <p class="hp-section-sub"><?php echo esc_html( $subtitle ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="hp-schedule-grid">
                <?php
                if ( ! empty( $schedule ) ) :
                    foreach ( $schedule as $day_key => $day ) :
                        $day_name = isset( $day['name'] ) ? $day['name'] : ucfirst( $day_key );
                        $slots    = isset( $day['slots'] ) && is_array( $day['slots'] ) ? $day['slots'] : array();
                        ?>
                        <div class="hp-day">
                            <div class="hp-day-name"><?php echo esc_html( $day_name ); ?></div>
                            <div class="hp-day-slots">
                                <?php
                                if ( ! empty( $slots ) ) :
                                    foreach ( $slots as $slot ) :
                                        $time  = isset( $slot['time'] ) ? $slot['time'] : '';
                                        $class = isset( $slot['class'] ) ? $slot['class'] : '';
                                        ?>
                                        <div class="hp-slot">
                                            <?php if ( ! empty( $time ) ) : ?>
                                                <span class="hp-slot-time"><?php echo esc_html( $time ); ?></span>
                                            <?php endif; ?>
                                            <?php if ( ! empty( $class ) ) : ?>
                                                <span class="hp-slot-class"><?php echo esc_html( $class ); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php
                                    endforeach;
                                else :
                                    ?>
                                    <div class="hp-slot">
                                        <span class="hp-slot-class" style="color: var(--text-muted); font-size: 12px;">No classes</span>
                                    </div>
                                    <?php
                                endif;
                                ?>
                            </div>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>
    <?php

    return ob_get_clean();
}
add_shortcode( 'weekly_schedule', 'cw_weekly_schedule_shortcode' );

/**
 * Shortcode to display Pricing Plans.
 *
 * Usage:
 *   [pricing_table]
 *   [pricing_table title="Pricing" label="Plans"]
 *   [pricing_table title="Pricing" columns="4" link_more_text="View all pricing" link_more_url="/hinnasto"]
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cw_pricing_table_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'title'          => '',
            'heading'        => '',
            'subtitle'       => '',
            'label'          => '',
            'columns'        => '4',
            'link_more_text' => '',
            'link_more_url'  => '',
        ),
        $atts,
        'pricing_table'
    );

    $title          = ! empty( $atts['heading'] ) ? $atts['heading'] : $atts['title'];
    $subtitle       = $atts['subtitle'];
    $label          = $atts['label'];
    $columns        = in_array( $atts['columns'], array( '2', '3', '4' ), true ) ? $atts['columns'] : '4';
    $link_more_text = $atts['link_more_text'];
    $link_more_url  = $atts['link_more_url'];

    $plans = function_exists( 'cw_get_pricing_plans' ) ? cw_get_pricing_plans() : array();

    ob_start();
    ?>
    <div class="pricing-table-container">
        <?php if ( ! empty( $title ) || ! empty( $label ) || ! empty( $subtitle ) ) : ?>
            <div class="hp-pricing-header" style="margin-bottom: 40px;">
                <?php if ( ! empty( $label ) ) : ?>
                    <p class="hp-section-label"><?php echo esc_html( $label ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $title ) ) : ?>
                    <h2 class="hp-section-title"><?php echo esc_html( $title ); ?></h2>
                <?php endif; ?>
                <?php if ( ! empty( $subtitle ) ) : ?>
                    <p class="hp-section-sub"><?php echo esc_html( $subtitle ); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $plans ) ) : ?>
            <div class="pricing-grid cards-grid-<?php echo esc_attr( $columns ); ?>">
                <?php
                foreach ( $plans as $plan ) :
                    $is_featured = ( ! empty( $plan['featured'] ) && 'yes' === $plan['featured'] );
                    $card_class  = 'pricing-card' . ( $is_featured ? ' pricing-card--popular' : '' );
                    $badge       = ! empty( $plan['badge'] ) ? $plan['badge'] : '';

                    // Button style
                    $btn_style = ! empty( $plan['btn_style'] ) ? $plan['btn_style'] : 'auto';
                    if ( 'gold' === $btn_style ) {
                        $btn_class = 'btn-gold';
                    } elseif ( 'outline' === $btn_style ) {
                        $btn_class = 'btn-outline';
                    } else {
                        $btn_class = $is_featured ? 'btn-gold' : 'btn-outline';
                    }

                    // Features list
                    $features_raw = ! empty( $plan['features'] ) ? $plan['features'] : '';
                    $features     = array_filter( array_map( 'trim', explode( "\n", str_replace( "\r", '', $features_raw ) ) ) );
                    ?>
                    <div class="<?php echo esc_attr( $card_class ); ?>">
                        <?php if ( ! empty( $badge ) ) : ?>
                            <span class="pricing-badge"><?php echo esc_html( $badge ); ?></span>
                        <?php endif; ?>

                        <h3 class="pricing-card-title"><?php echo esc_html( $plan['title'] ); ?></h3>

                        <div class="pricing-card-price">
                            <?php echo esc_html( $plan['price'] ); ?>
                            <?php if ( ! empty( $plan['period'] ) ) : ?>
                                <span><?php echo esc_html( $plan['period'] ); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if ( ! empty( $features ) ) : ?>
                            <ul class="pricing-card-features">
                                <?php foreach ( $features as $feature ) : ?>
                                    <li><?php echo esc_html( $feature ); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php
                        if ( ! empty( $plan['btn_text'] ) && ! empty( $plan['btn_url'] ) ) :
                            $is_external = ( 0 === strpos( $plan['btn_url'], 'http' ) && false === strpos( $plan['btn_url'], home_url() ) );
                            ?>
                            <a href="<?php echo esc_url( $plan['btn_url'] ); ?>"
                               <?php echo $is_external ? 'target="_blank" rel="noreferrer"' : ''; ?>
                               class="<?php echo esc_attr( $btn_class ); ?>">
                                <?php echo esc_html( $plan['btn_text'] ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p style="color: var(--text-muted); font-size: 15px; padding: 20px 0;">No pricing plans found.</p>
        <?php endif; ?>

        <?php if ( ! empty( $link_more_text ) && ! empty( $link_more_url ) ) : ?>
            <div class="hp-pricing-link" style="text-align: center; margin-top: 36px;">
                <a href="<?php echo esc_url( $link_more_url ); ?>" class="btn-outline"><?php echo esc_html( $link_more_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode( 'pricing_table', 'cw_pricing_table_shortcode' );
add_shortcode( 'cw_pricing', 'cw_pricing_table_shortcode' );
add_shortcode( 'pricing_plans', 'cw_pricing_table_shortcode' );

/**
 * Shortcode to display the FAQ section for the current page or specified page ID.
 *
 * Usage:
 *   [page_faq]
 *   [page_faq id="123"]
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output.
 */
function cw_page_faq_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'id' => 0,
        ),
        $atts,
        'page_faq'
    );

    $post_id = ! empty( $atts['id'] ) ? intval( $atts['id'] ) : get_the_ID();
    if ( function_exists( 'cw_render_page_faq' ) ) {
        return cw_render_page_faq( $post_id );
    }
    return '';
}
add_shortcode( 'page_faq', 'cw_page_faq_shortcode' );
add_shortcode( 'cw_faq', 'cw_page_faq_shortcode' );
add_shortcode( 'faq_section', 'cw_page_faq_shortcode' );



