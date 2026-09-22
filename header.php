<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>

    <header class="site-main-header">
        <div class="page-width">
            <div class="site-header desktop">
                <div class="logo">
                    <?php the_custom_logo(); ?>
                </div>

                <nav class="main-navigation">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary_menu',
                        'container'      => false,
                        'menu_class'     => 'main-menu',
                        'fallback_cb'    => false,
                    ));
                    ?>
                </nav>
                <a href="https://www.wodconnect.com/crossfit-kouvola" target="_blank" rel="noreferrer" class="btn-primery">WODCONNECT</a>
            </div>
            <div class="site-header mobile">
                <div class="logo">
                    <?php the_custom_logo(); ?>
                </div>
                
                <nav class="main-navigation mobile-nav">
                    <span class="nav-close">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 329.269 329" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M194.8 164.77 323.013 36.555c8.343-8.34 8.343-21.825 0-30.164-8.34-8.34-21.825-8.34-30.164 0L164.633 134.605 36.422 6.391c-8.344-8.34-21.824-8.34-30.164 0-8.344 8.34-8.344 21.824 0 30.164l128.21 128.215L6.259 292.984c-8.344 8.34-8.344 21.825 0 30.164a21.266 21.266 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25l128.21-128.214 128.216 128.214a21.273 21.273 0 0 0 15.082 6.25c5.46 0 10.922-2.09 15.082-6.25 8.343-8.34 8.343-21.824 0-30.164zm0 0" fill="#000000" opacity="1" data-original="#000000" class=""></path></g></svg>
                    </span>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary_menu',
                        'container'      => false,
                        'menu_class'     => 'main-menu',
                        'fallback_cb'    => false,
                    ));
                    ?>
                    <a href="/yhteystiedot/" class="btn-primery">TILAA UUTISKIRJE 
                    <span class="svg-icon-wrapper"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#ff5a00" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" role="img" aria-labelledby="title">
                        <path d="M5 12h13"/>
                        <path d="m13 6 6 6-6 6"/>
                        </svg>
                    </span>
                    </a>
                </nav>
                <span class="humbrger-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><path fill="#000000" fill-rule="evenodd" d="M20.5 7.003h-18a.5.5 0 0 1 0-1h18a.5.5 0 0 1 0 1m0 5h-18a.5.5 0 0 1 0-1h18a.5.5 0 0 1 0 1m0 5h-18a.5.5 0 0 1 0-1h18a.5.5 0 0 1 0 1" opacity="1" data-original="#000000"></path></g></svg>
                </span>
                
            </div>
        </div>

    </header>