<?php
/**
 * Codyweb Child Theme Functions
 *
 * Enqueues parent (Codyweb) and child theme stylesheets.
 *
 * @package Codyweb_Child
 */


require get_stylesheet_directory() . '/inc/enqueue.php';
require get_stylesheet_directory() . '/inc/theme-support.php';
require get_stylesheet_directory() . '/inc/menus.php';
require get_stylesheet_directory() . '/inc/shortcodes.php';


// =========================================================
// Widget Areas
// =========================================================
function codyweb_child_register_widgets() {
    register_sidebar( array(
        'name'          => 'Footer Widgets',
        'id'            => 'footer-widgets',
        'description'   => 'Widgets displayed in the site footer.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'codyweb_child_register_widgets' );

// =========================================================
// Training Recommendation Chatbot
// =========================================================
require_once get_stylesheet_directory() . '/inc/chatbot-cpt.php';
require_once get_stylesheet_directory() . '/inc/chatbot-enqueue.php';
require_once get_stylesheet_directory() . '/inc/chatbot-api.php';
require_once get_stylesheet_directory() . '/inc/chatbot-admin-questions.php';
require_once get_stylesheet_directory() . '/inc/chatbot-admin-rules.php';
require_once get_stylesheet_directory() . '/inc/chatbot-frontend.php';
