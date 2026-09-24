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

    // 3. FAQ Section
    add_meta_box(
        'cw_page_faq_section',
        __( 'FAQ Section Settings', 'codyweb-child' ),
        'cw_render_page_faq_meta_box',
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

// =========================================================
// 3. FAQ Section Meta Box & Functions
// =========================================================

/**
 * Render the FAQ Section meta box on page edit screens.
 *
 * @param WP_Post $post Current post object.
 */
function cw_render_page_faq_meta_box( $post ) {
    wp_nonce_field( 'cw_page_faq_save_meta', 'cw_page_faq_nonce' );

    $faq_data = cw_get_page_faq( $post->ID );
    $show     = $faq_data['show'] ? 'yes' : 'no';
    $label    = $faq_data['label'];
    $title    = $faq_data['title'];
    $items    = ! empty( $faq_data['items'] ) && is_array( $faq_data['items'] ) ? $faq_data['items'] : array();
    ?>
    <style>
        .cw-faq-meta-wrap {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
            color: #1e293b;
        }
        .cw-faq-items-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 10px;
        }
        .cw-faq-item-card {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            transition: border-color 0.2s;
        }
        .cw-faq-item-card:hover {
            border-color: #94a3b8;
        }
        .cw-faq-item-head {
            background: #0f172a;
            color: #fff;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cw-faq-item-head span.cw-faq-title-preview {
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.02em;
        }
        .cw-faq-item-body {
            padding: 16px;
        }
        .cw-faq-remove-btn {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 4px;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .cw-faq-remove-btn:hover {
            background: #ef4444;
            color: #fff;
            border-color: #dc2626;
        }
        .cw-add-faq-btn {
            background: #f8fafc;
            border: 2px dashed #94a3b8;
            border-radius: 6px;
            padding: 12px;
            color: #334155;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s ease;
            margin-top: 14px;
        }
        .cw-add-faq-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #64748b;
        }
    </style>

    <div class="cw-faq-meta-wrap">

        <!-- 1. Show FAQ (Yes / No) -->
        <div class="cw-meta-row" style="border-top: none;">
            <div class="cw-meta-label">
                <label><?php esc_html_e( 'Show FAQ Section', 'codyweb-child' ); ?></label>
            </div>
            <div class="cw-meta-field">
                <div class="cw-radio-group">
                    <label>
                        <input type="radio" name="cw_faq_show" value="yes" <?php checked( $show, 'yes' ); ?>>
                        <span><?php esc_html_e( 'Yes (Show)', 'codyweb-child' ); ?></span>
                    </label>
                    <label>
                        <input type="radio" name="cw_faq_show" value="no" <?php checked( $show, 'no' ); ?>>
                        <span><?php esc_html_e( 'No (Hide)', 'codyweb-child' ); ?></span>
                    </label>
                </div>
            </div>
        </div>

        <!-- 2. Section Label -->
        <div class="cw-meta-row">
            <div class="cw-meta-label">
                <label for="cw_faq_label"><?php esc_html_e( 'Section Label', 'codyweb-child' ); ?></label>
            </div>
            <div class="cw-meta-field">
                <input type="text"
                       id="cw_faq_label"
                       name="cw_faq_label"
                       value="<?php echo esc_attr( $label ); ?>"
                       placeholder="e.g. Questions">
                <p class="cw-help-text"><?php esc_html_e( 'Small eyebrow tag displayed above the main heading.', 'codyweb-child' ); ?></p>
            </div>
        </div>

        <!-- 3. Section Heading -->
        <div class="cw-meta-row">
            <div class="cw-meta-label">
                <label for="cw_faq_title"><?php esc_html_e( 'Section Heading', 'codyweb-child' ); ?></label>
            </div>
            <div class="cw-meta-field">
                <input type="text"
                       id="cw_faq_title"
                       name="cw_faq_title"
                       value="<?php echo esc_attr( $title ); ?>"
                       placeholder="e.g. Pricing FAQ or FAQ">
            </div>
        </div>

        <!-- 4. FAQ Items Repeater -->
        <div class="cw-meta-row" style="border-bottom: none; align-items: flex-start;">
            <div class="cw-meta-label" style="padding-top: 6px;">
                <label><?php esc_html_e( 'FAQ Items', 'codyweb-child' ); ?></label>
                <p class="cw-help-text" style="margin-top: 6px;"><?php esc_html_e( 'Add, edit, or remove questions and answers for this page.', 'codyweb-child' ); ?></p>
            </div>
            <div class="cw-meta-field">
                <div class="cw-faq-items-list" id="cw-faq-items-container">
                    <?php
                    foreach ( $items as $index => $item ) :
                        $q = isset( $item['question'] ) ? $item['question'] : '';
                        $a = isset( $item['answer'] ) ? $item['answer'] : '';
                        ?>
                        <div class="cw-faq-item-card" data-index="<?php echo esc_attr( $index ); ?>">
                            <div class="cw-faq-item-head">
                                <span class="cw-faq-title-preview"><?php echo ! empty( $q ) ? esc_html( $q ) : sprintf( esc_html__( 'Question #%d', 'codyweb-child' ), $index + 1 ); ?></span>
                                <button type="button" class="cw-faq-remove-btn"><?php esc_html_e( 'Remove', 'codyweb-child' ); ?></button>
                            </div>
                            <div class="cw-faq-item-body">
                                <div style="margin-bottom: 12px;">
                                    <label style="display:block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: #475569; margin-bottom: 4px;"><?php esc_html_e( 'Question', 'codyweb-child' ); ?></label>
                                    <input type="text"
                                           class="cw-faq-q-input"
                                           name="cw_faq_items[<?php echo esc_attr( $index ); ?>][question]"
                                           value="<?php echo esc_attr( $q ); ?>"
                                           placeholder="e.g. Do I need to be in shape before starting?"
                                           style="max-width: 100%;">
                                </div>
                                <div>
                                    <label style="display:block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: #475569; margin-bottom: 4px;"><?php esc_html_e( 'Answer', 'codyweb-child' ); ?></label>
                                    <textarea name="cw_faq_items[<?php echo esc_attr( $index ); ?>][answer]"
                                              rows="3"
                                              placeholder="Enter answer..."
                                              style="max-width: 100%;"><?php echo esc_textarea( $a ); ?></textarea>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <button type="button" class="cw-add-faq-btn" id="cw-add-faq-btn">
                    + <?php esc_html_e( 'Add Another FAQ Item', 'codyweb-child' ); ?>
                </button>
            </div>
        </div>

    </div>

    <script>
    (function($){
        $(document).ready(function(){
            // Live update question header preview
            $(document).on('input', '.cw-faq-q-input', function(){
                var val = $(this).val();
                var $card = $(this).closest('.cw-faq-item-card');
                $card.find('.cw-faq-title-preview').text(val ? val : 'New Question');
            });

            // Add new FAQ item
            $('#cw-add-faq-btn').on('click', function(e){
                e.preventDefault();
                var timestamp = Date.now();
                var count = $('.cw-faq-item-card').length + 1;

                var tpl = '<div class="cw-faq-item-card" data-index="' + timestamp + '">' +
                    '<div class="cw-faq-item-head">' +
                        '<span class="cw-faq-title-preview">New Question #' + count + '</span>' +
                        '<button type="button" class="cw-faq-remove-btn">Remove</button>' +
                    '</div>' +
                    '<div class="cw-faq-item-body">' +
                        '<div style="margin-bottom: 12px;">' +
                            '<label style="display:block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: #475569; margin-bottom: 4px;">Question</label>' +
                            '<input type="text" class="cw-faq-q-input" name="cw_faq_items[' + timestamp + '][question]" value="" placeholder="Enter question..." style="max-width: 100%;">' +
                        '</div>' +
                        '<div>' +
                            '<label style="display:block; font-size: 12px; font-weight: 600; text-transform: uppercase; color: #475569; margin-bottom: 4px;">Answer</label>' +
                            '<textarea name="cw_faq_items[' + timestamp + '][answer]" rows="3" placeholder="Enter answer..." style="max-width: 100%;"></textarea>' +
                        '</div>' +
                    '</div>' +
                '</div>';

                var $newCard = $(tpl).hide();
                $('#cw-faq-items-container').append($newCard);
                $newCard.fadeIn(150);
                $newCard.find('.cw-faq-q-input').focus();
            });

            // Remove FAQ item
            $(document).on('click', '.cw-faq-remove-btn', function(e){
                e.preventDefault();
                var $card = $(this).closest('.cw-faq-item-card');
                $card.fadeOut(150, function(){
                    $card.remove();
                });
            });
        });
    })(jQuery);
    </script>
    <?php
}

