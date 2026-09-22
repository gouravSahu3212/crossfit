<?php

function custom_theme_setup() {

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('custom-logo');

    add_image_size('blog-thumb', 600, 400, true);
    
    load_theme_textdomain(
        'codeyweb',
        get_template_directory() . '/languages'
    );
}
add_action('after_setup_theme', 'custom_theme_setup');

// Disable Gutenberg
add_filter('use_block_editor_for_post', '__return_false');
add_filter('use_block_editor_for_post_type', '__return_false');