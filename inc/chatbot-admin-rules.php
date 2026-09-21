<?php
/**
 * Admin page: Logic Rules builder.
 *
 * Registers the "Logic Rules" submenu under Training Chatbot.
 * The JS (admin/rules.js) handles all dynamic UI rendering.
 *
 * @package Codyweb_Child
 */

/**
 * Register the Logic Rules submenu page.
 */
function cw_chatbot_register_rules_menu() {
    add_submenu_page(
        'cw-chatbot',
        __( 'Logic Rules', 'codyweb-child' ),
        __( 'Logic Rules', 'codyweb-child' ),
        'manage_options',
        'cw-chatbot-rules',
        'cw_render_rules_page'
    );
}
add_action( 'admin_menu', 'cw_chatbot_register_rules_menu' );

/**
 * Render the Logic Rules admin page shell.
 * The JS app mounts into #cw-rules-app.
 */
function cw_render_rules_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'codyweb-child' ) );
    }
    ?>
    <div class="wrap cw-admin-wrap">
        <div id="cw-rules-app">
            <div class="cw-loading-placeholder">
                <span class="spinner is-active" style="float:none;margin:0 8px 0 0;"></span>
                Loading Rules...
            </div>
        </div>
    </div>
    <?php
}