/**
 * Save FAQ Section Meta Data.
 *
 * @param int $post_id Post ID.
 */
function cw_save_page_faq_meta( $post_id ) {
    if ( ! isset( $_POST['cw_page_faq_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['cw_page_faq_nonce'], 'cw_page_faq_save_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    $show = ( isset( $_POST['cw_faq_show'] ) && 'no' === $_POST['cw_faq_show'] ) ? 'no' : 'yes';
    update_post_meta( $post_id, '_cw_faq_show', $show );

    if ( isset( $_POST['cw_faq_label'] ) ) {
        update_post_meta( $post_id, '_cw_faq_label', sanitize_text_field( wp_unslash( $_POST['cw_faq_label'] ) ) );
    }

    if ( isset( $_POST['cw_faq_title'] ) ) {
        update_post_meta( $post_id, '_cw_faq_title', sanitize_text_field( wp_unslash( $_POST['cw_faq_title'] ) ) );
    }

    $clean_items = array();
    if ( isset( $_POST['cw_faq_items'] ) && is_array( $_POST['cw_faq_items'] ) ) {
        foreach ( $_POST['cw_faq_items'] as $item ) {
            $q = isset( $item['question'] ) ? sanitize_text_field( wp_unslash( $item['question'] ) ) : '';
            $a = isset( $item['answer'] ) ? wp_kses_post( wp_unslash( $item['answer'] ) ) : '';

            if ( ! empty( $q ) || ! empty( $a ) ) {
                $clean_items[] = array(
                    'question' => $q,
                    'answer'   => $a,
                );
            }
        }
    }
    update_post_meta( $post_id, '_cw_faq_items', $clean_items );
}
add_action( 'save_post_page', 'cw_save_page_faq_meta' );

/**
 * Return default contextual FAQ data for a post/page based on its template or slug.
 *
 * @param int $post_id Post ID.
 * @return array
 */
function cw_get_default_faq_data_for_post( $post_id ) {
    $template = get_page_template_slug( $post_id );
    $slug     = get_post_field( 'post_name', $post_id );
    $is_home  = ( get_option( 'page_on_front' ) == $post_id ) || 'templates/template-home.php' === $template || 'home' === $slug || is_front_page();

    if ( $is_home ) {
        return array(
            'show'  => true,
            'label' => 'Questions',
            'title' => 'FAQ',
            'items' => array(
                array(
                    'question' => 'Do I need to be in shape before starting?',
                    'answer'   => 'No. Every workout is scaled to your level and our coaches adjust the movements and loads for you from day one.',
                ),
                array(
                    'question' => 'What is the difference between CrossFit and HYROX?',
                    'answer'   => 'CrossFit focuses on varied functional movements at high intensity. HYROX is a specific race format combining running with functional workout stations. Both are coached and suitable for all levels.',
                ),
                array(
                    'question' => 'Can I try a class before committing?',
                    'answer'   => 'Absolutely! We offer a free trial class so you can experience a session before signing up.',
                ),
                array(
                    'question' => 'What should I bring to my first class?',
                    'answer'   => 'Comfortable workout clothes, indoor training shoes and a water bottle. We have all the equipment you need at the gym.',
                ),
                array(
                    'question' => 'How do I book classes?',
                    'answer'   => "All bookings are made through WODconnect. You'll receive access when you sign up for a membership or pass.",
                ),
            ),
        );
    }

    if ( 'templates/template-pricing.php' === $template || in_array( $slug, array( 'pricing', 'hinnasto' ), true ) ) {
        return array(
            'show'  => true,
            'label' => 'Questions',
            'title' => 'Pricing FAQ',
            'items' => array(
                array(
                    'question' => 'Is there a registration or joining fee?',
                    'answer'   => 'No joining fee whatsoever. You only pay for your active membership or pass, and you can begin training immediately.',
                ),
                array(
                    'question' => 'How can I freeze my membership if I get injured or travel?',
                    'answer'   => "Memberships can be frozen for documented medical reasons (doctor's certificate) or prolonged travel upon request by emailing us at info@crossfitkouvola.com.",
                ),
                array(
                    'question' => 'Can I test a class before buying a membership?',
                    'answer'   => 'Yes! We offer a completely free trial session so you can experience our coaching, equipment, and community before deciding on a plan.',
                ),
            ),
        );
    }

    if ( 'templates/template-hyrox.php' === $template || 'hyrox' === $slug ) {
        return array(
            'show'  => true,
            'label' => 'Questions',
            'title' => 'HYROX FAQ',
            'items' => array(
                array(
                    'question' => 'Do I need running or fitness experience before joining?',
                    'answer'   => 'Not at all. Our HYROX classes are designed for everyone from total beginners wanting to build aerobic endurance to athletes preparing for an official race. Everything is scaled to your current fitness.',
                ),
                array(
                    'question' => 'Do I need an On-Ramp course for HYROX?',
                    'answer'   => 'No! Unlike standard CrossFit which uses barbells and gymnastic rigs, HYROX movements are simple functional exercises that coaches instruct directly in the warm-up.',
                ),
                array(
                    'question' => 'What gear do I need for HYROX sessions?',
                    'answer'   => 'Just breathable workout clothing, good running or indoor training shoes, and a water bottle. All sleds, weights, rowers, and SkiErgs are provided at the gym.',
                ),
            ),
        );
    }

    if ( 'templates/template-crossfit.php' === $template || 'crossfit' === $slug ) {
        return array(
            'show'  => true,
            'label' => 'Questions',
            'title' => 'CrossFit FAQ',
            'items' => array(
                array(
                    'question' => 'What is an On-Ramp course?',
                    'answer'   => 'On-Ramp is our beginner course. In four weeks you learn the fundamental movements (squats, presses, Olympic lifts, gymnastics), safe technique, and how WODs are structured before joining the regular class schedule.',
                ),
                array(
                    'question' => 'What if I cannot do pull-ups or lift heavy weights?',
                    'answer'   => 'Every single movement has multiple variations. We use resistance bands, ring rows, lighter barbells, and dumbells so that you get the exact right stimulus for your current level without risking injury.',
                ),
                array(
                    'question' => 'Can I test a class before signing up for On-Ramp?',
                    'answer'   => 'Yes! We offer a free trial class where you can experience the gym, meet the coaches, and try a beginner-friendly workout with zero commitment.',
                ),
            ),
        );
    }

    if ( 'templates/template-events.php' === $template || in_array( $slug, array( 'events', 'tapahtumat' ), true ) ) {
        return array(
            'show'  => true,
            'label' => 'Questions',
            'title' => 'Events FAQ',
            'items' => array(
                array(
                    'question' => 'Can non-members participate in events?',
                    'answer'   => 'Yes! Most of our events, beginner workshops, and Saturday Team WODs are open to non-members unless stated otherwise.',
                ),
                array(
                    'question' => 'How do I reserve a spot for a workshop or course?',
                    'answer'   => 'You can sign up directly via WODconnect if you already have an account, or send us a message through our Contact page to reserve a spot.',
                ),
            ),
        );
    }

    // Default for any other page
    return array(
        'show'  => true,
        'label' => 'Questions',
        'title' => 'FAQ',
        'items' => array(),
    );
}

/**
 * Retrieve FAQ data for any page.
 *
 * @param int|null $post_id Page ID.
 * @return array
 */
function cw_get_page_faq( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    if ( ! $post_id ) {
        return array(
            'show'  => false,
            'label' => '',
            'title' => '',
            'items' => array(),
        );
    }

    $raw_show = get_post_meta( $post_id, '_cw_faq_show', true );
    $label    = get_post_meta( $post_id, '_cw_faq_label', true );
    $title    = get_post_meta( $post_id, '_cw_faq_title', true );
    $items    = get_post_meta( $post_id, '_cw_faq_items', true );

    // If never saved yet, return defaults
    if ( '' === $raw_show && '' === $title && '' === $label && ( false === $items || '' === $items ) ) {
        return cw_get_default_faq_data_for_post( $post_id );
    }

    return array(
        'show'  => ( 'no' !== $raw_show ),
        'label' => $label,
        'title' => $title,
        'items' => is_array( $items ) ? $items : array(),
    );
}

/**
 * Render FAQ Section HTML for a given page.
 *
 * @param int|null $post_id Page ID.
 * @return string HTML output.
 */
function cw_render_page_faq( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    $faq = cw_get_page_faq( $post_id );

    if ( empty( $faq['show'] ) || empty( $faq['items'] ) ) {
        return '';
    }

    $template = get_page_template_slug( $post_id );
    $slug     = get_post_field( 'post_name', $post_id );
    $is_home  = ( get_option( 'page_on_front' ) == $post_id ) || 'templates/template-home.php' === $template || 'home' === $slug || is_front_page();

    $section_class = $is_home ? 'hp-faq' : 'page-section page-faq';
    $header_class  = $is_home ? 'hp-faq-header' : 'page-section-header';
    $label_class   = $is_home ? 'hp-section-label' : 'page-section-label';
    $title_class   = $is_home ? 'hp-section-title' : 'page-section-title';
    $list_class    = $is_home ? 'hp-faq-list' : 'page-faq-list';
    $item_class    = $is_home ? 'hp-faq-item' : 'page-faq-item';
    $btn_class     = $is_home ? 'hp-faq-q' : 'page-faq-q';
    $icon_class    = $is_home ? 'hp-faq-icon' : 'page-faq-icon';
    $ans_class     = $is_home ? 'hp-faq-a' : 'page-faq-a';

    ob_start();
    ?>
    <section class="<?php echo esc_attr( $section_class ); ?>">
        <?php if ( $is_home ) : ?><div class="page-width"><?php endif; ?>

        <?php if ( ! empty( $faq['label'] ) || ! empty( $faq['title'] ) ) : ?>
            <div class="<?php echo esc_attr( $header_class ); ?>">
                <?php if ( ! empty( $faq['label'] ) ) : ?>
                    <p class="<?php echo esc_attr( $label_class ); ?>"><?php echo esc_html( $faq['label'] ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $faq['title'] ) ) : ?>
                    <h2 class="<?php echo esc_attr( $title_class ); ?>"><?php echo esc_html( $faq['title'] ); ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="<?php echo esc_attr( $list_class ); ?>">
            <?php foreach ( $faq['items'] as $item ) : ?>
                <div class="<?php echo esc_attr( $item_class ); ?>">
                    <button class="<?php echo esc_attr( $btn_class ); ?>" type="button">
                        <?php echo esc_html( $item['question'] ); ?>
                        <svg class="<?php echo esc_attr( $icon_class ); ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                    </button>
                    <div class="<?php echo esc_attr( $ans_class ); ?>">
                        <?php echo wpautop( wp_kses_post( $item['answer'] ) ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ( $is_home ) : ?></div><?php endif; ?>
    </section>
    <?php
    return ob_get_clean();
}


