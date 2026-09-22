<?php

function custom_theme_register_menus() {

    register_nav_menus(array(
        'primary_menu' => 'Primary Menu',
        'footer_menu'  => 'Footer Menu',
    ));

}

add_action('init', 'custom_theme_register_menus');