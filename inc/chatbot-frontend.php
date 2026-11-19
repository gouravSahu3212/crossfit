<?php
/**
 * Frontend: Inject the Training Chatbot floating bubble into wp_footer.
 *
 * @package Codyweb_Child
 */

/**
 * Output the chatbot bubble and panel HTML in the page footer.
 * JavaScript (assets/chatbot.js) handles all widget behaviour.
 */
function cw_chatbot_render_bubble() {
    $lang    = function_exists( 'cw_chatbot_get_language' ) ? cw_chatbot_get_language() : 'fi';
    $strings = function_exists( 'cw_chatbot_get_strings' ) ? cw_chatbot_get_strings( $lang ) : array(
        'wrap_aria'       => 'Valmennuksen valinta-apuribotti',
        'bubble_aria'     => 'Löydä valmennuksesi',
        'bubble_text'     => 'LÖYDÄ VALMENNUKSESI',
        'panel_aria'      => 'Valmennuksen valinta-apuri',
        'panel_title'     => 'LÖYDÄ VALMENNUKSESI',
        'panel_subtitle'  => 'Nopea valmennusopas',
        'close_aria'      => 'Sulje valmennusapuri',
        'scroll_aria'     => 'Vieritä alas',
        'see_all_options' => 'KATSO VALMENNUSVAIHTOEHDOT',
        'restart_title'   => 'Aloita alusta',
    );
    ?>
    <div id="cw-chatbot-wrap" role="complementary" aria-label="<?php echo esc_attr( $strings['wrap_aria'] ); ?>">

        <button
            id="cw-chatbot-bubble"
            type="button"
            aria-label="<?php echo esc_attr( $strings['bubble_aria'] ); ?>"
            aria-expanded="false"
            aria-controls="cw-chatbot-panel"
        >
            <svg class="cw-bubble-icon" aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
            </svg>
            <span class="cw-bubble-text"><?php echo esc_html( $strings['bubble_text'] ); ?></span>
        </button>

        <div
            id="cw-chatbot-panel"
            role="dialog"
            aria-modal="false"
            aria-label="<?php echo esc_attr( $strings['panel_aria'] ); ?>"
            aria-hidden="true"
        >
            <!-- Header -->
            <div class="cw-panel-header">
                <div class="cw-header-left">
                    <div class="cw-header-badge" aria-hidden="true">CFK</div>
                    <div class="cw-header-text">
                        <div class="cw-panel-title"><?php echo esc_html( $strings['panel_title'] ); ?></div>
                        <div class="cw-panel-subtitle"><?php echo esc_html( $strings['panel_subtitle'] ); ?></div>
                    </div>
                </div>
                <button id="cw-chatbot-close" type="button" aria-label="<?php echo esc_attr( $strings['close_aria'] ); ?>">&#x00D7;</button>
            </div>

            <!-- Messages + scroll button -->
            <div class="cw-messages-outer">
                <div id="cw-chatbot-messages" role="log" aria-live="polite" aria-atomic="false"></div>
                <button id="cw-scroll-bottom" class="cw-scroll-btn" type="button" aria-label="<?php echo esc_attr( $strings['scroll_aria'] ); ?>">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
                </button>
            </div>

            <!-- Fixed footer: CTA + Restart -->
            <div class="cw-panel-footer">
                <a id="cw-cta-btn" href="#" class="cw-cta-link is-disabled" aria-disabled="true"><?php echo esc_html( $strings['see_all_options'] ); ?></a>
                <button id="cw-restart-btn" type="button" class="cw-restart-btn" title="<?php echo esc_attr( $strings['restart_title'] ); ?>" aria-label="<?php echo esc_attr( $strings['restart_title'] ); ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>
    <?php
}
add_action( 'wp_footer', 'cw_chatbot_render_bubble' );