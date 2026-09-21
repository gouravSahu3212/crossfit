<?php
/**
 * Admin page: Questions & Answers builder.
 *
 * Registers the top-level "Training Chatbot" menu and the "Questions" submenu.
 * The JS (admin/questions.js) handles all dynamic UI rendering.
 *
 * @package Codyweb_Child
 */

/**
 * Register the Training Chatbot admin menu and Questions submenu.
 */
function cw_chatbot_register_admin_menus() {
    add_menu_page(
        __( 'Training Chatbot', 'codyweb-child' ),
        __( 'Training Chatbot', 'codyweb-child' ),
        'manage_options',
        'cw-chatbot',
        'cw_render_questions_page',
        'dashicons-format-chat',
        30
    );

    // First submenu replaces the duplicated parent label in the sidebar
    add_submenu_page(
        'cw-chatbot',
        __( 'Questions & Answers', 'codyweb-child' ),
        __( 'Questions', 'codyweb-child' ),
        'manage_options',
        'cw-chatbot',
        'cw_render_questions_page'
    );
}
add_action( 'admin_menu', 'cw_chatbot_register_admin_menus' );

/**
 * Render the Questions admin page shell.
 * The JS app mounts into #cw-questions-app.
 */
function cw_render_questions_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'codyweb-child' ) );
    }
    ?>
    <div class="wrap cw-admin-wrap">
        <div id="cw-questions-app">
            <div class="cw-loading-placeholder">
                <span class="spinner is-active" style="float:none;margin:0 8px 0 0;"></span>
                Loading Questions...
            </div>
        </div>
    </div>
    <?php
}
