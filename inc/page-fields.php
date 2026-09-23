<?php
/**
 * Page Custom Meta Boxes & Fields.
 *
 * Implements custom fields for WordPress Pages, starting with the Hero Section.
 *
 * @package Codyweb_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register custom meta boxes on pages.
 */
function cw_register_page_meta_boxes() {
    // 1. Hero Section
    add_meta_box(
        'cw_page_hero_section',
        __( 'Hero Section Settings', 'codyweb-child' ),
        'cw_render_page_hero_meta_box',
        'page',
        'normal',
        'high'
    );

    // 2. Rich Text Section
    add_meta_box(
        'cw_page_rich_text_section',
        __( 'Rich Text Section Settings', 'codyweb-child' ),
        'cw_render_page_rich_text_meta_box',
        'page',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'cw_register_page_meta_boxes' );

/**
 * Enqueue WordPress Media scripts and styles for page admin.
 *
 * @param string $hook The current admin page hook.
 */
function cw_page_hero_admin_assets( $hook ) {
    global $post;
    if ( ( 'post.php' === $hook || 'post-new.php' === $hook ) && isset( $post ) && 'page' === $post->post_type ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'cw_page_hero_admin_assets' );

/**
 * Render the Hero Section meta box.
 *
 * @param WP_Post $post Current post object.
 */
function cw_render_page_hero_meta_box( $post ) {
    wp_nonce_field( 'cw_page_hero_save_meta', 'cw_page_hero_nonce' );

    $show       = get_post_meta( $post->ID, '_cw_hero_show', true );
    $show       = ( '' === $show ) ? 'yes' : $show; // Default to 'yes'
    $card_count = intval( get_post_meta( $post->ID, '_cw_hero_card_count', true ) );
    if ( $card_count < 1 || $card_count > 4 ) {
        $card_count = 2; // Default to 2 cards
    }

    $cards_data = get_post_meta( $post->ID, '_cw_hero_cards', true );
    if ( ! is_array( $cards_data ) ) {
        $cards_data = array();
    }
    ?>
    <style>
        .cw-hero-meta-wrap {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            color: #1e293b;
        }
        .cw-meta-row {
            display: flex;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .cw-meta-label {
            width: 200px;
            font-weight: 600;
            font-size: 14px;
            color: #334155;
            flex-shrink: 0;
        }
        .cw-meta-field {
            flex-grow: 1;
        }
        .cw-meta-field select,
        .cw-meta-field input[type="text"],
        .cw-meta-field input[type="url"],
        .cw-meta-field textarea {
            width: 100%;
            max-width: 520px;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 14px;
            background: #fff;
        }
        .cw-radio-group {
            display: flex;
            gap: 24px;
            align-items: center;
        }
        .cw-radio-group label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            cursor: pointer;
        }
        .cw-cards-container {
            margin-top: 24px;
        }
        .cw-card-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 18px;
            transition: border-color 0.2s ease;
        }
        .cw-card-box.is-hidden {
            display: none !important;
        }
        .cw-card-box-header {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cw-card-box-header span.cw-badge {
            background: #e2e8f0;
            color: #475569;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: 0.05em;
        }
        .cw-img-preview-box {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 6px;
        }
        .cw-img-thumb {
            width: 120px;
            height: 75px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            background: #e2e8f0;
            display: block;
        }
        .cw-img-thumb.no-img {
            display: none;
        }
        .cw-help-text {
            color: #64748b;
            font-size: 12px;
            margin-top: 4px;
        }
    </style>

    <div class="cw-hero-meta-wrap">

        <!-- 1. SHOW HERO (YES / NO) -->
        <div class="cw-meta-row">
            <div class="cw-meta-label">
                <label><?php esc_html_e( 'Show Hero Section', 'codyweb-child' ); ?></label>
            </div>
            <div class="cw-meta-field">
                <div class="cw-radio-group">
                    <label>
                        <input type="radio" name="cw_hero_show" value="yes" <?php checked( $show, 'yes' ); ?>>
                        <span><?php esc_html_e( 'Yes (Show)', 'codyweb-child' ); ?></span>
                    </label>
                    <label>
                        <input type="radio" name="cw_hero_show" value="no" <?php checked( $show, 'no' ); ?>>
                        <span><?php esc_html_e( 'No (Hide)', 'codyweb-child' ); ?></span>
                    </label>
                </div>
                <p class="cw-help-text"><?php esc_html_e( 'Select whether to display the hero section on this page.', 'codyweb-child' ); ?></p>
            </div>
        </div>

        <div id="cw-hero-settings-body" style="<?php echo ( 'no' === $show ) ? 'opacity: 0.5;' : ''; ?>">

            <!-- 2. NUMBER OF CARDS -->
            <div class="cw-meta-row">
                <div class="cw-meta-label">
                    <label for="cw_hero_card_count"><?php esc_html_e( 'Number of Cards', 'codyweb-child' ); ?></label>
                </div>
                <div class="cw-meta-field">
                    <select id="cw_hero_card_count" name="cw_hero_card_count" style="max-width: 240px;">
                        <option value="1" <?php selected( $card_count, 1 ); ?>>1 Card</option>
                        <option value="2" <?php selected( $card_count, 2 ); ?>>2 Cards (Split Hero)</option>
                        <option value="3" <?php selected( $card_count, 3 ); ?>>3 Cards</option>
                        <option value="4" <?php selected( $card_count, 4 ); ?>>4 Cards</option>
                    </select>
                    <p class="cw-help-text"><?php esc_html_e( 'Choose how many cards to display in the hero section.', 'codyweb-child' ); ?></p>
                </div>
            </div>

            <!-- 3. CARDS INPUTS -->
            <div class="cw-cards-container">
                <?php
                for ( $i = 1; $i <= 4; $i++ ) :
                    $card_index = $i - 1;
                    $card = isset( $cards_data[ $card_index ] ) && is_array( $cards_data[ $card_index ] ) ? $cards_data[ $card_index ] : array();
                    $img_url     = isset( $card['image'] ) ? $card['image'] : '';
                    $heading     = isset( $card['heading'] ) ? $card['heading'] : '';
                    $description = isset( $card['description'] ) ? $card['description'] : '';
                    $btn_text    = isset( $card['btn_text'] ) ? $card['btn_text'] : '';
                    $btn_url     = isset( $card['btn_url'] ) ? $card['btn_url'] : '';
                    $is_hidden   = ( $i > $card_count );
                    ?>
                    <div class="cw-card-box <?php echo $is_hidden ? 'is-hidden' : ''; ?>" data-card-num="<?php echo esc_attr( $i ); ?>">
                        <div class="cw-card-box-header">
                            <span><?php printf( esc_html__( 'Card #%d', 'codyweb-child' ), $i ); ?></span>
                            <span class="cw-badge"><?php echo ( 1 === $i ) ? 'Panel 1' : 'Panel ' . $i; ?></span>
                        </div>

                        <!-- Card Image -->
                        <div class="cw-meta-row" style="border-top: none;">
                            <div class="cw-meta-label">
                                <label><?php esc_html_e( 'Card Image', 'codyweb-child' ); ?></label>
                            </div>
                            <div class="cw-meta-field">
                                <input type="hidden"
                                       class="cw-img-url-input"
                                       name="cw_hero_cards[<?php echo esc_attr( $card_index ); ?>][image]"
                                       value="<?php echo esc_attr( $img_url ); ?>">
                                
                                <div class="cw-img-preview-box">
                                    <img src="<?php echo esc_url( $img_url ); ?>"
                                         class="cw-img-thumb <?php echo empty( $img_url ) ? 'no-img' : ''; ?>"
                                         alt="Card Image Preview">
                                    <div>
                                        <button type="button" class="button cw-upload-img-btn">
                                            <?php echo empty( $img_url ) ? esc_html__( 'Select Image', 'codyweb-child' ) : esc_html__( 'Change Image', 'codyweb-child' ); ?>
                                        </button>
                                        <button type="button" class="button-link-delete cw-remove-img-btn" style="<?php echo empty( $img_url ) ? 'display:none;' : 'margin-left:8px;'; ?>">
                                            <?php esc_html_e( 'Remove', 'codyweb-child' ); ?>
                                        </button>
                                    </div>
                                </div>
                                <p class="cw-help-text"><?php esc_html_e( 'Upload or select a high-resolution background photo for this hero card.', 'codyweb-child' ); ?></p>
                            </div>
                        </div>

                        <!-- Card Heading -->
                        <div class="cw-meta-row">
                            <div class="cw-meta-label">
                                <label><?php esc_html_e( 'Card Heading', 'codyweb-child' ); ?></label>
                            </div>
                            <div class="cw-meta-field">
                                <input type="text"
                                       name="cw_hero_cards[<?php echo esc_attr( $card_index ); ?>][heading]"
                                       value="<?php echo esc_attr( $heading ); ?>"
                                       placeholder="<?php esc_attr_e( 'e.g. Start HYROX or Start CrossFit', 'codyweb-child' ); ?>">
                            </div>
                        </div>

                        <!-- Card Description -->
                        <div class="cw-meta-row">
                            <div class="cw-meta-label">
                                <label><?php esc_html_e( 'Card Description', 'codyweb-child' ); ?></label>
                            </div>
                            <div class="cw-meta-field">
                                <textarea name="cw_hero_cards[<?php echo esc_attr( $card_index ); ?>][description]"
                                          rows="3"
                                          placeholder="<?php esc_attr_e( 'Optional subtext or summary for this card...', 'codyweb-child' ); ?>"><?php echo esc_textarea( $description ); ?></textarea>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="cw-meta-row" style="border-bottom: none;">
                            <div class="cw-meta-label">
                                <label><?php esc_html_e( 'CTA Button', 'codyweb-child' ); ?></label>
                            </div>
                            <div class="cw-meta-field">
                                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                                    <input type="text"
                                           name="cw_hero_cards[<?php echo esc_attr( $card_index ); ?>][btn_text]"
                                           value="<?php echo esc_attr( $btn_text ); ?>"
                                           placeholder="<?php esc_attr_e( 'Button Text (e.g. Read more)', 'codyweb-child' ); ?>"
                                           style="flex: 1; min-width: 160px;">
                                    <input type="text"
                                           name="cw_hero_cards[<?php echo esc_attr( $card_index ); ?>][btn_url]"
                                           value="<?php echo esc_attr( $btn_url ); ?>"
                                           placeholder="<?php esc_attr_e( 'Button Link (e.g. /hyrox)', 'codyweb-child' ); ?>"
                                           style="flex: 2; min-width: 220px;">
                                </div>
                                <p class="cw-help-text"><?php esc_html_e( 'Enter the label and target URL for the gold call-to-action button.', 'codyweb-child' ); ?></p>
                            </div>
                        </div>

                    </div>
                <?php endfor; ?>
            </div>

        </div>

    </div>

    <!-- Admin Interactive Script for Hero Meta Box -->
    <script>
    (function($){
        $(document).ready(function(){
            // 1. Show/Hide Cards based on Number of Cards selector
            $('#cw_hero_card_count').on('change', function(){
                var count = parseInt($(this).val(), 10) || 1;
                $('.cw-card-box').each(function(){
                    var cardNum = parseInt($(this).attr('data-card-num'), 10);
                    if (cardNum <= count) {
                        $(this).removeClass('is-hidden').fadeIn(150);
                    } else {
                        $(this).addClass('is-hidden').hide();
                    }
                });
            });

            // 2. Dim/Enable hero settings based on Show radio
            $('input[name="cw_hero_show"]').on('change', function(){
                if ($(this).val() === 'no') {
                    $('#cw-hero-settings-body').css('opacity', '0.5');
                } else {
                    $('#cw-hero-settings-body').css('opacity', '1');
                }
            });

            // 3. Media Uploader for Card Images
            $('.cw-cards-container').on('click', '.cw-upload-img-btn', function(e){
                e.preventDefault();
                var $btn = $(this);
                var $row = $btn.closest('.cw-meta-field');
                var $input = $row.find('.cw-img-url-input');
                var $img = $row.find('.cw-img-thumb');
                var $removeBtn = $row.find('.cw-remove-img-btn');

                var frame = wp.media({
                    title: '<?php echo esc_js( __( 'Select or Upload Card Image', 'codyweb-child' ) ); ?>',
                    button: { text: '<?php echo esc_js( __( 'Use this image', 'codyweb-child' ) ); ?>' },
                    multiple: false
                });

                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    var url = attachment.sizes && attachment.sizes.large ? attachment.sizes.large.url : attachment.url;
                    $input.val(url);
                    $img.attr('src', url).removeClass('no-img').show();
                    $btn.text('<?php echo esc_js( __( 'Change Image', 'codyweb-child' ) ); ?>');
                    $removeBtn.show();
                });

                frame.open();
            });

            // 4. Remove Image
            $('.cw-cards-container').on('click', '.cw-remove-img-btn', function(e){
                e.preventDefault();
                var $btn = $(this);
                var $row = $btn.closest('.cw-meta-field');
                $row.find('.cw-img-url-input').val('');
                $row.find('.cw-img-thumb').attr('src', '').addClass('no-img').hide();
                $row.find('.cw-upload-img-btn').text('<?php echo esc_js( __( 'Select Image', 'codyweb-child' ) ); ?>');
                $btn.hide();
            });
        });
    })(jQuery);
    </script>
    <?php
}

