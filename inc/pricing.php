<?php
/**
 * Pricing Plans Management & Admin Options.
 *
 * Allows managing gym pricing tiers and memberships in WP Admin.
 *
 * @package Codyweb_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register "Pricing Plans" admin menu page.
 */
function cw_register_pricing_admin_menu() {
    add_menu_page(
        __( 'Pricing Plans', 'codyweb-child' ),
        __( 'Pricing Plans', 'codyweb-child' ),
        'manage_options',
        'cw-pricing-plans',
        'cw_render_pricing_admin_page',
        'dashicons-tag',
        33
    );
}
add_action( 'admin_menu', 'cw_register_pricing_admin_menu' );

/**
 * Return default pricing plans data matching the design.
 *
 * @return array Default plans.
 */
function cw_get_default_pricing_plans() {
    return array(
        array(
            'title'     => 'Full Membership',
            'price'     => '89 €',
            'period'    => '/ month',
            'badge'     => 'Most Popular',
            'featured'  => 'yes',
            'features'  => "Unlimited coached classes\nCrossFit, HYROX & Easy WOD\nOpen Gym access during open hours\nWODconnect workout tracking\nContinuous monthly billing",
            'btn_text'  => 'Choose Plan',
            'btn_url'   => 'https://www.wodconnect.com/crossfit-kouvola',
            'btn_style' => 'gold',
        ),
        array(
            'title'     => '10-Session Pass',
            'price'     => '139 €',
            'period'    => '/ 10 visits',
            'badge'     => '',
            'featured'  => 'no',
            'features'  => "10 class visits of your choice\nValid for 3 full months\nAccess to all class formats\nGreat for flexible training schedules\nNo ongoing commitment",
            'btn_text'  => 'Get 10-Pass',
            'btn_url'   => 'https://www.wodconnect.com/crossfit-kouvola',
            'btn_style' => 'outline',
        ),
        array(
            'title'     => 'Student / Senior',
            'price'     => '69 €',
            'period'    => '/ month',
            'badge'     => '',
            'featured'  => 'no',
            'features'  => "Unlimited coached classes\nCrossFit, HYROX & Easy WOD\nOpen Gym access\nValid student / senior ID required\nContinuous monthly billing",
            'btn_text'  => 'Choose Plan',
            'btn_url'   => 'https://www.wodconnect.com/crossfit-kouvola',
            'btn_style' => 'outline',
        ),
        array(
            'title'     => 'Drop-In',
            'price'     => '20 €',
            'period'    => '/ class',
            'badge'     => '',
            'featured'  => 'no',
            'features'  => "One single class visit\nVisiting athletes welcome\nBook easily before arriving\nShower & locker facilities\nIncludes workout coaching",
            'btn_text'  => 'Book Drop-In',
            'btn_url'   => '/yhteystiedot',
            'btn_style' => 'outline',
        ),
    );
}

/**
 * Get current pricing plans from DB or fallback to default.
 *
 * @return array
 */
function cw_get_pricing_plans() {
    $saved = get_option( 'cw_pricing_plans', false );
    if ( false === $saved || ! is_array( $saved ) || empty( $saved ) ) {
        return cw_get_default_pricing_plans();
    }
    return $saved;
}

/**
 * Render the Pricing Plans admin page.
 */
