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
 *   [upcoming_events limit="6" columns="3" orderby="date" order="ASC" button_text="Read More"]
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
        ),
        $atts,
        'upcoming_events'
    );

    $limit       = intval( $atts['limit'] );
    $columns     = in_array( $atts['columns'], array( '2', '3', '4' ), true ) ? $atts['columns'] : '3';
    $grid_class  = 'cards-grid-' . $columns;
    $button_text = sanitize_text_field( $atts['button_text'] );

    $query = new WP_Query( array(
        'post_type'      => 'cw_recommendation',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'orderby'        => sanitize_key( $atts['orderby'] ),
        'order'          => strtoupper( $atts['order'] ) === 'DESC' ? 'DESC' : 'ASC',
    ) );

    ob_start();

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

    return ob_get_clean();
}
add_shortcode( 'upcoming_events', 'cw_upcoming_events_shortcode' );
add_shortcode( 'cw_recommendations', 'cw_upcoming_events_shortcode' );