/**
 * Save Hero Section Meta Data.
 *
 * @param int $post_id Post ID.
 */
function cw_save_page_hero_meta( $post_id ) {
    if ( ! isset( $_POST['cw_page_hero_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['cw_page_hero_nonce'], 'cw_page_hero_save_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    // 1. Show (yes/no)
    $show = ( isset( $_POST['cw_hero_show'] ) && 'no' === $_POST['cw_hero_show'] ) ? 'no' : 'yes';
    update_post_meta( $post_id, '_cw_hero_show', $show );

    // 2. Number of cards
    $count = isset( $_POST['cw_hero_card_count'] ) ? intval( $_POST['cw_hero_card_count'] ) : 2;
    if ( $count < 1 || $count > 4 ) {
        $count = 2;
    }
    update_post_meta( $post_id, '_cw_hero_card_count', $count );

    // 3. Cards Data
    $sanitized_cards = array();
    if ( isset( $_POST['cw_hero_cards'] ) && is_array( $_POST['cw_hero_cards'] ) ) {
        for ( $i = 0; $i < $count; $i++ ) {
            $raw_card = isset( $_POST['cw_hero_cards'][ $i ] ) ? $_POST['cw_hero_cards'][ $i ] : array();
            $sanitized_cards[] = array(
                'image'       => isset( $raw_card['image'] ) ? esc_url_raw( wp_unslash( $raw_card['image'] ) ) : '',
                'heading'     => isset( $raw_card['heading'] ) ? sanitize_text_field( wp_unslash( $raw_card['heading'] ) ) : '',
                'description' => isset( $raw_card['description'] ) ? sanitize_textarea_field( wp_unslash( $raw_card['description'] ) ) : '',
                'btn_text'    => isset( $raw_card['btn_text'] ) ? sanitize_text_field( wp_unslash( $raw_card['btn_text'] ) ) : '',
                'btn_url'     => isset( $raw_card['btn_url'] ) ? sanitize_text_field( wp_unslash( $raw_card['btn_url'] ) ) : '',
            );
        }
    }
    update_post_meta( $post_id, '_cw_hero_cards', $sanitized_cards );
}
add_action( 'save_post_page', 'cw_save_page_hero_meta' );

/**
 * Helper function to retrieve the hero data for any page.
 *
 * @param int|null $post_id The page ID.
 * @return array Normalized hero section configuration.
 */
function cw_get_page_hero( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $raw_show  = get_post_meta( $post_id, '_cw_hero_show', true );
    $show      = ( 'no' !== $raw_show ); // Default to true if not explicitly 'no'
    $count     = intval( get_post_meta( $post_id, '_cw_hero_card_count', true ) );
    $cards     = get_post_meta( $post_id, '_cw_hero_cards', true );

    if ( ! is_array( $cards ) || empty( $cards ) ) {
        // Fallback default for Home Page if meta has not been explicitly saved yet
        $theme_uri = get_stylesheet_directory_uri();
        $cards = array(
            array(
                'image'       => $theme_uri . '/assets/images/hero-hyrox.jpg',
                'heading'     => 'Start HYROX',
                'description' => '',
                'btn_text'    => 'Read more',
                'btn_url'     => '/hyrox',
            ),
            array(
                'image'       => $theme_uri . '/assets/images/hero-crossfit.jpg',
                'heading'     => 'Start CrossFit',
                'description' => '',
                'btn_text'    => 'Read more',
                'btn_url'     => '/crossfit',
            ),
        );
        $count = 2;
    } else {
        if ( $count < 1 ) {
            $count = count( $cards );
        }
        $cards = array_slice( $cards, 0, $count );
    }

    return array(
        'show'  => $show,
        'count' => $count,
        'cards' => $cards,
    );
}

/**
 * Render the Rich Text Section meta box.
 *
 * @param WP_Post $post Current post object.
 */
function cw_render_page_rich_text_meta_box( $post ) {
    wp_nonce_field( 'cw_page_rich_text_save_meta', 'cw_page_rich_text_nonce' );

    $show        = get_post_meta( $post->ID, '_cw_rich_text_show', true );
    $show        = ( '' === $show ) ? 'yes' : $show;
    $heading     = get_post_meta( $post->ID, '_cw_rich_text_heading', true );
    $description = get_post_meta( $post->ID, '_cw_rich_text_description', true );
    $btn_text    = get_post_meta( $post->ID, '_cw_rich_text_btn_text', true );
    $btn_url     = get_post_meta( $post->ID, '_cw_rich_text_btn_url', true );
    ?>
    <div class="cw-hero-meta-wrap">

        <!-- Show (Yes / No) -->
        <div class="cw-meta-row" style="border-top: none;">
            <div class="cw-meta-label">
                <label><?php esc_html_e( 'Show Rich Text Section', 'codyweb-child' ); ?></label>
            </div>
            <div class="cw-meta-field">
                <div class="cw-radio-group">
                    <label>
                        <input type="radio" name="cw_rich_text_show" value="yes" <?php checked( $show, 'yes' ); ?>>
                        <span><?php esc_html_e( 'Yes (Show)', 'codyweb-child' ); ?></span>
                    </label>
                    <label>
                        <input type="radio" name="cw_rich_text_show" value="no" <?php checked( $show, 'no' ); ?>>
                        <span><?php esc_html_e( 'No (Hide)', 'codyweb-child' ); ?></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Heading -->
        <div class="cw-meta-row">
            <div class="cw-meta-label">
                <label for="cw_rich_text_heading"><?php esc_html_e( 'Heading', 'codyweb-child' ); ?></label>
            </div>
            <div class="cw-meta-field">
                <input type="text"
                       id="cw_rich_text_heading"
                       name="cw_rich_text_heading"
                       value="<?php echo esc_attr( $heading ); ?>"
                       placeholder="<?php esc_attr_e( "e.g. You don't need to be fit to start. You only need to decide to walk through the door.", 'codyweb-child' ); ?>">
            </div>
        </div>

        <!-- Description -->
        <div class="cw-meta-row">
            <div class="cw-meta-label">
                <label for="cw_rich_text_description"><?php esc_html_e( 'Description', 'codyweb-child' ); ?></label>
            </div>
            <div class="cw-meta-field">
                <textarea id="cw_rich_text_description"
                          name="cw_rich_text_description"
                          rows="5"
                          placeholder="<?php esc_attr_e( 'Enter section description or motivational body text...', 'codyweb-child' ); ?>"><?php echo esc_textarea( $description ); ?></textarea>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="cw-meta-row" style="border-bottom: none;">
            <div class="cw-meta-label">
                <label><?php esc_html_e( 'CTA Button', 'codyweb-child' ); ?></label>
            </div>
            <div class="cw-meta-field">
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <input type="text"
                           name="cw_rich_text_btn_text"
                           value="<?php echo esc_attr( $btn_text ); ?>"
                           placeholder="<?php esc_attr_e( 'Button Text (leave blank if no button)', 'codyweb-child' ); ?>"
                           style="flex: 1; min-width: 180px;">
                    <input type="text"
                           name="cw_rich_text_btn_url"
                           value="<?php echo esc_attr( $btn_url ); ?>"
                           placeholder="<?php esc_attr_e( 'Button Link (e.g. /yhteystiedot)', 'codyweb-child' ); ?>"
                           style="flex: 2; min-width: 220px;">
                </div>
                <p class="cw-help-text"><?php esc_html_e( 'If button text or link is left blank, no button will be shown.', 'codyweb-child' ); ?></p>
            </div>
        </div>

    </div>
    <?php
}

/**
 * Save Rich Text Section Meta Data.
 *
 * @param int $post_id Post ID.
 */
function cw_save_page_rich_text_meta( $post_id ) {
    if ( ! isset( $_POST['cw_page_rich_text_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['cw_page_rich_text_nonce'], 'cw_page_rich_text_save_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    $show = ( isset( $_POST['cw_rich_text_show'] ) && 'no' === $_POST['cw_rich_text_show'] ) ? 'no' : 'yes';
    update_post_meta( $post_id, '_cw_rich_text_show', $show );

    if ( isset( $_POST['cw_rich_text_heading'] ) ) {
        update_post_meta( $post_id, '_cw_rich_text_heading', sanitize_text_field( wp_unslash( $_POST['cw_rich_text_heading'] ) ) );
    }
    if ( isset( $_POST['cw_rich_text_description'] ) ) {
        update_post_meta( $post_id, '_cw_rich_text_description', sanitize_textarea_field( wp_unslash( $_POST['cw_rich_text_description'] ) ) );
    }
    if ( isset( $_POST['cw_rich_text_btn_text'] ) ) {
        update_post_meta( $post_id, '_cw_rich_text_btn_text', sanitize_text_field( wp_unslash( $_POST['cw_rich_text_btn_text'] ) ) );
    }
    if ( isset( $_POST['cw_rich_text_btn_url'] ) ) {
        update_post_meta( $post_id, '_cw_rich_text_btn_url', sanitize_text_field( wp_unslash( $_POST['cw_rich_text_btn_url'] ) ) );
    }
}
add_action( 'save_post_page', 'cw_save_page_rich_text_meta' );

/**
 * Helper function to retrieve the Rich Text section data for any page.
 *
 * @param int|null $post_id The page ID.
 * @return array Normalized rich text section configuration.
 */
function cw_get_page_rich_text( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $raw_show    = get_post_meta( $post_id, '_cw_rich_text_show', true );
    $show        = ( 'no' !== $raw_show );
    $heading     = get_post_meta( $post_id, '_cw_rich_text_heading', true );
    $description = get_post_meta( $post_id, '_cw_rich_text_description', true );
    $btn_text    = get_post_meta( $post_id, '_cw_rich_text_btn_text', true );
    $btn_url     = get_post_meta( $post_id, '_cw_rich_text_btn_url', true );

    // Default fallback on homepage if not yet explicitly saved
    if ( '' === $heading && '' === $description && is_front_page() ) {
        $heading     = "You don't need to be fit to start. You only need to decide to walk through the door.";
        $description = 'We are a coached functional training gym in Kouvola. CrossFit, HYROX and Easy WOD classes run every day of the week and every workout is scaled to the person doing it — first-timers and competitors train side by side in the same hour.';
    }

    return array(
        'show'        => $show,
        'heading'     => $heading,
        'description' => $description,
        'btn_text'    => $btn_text,
        'btn_url'     => $btn_url,
    );
}