function cw_render_pricing_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $saved_notice = false;

    // Handle Form Save
    if ( isset( $_POST['cw_save_pricing_nonce'] ) && wp_verify_nonce( $_POST['cw_save_pricing_nonce'], 'cw_save_pricing' ) ) {
        $raw_plans = isset( $_POST['cw_plans'] ) && is_array( $_POST['cw_plans'] ) ? $_POST['cw_plans'] : array();
        $clean_plans = array();

        foreach ( $raw_plans as $plan ) {
            $title = isset( $plan['title'] ) ? sanitize_text_field( $plan['title'] ) : '';

            // Skip empty rows
            if ( empty( $title ) && empty( $plan['price'] ) ) {
                continue;
            }

            $clean_plans[] = array(
                'title'     => $title,
                'price'     => isset( $plan['price'] ) ? sanitize_text_field( $plan['price'] ) : '',
                'period'    => isset( $plan['period'] ) ? sanitize_text_field( $plan['period'] ) : '',
                'badge'     => isset( $plan['badge'] ) ? sanitize_text_field( $plan['badge'] ) : '',
                'featured'  => ( isset( $plan['featured'] ) && 'yes' === $plan['featured'] ) ? 'yes' : 'no',
                'features'  => isset( $plan['features'] ) ? sanitize_textarea_field( $plan['features'] ) : '',
                'btn_text'  => isset( $plan['btn_text'] ) ? sanitize_text_field( $plan['btn_text'] ) : '',
                'btn_url'   => isset( $plan['btn_url'] ) ? sanitize_text_field( $plan['btn_url'] ) : '',
                'btn_style' => isset( $plan['btn_style'] ) && in_array( $plan['btn_style'], array( 'gold', 'outline', 'auto' ), true ) ? $plan['btn_style'] : 'auto',
            );
        }

        update_option( 'cw_pricing_plans', $clean_plans );
        $saved_notice = true;
    }

    $plans = cw_get_pricing_plans();
    ?>
    <style>
        .cw-pricing-wrap {
            max-width: 1200px;
            margin: 20px 20px 40px 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
        .cw-pricing-header {
            background: #fff;
            padding: 24px 30px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .cw-pricing-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 8px;
        }
        .cw-pricing-header p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
            line-height: 1.5;
        }
        .cw-plans-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 26px;
        }
        .cw-plan-card {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: border-color 0.2s ease;
        }
        .cw-plan-card.is-featured {
            border-color: #c9a84c;
            box-shadow: 0 0 0 1px #c9a84c;
        }
        .cw-plan-card-head {
            background: #0f172a;
            color: #fff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cw-plan-card-head span.plan-title-display {
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.03em;
        }
        .cw-plan-card-body {
            padding: 20px;
        }
        .cw-form-row {
            display: flex;
            gap: 16px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }
        .cw-form-col {
            flex: 1;
            min-width: 200px;
        }
        .cw-form-col label {
            display: block;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            margin-bottom: 6px;
        }
        .cw-form-col input[type="text"],
        .cw-form-col select,
        .cw-form-col textarea {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            font-size: 14px;
            background: #fff;
        }
        .cw-form-col textarea {
            resize: vertical;
            min-height: 90px;
            line-height: 1.5;
            font-family: monospace;
            font-size: 13px;
        }
        .cw-checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #0f172a;
            cursor: pointer;
            margin-top: 24px;
        }
        .cw-delete-plan-btn {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 4px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .cw-delete-plan-btn:hover {
            background: #ef4444;
            color: #fff;
            border-color: #dc2626;
        }
        .cw-add-plan-btn {
            background: #f8fafc;
            border: 2px dashed #94a3b8;
            border-radius: 8px;
            padding: 16px;
            color: #334155;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s ease;
            margin-bottom: 30px;
        }
        .cw-add-plan-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
            border-color: #64748b;
        }
        .cw-pricing-footer {
            background: #fff;
            padding: 18px 24px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            z-index: 10;
        }
        .cw-pricing-hint {
            font-size: 13px;
            color: #64748b;
        }
        .cw-pricing-hint code {
            background: #f1f5f9;
            padding: 3px 6px;
            border-radius: 4px;
            color: #0f172a;
            font-weight: 600;
        }
    </style>

    <div class="wrap cw-pricing-wrap">
        <?php if ( $saved_notice ) : ?>
            <div class="notice notice-success is-dismissible" style="margin-bottom: 20px;">
                <p><strong><?php esc_html_e( 'Pricing Plans updated successfully!', 'codyweb-child' ); ?></strong></p>
            </div>
        <?php endif; ?>

        <div class="cw-pricing-header">
            <h1><?php esc_html_e( 'Pricing & Membership Plans', 'codyweb-child' ); ?></h1>
            <p><?php esc_html_e( 'Add, remove, and manage gym pricing cards. You can display them anywhere using the [pricing_table] shortcode.', 'codyweb-child' ); ?></p>
        </div>

        <form method="POST" action="">
            <?php wp_nonce_field( 'cw_save_pricing', 'cw_save_pricing_nonce' ); ?>

            <div class="cw-plans-list" id="cw-plans-container">
                <?php
                foreach ( $plans as $index => $plan ) :
                    $title     = isset( $plan['title'] ) ? $plan['title'] : '';
                    $price     = isset( $plan['price'] ) ? $plan['price'] : '';
                    $period    = isset( $plan['period'] ) ? $plan['period'] : '';
                    $badge     = isset( $plan['badge'] ) ? $plan['badge'] : '';
                    $featured  = ( ! empty( $plan['featured'] ) && 'yes' === $plan['featured'] );
                    $features  = isset( $plan['features'] ) ? $plan['features'] : '';
                    $btn_text  = isset( $plan['btn_text'] ) ? $plan['btn_text'] : '';
                    $btn_url   = isset( $plan['btn_url'] ) ? $plan['btn_url'] : '';
                    $btn_style = isset( $plan['btn_style'] ) ? $plan['btn_style'] : 'auto';
                    ?>
                    <div class="cw-plan-card <?php echo $featured ? 'is-featured' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>">
                        <div class="cw-plan-card-head">
                            <span class="plan-title-display"><?php echo ! empty( $title ) ? esc_html( $title ) : sprintf( esc_html__( 'Plan #%d', 'codyweb-child' ), $index + 1 ); ?></span>
                            <button type="button" class="cw-delete-plan-btn"><?php esc_html_e( 'Remove Plan', 'codyweb-child' ); ?></button>
                        </div>

                        <div class="cw-plan-card-body">
                            <!-- Row 1: Title, Price, Period -->
                            <div class="cw-form-row">
                                <div class="cw-form-col" style="flex: 2;">
                                    <label><?php esc_html_e( 'Plan Title *', 'codyweb-child' ); ?></label>
                                    <input type="text"
                                           class="cw-plan-title-input"
                                           name="cw_plans[<?php echo esc_attr( $index ); ?>][title]"
                                           value="<?php echo esc_attr( $title ); ?>"
                                           placeholder="e.g. Full Membership" required>
                                </div>
                                <div class="cw-form-col">
                                    <label><?php esc_html_e( 'Price *', 'codyweb-child' ); ?></label>
                                    <input type="text"
                                           name="cw_plans[<?php echo esc_attr( $index ); ?>][price]"
                                           value="<?php echo esc_attr( $price ); ?>"
                                           placeholder="e.g. 89 €" required>
                                </div>
                                <div class="cw-form-col">
                                    <label><?php esc_html_e( 'Billing Period / Subtitle', 'codyweb-child' ); ?></label>
                                    <input type="text"
                                           name="cw_plans[<?php echo esc_attr( $index ); ?>][period]"
                                           value="<?php echo esc_attr( $period ); ?>"
                                           placeholder="e.g. / month, / 10 visits, / class">
                                </div>
                            </div>

                            <!-- Row 2: Badge, Featured Checkbox -->
                            <div class="cw-form-row">
                                <div class="cw-form-col">
                                    <label><?php esc_html_e( 'Badge Label (Optional)', 'codyweb-child' ); ?></label>
                                    <input type="text"
                                           name="cw_plans[<?php echo esc_attr( $index ); ?>][badge]"
                                           value="<?php echo esc_attr( $badge ); ?>"
                                           placeholder="e.g. Most Popular or Best Value">
                                </div>
                                <div class="cw-form-col" style="display: flex; align-items: center;">
                                    <label class="cw-checkbox-label">
                                        <input type="checkbox"
                                               class="cw-plan-featured-cb"
                                               name="cw_plans[<?php echo esc_attr( $index ); ?>][featured]"
                                               value="yes"
                                               <?php checked( $featured, true ); ?>>
                                        <?php esc_html_e( 'Highlight as Featured (Gold border & accent)', 'codyweb-child' ); ?>
                                    </label>
                                </div>
                            </div>

                            <!-- Row 3: Features List -->
                            <div class="cw-form-row">
                                <div class="cw-form-col" style="flex: 100%;">
                                    <label><?php esc_html_e( 'Features List (One feature per line)', 'codyweb-child' ); ?></label>
                                    <textarea name="cw_plans[<?php echo esc_attr( $index ); ?>][features]"
                                              placeholder="Unlimited coached classes&#10;CrossFit, HYROX &amp; Easy WOD&#10;Open Gym access during open hours&#10;WODconnect workout tracking"><?php echo esc_textarea( $features ); ?></textarea>
                                </div>
                            </div>

                            <!-- Row 4: Button Text, URL, Style -->
                            <div class="cw-form-row" style="margin-bottom: 0;">
                                <div class="cw-form-col">
                                    <label><?php esc_html_e( 'CTA Button Text', 'codyweb-child' ); ?></label>
                                    <input type="text"
                                           name="cw_plans[<?php echo esc_attr( $index ); ?>][btn_text]"
                                           value="<?php echo esc_attr( $btn_text ); ?>"
                                           placeholder="e.g. Choose Plan">
                                </div>
                                <div class="cw-form-col" style="flex: 2;">
                                    <label><?php esc_html_e( 'CTA Button URL', 'codyweb-child' ); ?></label>
                                    <input type="text"
                                           name="cw_plans[<?php echo esc_attr( $index ); ?>][btn_url]"
                                           value="<?php echo esc_attr( $btn_url ); ?>"
                                           placeholder="e.g. https://www.wodconnect.com/... or /yhteystiedot">
                                </div>
                                <div class="cw-form-col">
                                    <label><?php esc_html_e( 'Button Style', 'codyweb-child' ); ?></label>
                                    <select name="cw_plans[<?php echo esc_attr( $index ); ?>][btn_style]">
                                        <option value="auto" <?php selected( $btn_style, 'auto' ); ?>><?php esc_html_e( 'Auto (Gold for featured, Outline for others)', 'codyweb-child' ); ?></option>
                                        <option value="gold" <?php selected( $btn_style, 'gold' ); ?>><?php esc_html_e( 'Gold Button (Filled)', 'codyweb-child' ); ?></option>
                                        <option value="outline" <?php selected( $btn_style, 'outline' ); ?>><?php esc_html_e( 'Outline Button', 'codyweb-child' ); ?></option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="cw-add-plan-btn" id="cw-add-plan-btn">
                + <?php esc_html_e( 'Add Another Pricing Plan', 'codyweb-child' ); ?>
            </button>

            <div class="cw-pricing-footer">
                <div class="cw-pricing-hint">
                    <?php esc_html_e( 'Shortcode:', 'codyweb-child' ); ?> <code>[pricing_table]</code> &bull; <code>[pricing_table title="Pricing" label="Plans"]</code>
                </div>
                <button type="submit" class="button button-primary button-large" style="padding: 4px 28px; font-weight: 700;">
                    <?php esc_html_e( 'Save Pricing Plans', 'codyweb-child' ); ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Script to dynamically add & remove pricing plans -->
    <script>
    (function($){
        $(document).ready(function(){
            // Live update plan title in header
            $(document).on('input', '.cw-plan-title-input', function(){
                var val = $(this).val();
                var $card = $(this).closest('.cw-plan-card');
                $card.find('.plan-title-display').text(val ? val : 'New Plan');
            });

            // Live toggle featured style
            $(document).on('change', '.cw-plan-featured-cb', function(){
                var $card = $(this).closest('.cw-plan-card');
                if ($(this).is(':checked')) {
                    $card.addClass('is-featured');
                } else {
                    $card.removeClass('is-featured');
                }
            });

            // Add Plan
            $('#cw-add-plan-btn').on('click', function(e){
                e.preventDefault();
                var timestamp = Date.now();
                var count = $('.cw-plan-card').length + 1;

                var tpl = '<div class="cw-plan-card" data-index="' + timestamp + '">' +
                    '<div class="cw-plan-card-head">' +
                        '<span class="plan-title-display">New Plan #' + count + '</span>' +
                        '<button type="button" class="cw-delete-plan-btn">Remove Plan</button>' +
                    '</div>' +
                    '<div class="cw-plan-card-body">' +
                        '<div class="cw-form-row">' +
                            '<div class="cw-form-col" style="flex: 2;">' +
                                '<label>Plan Title *</label>' +
                                '<input type="text" class="cw-plan-title-input" name="cw_plans[' + timestamp + '][title]" value="" placeholder="e.g. 5-Session Pass" required>' +
                            '</div>' +
                            '<div class="cw-form-col">' +
                                '<label>Price *</label>' +
                                '<input type="text" name="cw_plans[' + timestamp + '][price]" value="" placeholder="e.g. 75 €" required>' +
                            '</div>' +
                            '<div class="cw-form-col">' +
                                '<label>Billing Period / Subtitle</label>' +
                                '<input type="text" name="cw_plans[' + timestamp + '][period]" value="" placeholder="e.g. / month">' +
                            '</div>' +
                        '</div>' +
                        '<div class="cw-form-row">' +
                            '<div class="cw-form-col">' +
                                '<label>Badge Label (Optional)</label>' +
                                '<input type="text" name="cw_plans[' + timestamp + '][badge]" value="" placeholder="e.g. Special Offer">' +
                            '</div>' +
                            '<div class="cw-form-col" style="display: flex; align-items: center;">' +
                                '<label class="cw-checkbox-label">' +
                                    '<input type="checkbox" class="cw-plan-featured-cb" name="cw_plans[' + timestamp + '][featured]" value="yes">' +
                                    'Highlight as Featured (Gold border & accent)' +
                                '</label>' +
                            '</div>' +
                        '</div>' +
                        '<div class="cw-form-row">' +
                            '<div class="cw-form-col" style="flex: 100%;">' +
                                '<label>Features List (One feature per line)</label>' +
                                '<textarea name="cw_plans[' + timestamp + '][features]" placeholder="5 class visits&#10;Valid for 2 months&#10;All class types"></textarea>' +
                            '</div>' +
                        '</div>' +
                        '<div class="cw-form-row" style="margin-bottom: 0;">' +
                            '<div class="cw-form-col">' +
                                '<label>CTA Button Text</label>' +
                                '<input type="text" name="cw_plans[' + timestamp + '][btn_text]" value="Choose Plan" placeholder="e.g. Choose Plan">' +
                            '</div>' +
                            '<div class="cw-form-col" style="flex: 2;">' +
                                '<label>CTA Button URL</label>' +
                                '<input type="text" name="cw_plans[' + timestamp + '][btn_url]" value="/yhteystiedot" placeholder="e.g. /yhteystiedot">' +
                            '</div>' +
                            '<div class="cw-form-col">' +
                                '<label>Button Style</label>' +
                                '<select name="cw_plans[' + timestamp + '][btn_style]">' +
                                    '<option value="auto">Auto (Gold for featured, Outline for others)</option>' +
                                    '<option value="gold">Gold Button (Filled)</option>' +
                                    '<option value="outline">Outline Button</option>' +
                                '</select>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';

                var $newCard = $(tpl).hide();
                $('#cw-plans-container').append($newCard);
                $newCard.fadeIn(150);
                $newCard.find('.cw-plan-title-input').focus();
            });

            // Delete Plan
            $(document).on('click', '.cw-delete-plan-btn', function(e){
                e.preventDefault();
                if (confirm('Are you sure you want to remove this pricing plan?')) {
                    var $card = $(this).closest('.cw-plan-card');
                    $card.fadeOut(150, function(){
                        $card.remove();
                    });
                }
            });
        });
    })(jQuery);
    </script>
    <?php
}
