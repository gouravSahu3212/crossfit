<?php
/**
 * Asset enqueueing for the Training Recommendation Chatbot.
 *
 * Admin pages:
 *   - Questions page hook: toplevel_page_cw-chatbot
 *   - Rules page hook:     training-chatbot_page_cw-chatbot-rules
 *
 * @package Codyweb_Child
 */

/**
 * Enqueue admin-side assets for chatbot pages.
 *
 * @param string $hook Current admin page hook suffix.
 */
function cw_chatbot_enqueue_admin_assets( $hook ) {
    $child_uri = get_stylesheet_directory_uri();
    $version   = wp_get_theme()->get( 'Version' );

    // Questions & Answers admin page
    if ( 'toplevel_page_cw-chatbot' === $hook ) {
        wp_enqueue_style(
            'cw-chatbot-questions-admin',
            $child_uri . '/admin/css/questions.css',
            array(),
            $version
        );
        wp_enqueue_script(
            'cw-chatbot-questions-admin',
            $child_uri . '/admin/js/questions.js',
            array(),
            $version,
            true
        );
        wp_localize_script( 'cw-chatbot-questions-admin', 'cwChatbotAdmin', array(
            'nonce'   => wp_create_nonce( 'wp_rest' ),
            'apiBase' => rest_url( 'cw-chatbot/v1/' ),
        ) );
    }

    // Logic Rules admin page
    if ( 'training-chatbot_page_cw-chatbot-rules' === $hook ) {
        wp_enqueue_style(
            'cw-chatbot-rules-admin',
            $child_uri . '/admin/css/rules.css',
            array(),
            $version
        );
        wp_enqueue_script(
            'cw-chatbot-rules-admin',
            $child_uri . '/admin/js/rules.js',
            array(),
            $version,
            true
        );
        wp_localize_script( 'cw-chatbot-rules-admin', 'cwChatbotRules', array(
            'nonce'        => wp_create_nonce( 'wp_rest' ),
            'apiBase'      => rest_url( 'cw-chatbot/v1/' ),
            'questionsUrl' => admin_url( 'admin.php?page=cw-chatbot' ),
            'newRecUrl'    => admin_url( 'post-new.php?post_type=cw_recommendation' ),
        ) );
    }
}
add_action( 'admin_enqueue_scripts', 'cw_chatbot_enqueue_admin_assets' );

/**
 * Enqueue frontend chatbot assets on all public pages.
 */
function cw_chatbot_enqueue_frontend_assets() {
    $child_uri = get_stylesheet_directory_uri();
    $child_dir = get_stylesheet_directory();
    $theme_ver = wp_get_theme()->get( 'Version' );

    $css_file = $child_dir . '/assets/css/chatbot.css';
    $js_file  = $child_dir . '/assets/js/chatbot.js';

    $css_ver = file_exists( $css_file ) ? (string) filemtime( $css_file ) : $theme_ver;
    $js_ver  = file_exists( $js_file )  ? (string) filemtime( $js_file )  : $theme_ver;

    wp_enqueue_style(
        'cw-chatbot-frontend',
        $child_uri . '/assets/css/chatbot.css',
        array(),
        $css_ver
    );

    wp_enqueue_script(
        'cw-chatbot-frontend',
        $child_uri . '/assets/js/chatbot.js',
        array(),
        $js_ver,
        true
    );

    $lang    = function_exists( 'cw_chatbot_get_language' ) ? cw_chatbot_get_language() : 'fi';
    $strings = function_exists( 'cw_chatbot_get_strings' ) ? cw_chatbot_get_strings( $lang ) : array();

    wp_localize_script( 'cw-chatbot-frontend', 'cwChatbot', array(
        'apiBase' => rest_url( 'cw-chatbot/v1/' ),
        'lang'    => $lang,
        'i18n'    => $strings,
    ) );
}
add_action( 'wp_enqueue_scripts', 'cw_chatbot_enqueue_frontend_assets' );
