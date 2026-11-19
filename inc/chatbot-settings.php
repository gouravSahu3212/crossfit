<?php
/**
 * Admin page and internationalization settings for the Training Chatbot.
 *
 * @package Codyweb_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get the currently configured language for the chatbot.
 *
 * @return string 'fi' (default) or 'en'.
 */
function cw_chatbot_get_language() {
    $lang = get_option( 'cw_chatbot_language', 'fi' );
    return in_array( $lang, array( 'fi', 'en' ), true ) ? $lang : 'fi';
}

/**
 * Get UI dictionary strings for a given language.
 *
 * Questions and answers are entered by the admin and are not translated here.
 *
 * @param string|null $lang Language code ('fi' or 'en'). Defaults to current setting.
 * @return array Key-value map of UI strings.
 */
function cw_chatbot_get_strings( $lang = null ) {
    if ( ! $lang ) {
        $lang = cw_chatbot_get_language();
    }

    $strings = array(
        'fi' => array(
            'bubble_text'       => 'LÖYDÄ VALMENNUKSESI',
            'bubble_aria'       => 'Löydä valmennuksesi',
            'wrap_aria'         => 'Valmennuksen valinta-apuribotti',
            'panel_aria'        => 'Valmennuksen valinta-apuri',
            'panel_title'       => 'LÖYDÄ VALMENNUKSESI',
            'panel_subtitle'    => 'Nopea valmennusopas',
            'close_aria'        => 'Sulje valmennusapuri',
            'scroll_aria'       => 'Vieritä alas',
            'intro_template'    => 'Vastaa {count} lyhyeen kysymykseen, niin suosittelemme sinulle sopivinta valmennusta.',
            'no_questions'      => 'Kysymyksiä ei ole vielä määritetty.',
            'error_loading'     => 'Pahoittelut, apurin latauksessa tapahtui virhe. Ole hyvä ja päivitä sivu.',
            'recommend_eyebrow' => 'SUOSITTELEMME',
            'see_rec_prefix'    => 'KATSO ',
            'see_all_options'   => 'KATSO VALMENNUSVAIHTOEHDOT',
            'restart_title'     => 'Aloita alusta',
            'fallback_eyebrow'  => 'OTA YHTEYTTÄ',
            'fallback_title'    => 'Etkö ole vielä varma?',
            'fallback_message'  => 'Ota meihin yhteyttä, niin autamme löytämään sinulle parhaan valmennuksen!',
            'fallback_cta'      => 'OTA YHTEYTTÄ',
        ),
        'en' => array(
            'bubble_text'       => 'FIND YOUR TRAINING',
            'bubble_aria'       => 'Find Your Training',
            'wrap_aria'         => 'Training Finder Chatbot',
            'panel_aria'        => 'Training Finder',
            'panel_title'       => 'FIND YOUR TRAINING',
            'panel_subtitle'    => 'Quick training guide',
            'close_aria'        => 'Close Training Finder',
            'scroll_aria'       => 'Scroll to bottom',
            'intro_template'    => 'Answer {count} quick questions and I\'ll point you to the most suitable training option.',
            'no_questions'      => 'No questions have been configured yet.',
            'error_loading'     => 'Sorry, something went wrong loading the chatbot. Please try refreshing the page.',
            'recommend_eyebrow' => 'WE RECOMMEND',
            'see_rec_prefix'    => 'SEE ',
            'see_all_options'   => 'SEE TRAINING OPTIONS',
            'restart_title'     => 'Restart quiz',
            'fallback_eyebrow'  => 'LET\'S TALK',
            'fallback_title'    => 'Not sure yet?',
            'fallback_message'  => 'Get in touch with us to find the best training option for you!',
            'fallback_cta'      => 'CONTACT US',
        ),
    );

    return isset( $strings[ $lang ] ) ? $strings[ $lang ] : $strings['fi'];
}

/**
 * Register the Settings submenu under Training Chatbot.
 */
