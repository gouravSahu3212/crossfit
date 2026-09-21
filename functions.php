<?php
/**
 * Codyweb Child Theme Functions
 *
 * Enqueues parent (Codyweb) and child theme stylesheets.
 *
 * @package Codyweb_Child
 */

/**
 * Enqueue parent and child theme styles.
 */
function codyweb_child_enqueue_styles() {
    // Enqueue parent theme stylesheet
    wp_enqueue_style(
        'codyweb-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme( get_template() )->get( 'Version' )
    );

    // Enqueue child theme stylesheet (depends on parent)
    wp_enqueue_style(
        'codyweb-child-style',
        get_stylesheet_uri(),
        array( 'codyweb-parent-style' ),
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'wp_enqueue_scripts', 'codyweb_child_enqueue_styles' );

// =========================================================
// Training Recommendation Chatbot
// =========================================================
require_once get_stylesheet_directory() . '/inc/chatbot-cpt.php';
require_once get_stylesheet_directory() . '/inc/chatbot-enqueue.php';
require_once get_stylesheet_directory() . '/inc/chatbot-api.php';
require_once get_stylesheet_directory() . '/inc/chatbot-admin-questions.php';
require_once get_stylesheet_directory() . '/inc/chatbot-admin-rules.php';
require_once get_stylesheet_directory() . '/inc/chatbot-frontend.php';
