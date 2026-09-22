<?php
/**
 * Register the cw_recommendation Custom Post Type and its meta boxes.
 *
 * @package Codyweb_Child
 */

/**
 * Register cw_recommendation CPT.
 */
function cw_register_recommendation_cpt() {
    $labels = array(
        'name'               => 'Recommendations',
        'singular_name'      => 'Recommendation',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Recommendation',
        'edit_item'          => 'Edit Recommendation',
        'new_item'           => 'New Recommendation',
        'view_item'          => 'View Recommendation',
        'search_items'       => 'Search Recommendations',
        'not_found'          => 'No recommendations found',
        'not_found_in_trash' => 'No recommendations found in Trash',
        'menu_name'          => 'Recommendations',
        'all_items'          => 'All Recommendations',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_icon'          => 'dashicons-awards',
        'menu_position'      => 31,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'            => array( 'slug' => 'recommendation', 'with_front' => false ),
    );

    register_post_type( 'cw_recommendation', $args );

    // Flush rewrite rules once so single post permalinks work immediately
    if ( ! get_option( 'cw_rec_cpt_flushed_v1' ) ) {
        flush_rewrite_rules();
        update_option( 'cw_rec_cpt_flushed_v1', 1 );
    }
}
add_action( 'init', 'cw_register_recommendation_cpt' );

/**
 * Add Recommendation Details meta box.
 */
function cw_add_recommendation_meta_boxes() {
    add_meta_box(
        'cw_recommendation_details',
        'Recommendation Details',
        'cw_render_recommendation_meta_box',
        'cw_recommendation',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cw_add_recommendation_meta_boxes' );

/**
 * Render the Recommendation Details meta box.
 *
 * @param WP_Post $post The current post object.
 */
function cw_render_recommendation_meta_box( $post ) {
    wp_nonce_field( 'cw_recommendation_save_meta', 'cw_recommendation_nonce' );

    $description = get_post_meta( $post->ID, '_cw_rec_description', true );
    // $url         = get_post_meta( $post->ID, '_cw_rec_url', true );
    $icon        = get_post_meta( $post->ID, '_cw_rec_icon', true );
    ?>
    <style>
        #cw_recommendation_details table { width:100%; border-collapse:collapse; }
        #cw_recommendation_details th { width:160px; padding:12px 8px; text-align:left; font-weight:600; vertical-align:top; color:#374151; }
        #cw_recommendation_details td { padding:10px 8px; }
        #cw_recommendation_details textarea,
        #cw_recommendation_details input[type="url"] { width:100%; }
        #cw_recommendation_details .description { color:#6b7280; font-size:12px; margin-top:4px; }
        #cw_recommendation_details .cw-icon-input { width:80px; font-size:20px; text-align:center; }
    </style>
    <table>
        <tbody>
            <tr>
                <th><label for="cw_rec_description">Description</label></th>
                <td>
                    <textarea id="cw_rec_description" name="cw_rec_description" rows="4"><?php echo esc_textarea( $description ); ?></textarea>
                    <p class="description">Shown to the visitor in the chatbot result card.</p>
                </td>
            </tr>
            <!-- <tr>
                <th><label for="cw_rec_url">Link URL</label></th>
                <td>
                    <input type="url" id="cw_rec_url" name="cw_rec_url"
                           value="<?php // echo esc_attr( $url ); ?>"
                           placeholder="https://example.com/training-page" />
                    <p class="description">The page visitors will be sent to via the CTA button.</p>
                </td>
            </tr> -->
            <tr>
                <th><label for="cw_rec_icon">Icon / Emoji</label></th>
                <td>
                    <input type="text" id="cw_rec_icon" name="cw_rec_icon" class="cw-icon-input"
                           value="<?php echo esc_attr( $icon ); ?>" placeholder="&#x1F3CB;" />
                    <p class="description">Optional emoji displayed on the chatbot result card.</p>
                </td>
            </tr>
        </tbody>
    </table>
    <?php
}

/**
 * Save the Recommendation meta box data.
 *
 * @param int $post_id The post ID.
 */
function cw_save_recommendation_meta( $post_id ) {
    if ( ! isset( $_POST['cw_recommendation_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['cw_recommendation_nonce'], 'cw_recommendation_save_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['cw_rec_description'] ) ) {
        update_post_meta( $post_id, '_cw_rec_description', sanitize_textarea_field( wp_unslash( $_POST['cw_rec_description'] ) ) );
    }
    // if ( isset( $_POST['cw_rec_url'] ) ) {
    //     update_post_meta( $post_id, '_cw_rec_url', esc_url_raw( wp_unslash( $_POST['cw_rec_url'] ) ) );
    // }
    if ( isset( $_POST['cw_rec_icon'] ) ) {
        update_post_meta( $post_id, '_cw_rec_icon', sanitize_text_field( wp_unslash( $_POST['cw_rec_icon'] ) ) );
    }
}
add_action( 'save_post_cw_recommendation', 'cw_save_recommendation_meta' );