function cw_chatbot_register_settings_menu() {
    add_submenu_page(
        'cw-chatbot',
        __( 'Chatbot Settings', 'codyweb-child' ),
        __( 'Settings', 'codyweb-child' ),
        'manage_options',
        'cw-chatbot-settings',
        'cw_render_chatbot_settings_page'
    );
}
add_action( 'admin_menu', 'cw_chatbot_register_settings_menu', 20 );

/**
 * Render the Chatbot Settings page in WP Admin.
 */
function cw_render_chatbot_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have permission to access this page.', 'codyweb-child' ) );
    }

    $saved = false;

    // Handle form save
    if ( isset( $_POST['cw_chatbot_settings_submit'] ) ) {
        check_admin_referer( 'cw_chatbot_settings_save', 'cw_chatbot_settings_nonce' );

        $lang = isset( $_POST['cw_chatbot_language'] ) ? sanitize_key( $_POST['cw_chatbot_language'] ) : 'fi';
        if ( in_array( $lang, array( 'fi', 'en' ), true ) ) {
            update_option( 'cw_chatbot_language', $lang );
            $saved = true;
        }
    }

    $current_lang = cw_chatbot_get_language();
    $fi_strings   = cw_chatbot_get_strings( 'fi' );
    $en_strings   = cw_chatbot_get_strings( 'en' );
    ?>
    <div class="wrap" style="max-width: 900px;">
        <h1 style="display:flex; align-items:center; gap:10px; margin-bottom:20px;">
            <span class="dashicons dashicons-translation" style="font-size:32px; width:32px; height:32px;"></span>
            <?php esc_html_e( 'Training Chatbot Settings', 'codyweb-child' ); ?>
        </h1>

        <?php if ( $saved ) : ?>
            <div class="notice notice-success is-dismissible" style="margin-bottom:20px;">
                <p><strong><?php esc_html_e( 'Settings saved successfully! Chatbot text has been updated.', 'codyweb-child' ); ?></strong></p>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field( 'cw_chatbot_settings_save', 'cw_chatbot_settings_nonce' ); ?>

            <div class="card" style="margin-top:0; padding:24px; max-width:100%; box-shadow:0 1px 3px rgba(0,0,0,0.06); border-radius:8px;">
                <h2 style="margin-top:0; font-size:18px; border-bottom:1px solid #e2e8f0; padding-bottom:12px;">
                    <?php esc_html_e( 'Interface Language', 'codyweb-child' ); ?>
                </h2>

                <p class="description" style="font-size:14px; margin-bottom:20px; color:#475569;">
                    <?php esc_html_e( 'Choose whether the chatbot user interface (bubble button, headings, instructions, results, and action buttons) is displayed in Finnish or English.', 'codyweb-child' ); ?>
                    <br>
                    <strong><?php esc_html_e( 'Note:', 'codyweb-child' ); ?></strong>
                    <?php esc_html_e( 'Questions and answer choices configured in the "Questions" menu will always be displayed exactly as you entered them and are not translated.', 'codyweb-child' ); ?>
                </p>

                <table class="form-table" role="presentation" style="margin-top:0;">
                    <tbody>
                        <tr>
                            <th scope="row" style="width:160px; font-weight:600; vertical-align:top; padding-top:8px;">
                                <?php esc_html_e( 'Select Language', 'codyweb-child' ); ?>
                            </th>
                            <td>
                                <fieldset style="display:flex; flex-direction:column; gap:14px;">
                                    <label style="display:inline-flex; align-items:center; gap:10px; font-size:15px; cursor:pointer;">
                                        <input type="radio" name="cw_chatbot_language" value="fi" <?php checked( $current_lang, 'fi' ); ?> style="margin-top:0;">
                                        <span><strong>Suomi (Finnish)</strong> &mdash; <em>Löydä valmennuksesi</em></span>
                                    </label>

                                    <label style="display:inline-flex; align-items:center; gap:10px; font-size:15px; cursor:pointer;">
                                        <input type="radio" name="cw_chatbot_language" value="en" <?php checked( $current_lang, 'en' ); ?> style="margin-top:0;">
                                        <span><strong>English (English)</strong> &mdash; <em>Find Your Training</em></span>
                                    </label>
                                </fieldset>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-top:20px; padding-top:16px; border-top:1px solid #e2e8f0;">
                    <?php submit_button( __( 'Save Language Settings', 'codyweb-child' ), 'primary', 'cw_chatbot_settings_submit', false ); ?>
                </div>
            </div>

            <!-- Preview Comparison Card -->
            <div class="card" style="margin-top:24px; padding:24px; max-width:100%; box-shadow:0 1px 3px rgba(0,0,0,0.06); border-radius:8px;">
                <h3 style="margin-top:0; font-size:16px; border-bottom:1px solid #e2e8f0; padding-bottom:10px;">
                    <?php esc_html_e( 'Interface Text Reference & Preview', 'codyweb-child' ); ?>
                </h3>
                <p class="description" style="margin-bottom:16px;">
                    <?php esc_html_e( 'Preview of how each text element changes between languages:', 'codyweb-child' ); ?>
                </p>

                <table class="widefat striped" style="border:1px solid #cbd5e1; border-radius:4px;">
                    <thead>
                        <tr>
                            <th style="width:28%;"><?php esc_html_e( 'Element / Location', 'codyweb-child' ); ?></th>
                            <th style="width:36%;">Suomi (Finnish) <?php echo ( 'fi' === $current_lang ) ? '<span style="color:#059669; font-weight:700;">(Aktiivinen)</span>' : ''; ?></th>
                            <th style="width:36%;">English <?php echo ( 'en' === $current_lang ) ? '<span style="color:#059669; font-weight:700;">(Active)</span>' : ''; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Bubble Button</strong></td>
                            <td><code><?php echo esc_html( $fi_strings['bubble_text'] ); ?></code></td>
                            <td><code><?php echo esc_html( $en_strings['bubble_text'] ); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Panel Title</strong></td>
                            <td><code><?php echo esc_html( $fi_strings['panel_title'] ); ?></code></td>
                            <td><code><?php echo esc_html( $en_strings['panel_title'] ); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Panel Subtitle</strong></td>
                            <td><?php echo esc_html( $fi_strings['panel_subtitle'] ); ?></td>
                            <td><?php echo esc_html( $en_strings['panel_subtitle'] ); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Quiz Intro</strong></td>
                            <td><?php echo esc_html( str_replace( '{count}', '3', $fi_strings['intro_template'] ) ); ?></td>
                            <td><?php echo esc_html( str_replace( '{count}', '3', $en_strings['intro_template'] ) ); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Recommendation Eyebrow</strong></td>
                            <td><code><?php echo esc_html( $fi_strings['recommend_eyebrow'] ); ?></code></td>
                            <td><code><?php echo esc_html( $en_strings['recommend_eyebrow'] ); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Action Button</strong></td>
                            <td><code><?php echo esc_html( $fi_strings['see_rec_prefix'] . '...' ); ?></code></td>
                            <td><code><?php echo esc_html( $en_strings['see_rec_prefix'] . '...' ); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Initial Button (Footer)</strong></td>
                            <td><code><?php echo esc_html( $fi_strings['see_all_options'] ); ?></code></td>
                            <td><code><?php echo esc_html( $en_strings['see_all_options'] ); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Restart Button Tooltip</strong></td>
                            <td><?php echo esc_html( $fi_strings['restart_title'] ); ?></td>
                            <td><?php echo esc_html( $en_strings['restart_title'] ); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Fallback (No Match) Eyebrow</strong></td>
                            <td><code><?php echo esc_html( $fi_strings['fallback_eyebrow'] ); ?></code></td>
                            <td><code><?php echo esc_html( $en_strings['fallback_eyebrow'] ); ?></code></td>
                        </tr>
                        <tr>
                            <td><strong>Fallback Title & Button</strong></td>
                            <td><?php echo esc_html( $fi_strings['fallback_title'] . ' / ' . $fi_strings['fallback_cta'] ); ?></td>
                            <td><?php echo esc_html( $en_strings['fallback_title'] . ' / ' . $en_strings['fallback_cta'] ); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
    <?php
}
