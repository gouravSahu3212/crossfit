<?php

/**
 * Enqueue Parent & Child Theme Styles
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

    // Header CSS
    wp_enqueue_style(
        'theme-header',
        get_stylesheet_directory_uri() . '/assets/css/header.css',
        array('codyweb-child-style'),
        filemtime(get_stylesheet_directory() . '/assets/css/header.css')
    );

    // Footer CSS
    wp_enqueue_style(
        'theme-footer',
        get_stylesheet_directory_uri() . '/assets/css/footer.css',
        array('codyweb-child-style'),
        filemtime(get_stylesheet_directory() . '/assets/css/footer.css')
    );

    // Homepage CSS & JS — only on the Home Page template
    if ( is_page_template( 'templates/template-home.php' ) ) {
        wp_enqueue_style(
            'theme-home',
            get_stylesheet_directory_uri() . '/assets/css/home.css',
            array('codyweb-child-style'),
            filemtime(get_stylesheet_directory() . '/assets/css/home.css')
        );
        wp_enqueue_script(
            'theme-home-js',
            get_stylesheet_directory_uri() . '/assets/js/home.js',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/js/home.js'),
            true
        );
    }

    // Inner Pages CSS & JS (Hyrox, CrossFit, Events, Pricing, Contact)
    $inner_templates = array(
        'templates/template-hyrox.php',
        'templates/template-crossfit.php',
        'templates/template-events.php',
        'templates/template-pricing.php',
        'templates/template-contact.php',
    );

    if ( is_page_template( $inner_templates ) ) {
        wp_enqueue_style(
            'theme-pages',
            get_stylesheet_directory_uri() . '/assets/css/pages.css',
            array('codyweb-child-style'),
            filemtime(get_stylesheet_directory() . '/assets/css/pages.css')
        );
        wp_enqueue_script(
            'theme-pages-js',
            get_stylesheet_directory_uri() . '/assets/js/home.js',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/js/home.js'),
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'codyweb_child_enqueue_styles' );