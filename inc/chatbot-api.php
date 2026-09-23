<?php
/**
 * REST API endpoints for the Training Recommendation Chatbot.
 *
 * Namespace: cw-chatbot/v1
 * Routes:
 *   GET/POST /questions
 *   GET      /recommendations
 *   GET/POST /rules
 *   GET      /config  (merged, used by frontend widget)
 *
 * @package Codyweb_Child
 */

/**
 * Register all REST API routes.
 */
function cw_chatbot_register_routes() {
    $ns = 'cw-chatbot/v1';

    register_rest_route( $ns, '/questions', array(
        array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'cw_api_get_questions',
            'permission_callback' => '__return_true',
        ),
        array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'cw_api_save_questions',
            'permission_callback' => 'cw_api_admin_permission',
        ),
    ) );

    register_rest_route( $ns, '/recommendations', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'cw_api_get_recommendations',
        'permission_callback' => '__return_true',
    ) );

    register_rest_route( $ns, '/rules', array(
        array(
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => 'cw_api_get_rules',
            'permission_callback' => '__return_true',
        ),
        array(
            'methods'             => WP_REST_Server::CREATABLE,
            'callback'            => 'cw_api_save_rules',
            'permission_callback' => 'cw_api_admin_permission',
        ),
    ) );

    register_rest_route( $ns, '/config', array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'cw_api_get_config',
        'permission_callback' => '__return_true',
    ) );
}
add_action( 'rest_api_init', 'cw_chatbot_register_routes' );

/**
 * Permission callback: require manage_options capability.
 */
function cw_api_admin_permission() {
    return current_user_can( 'manage_options' );
}

/**
 * GET /questions — Returns all questions from wp_options.
 */
function cw_api_get_questions() {
    $questions = get_option( 'cw_chatbot_questions', array() );
    return rest_ensure_response( $questions );
}

/**
 * POST /questions — Sanitises and saves the questions array.
 *
 * @param WP_REST_Request $request
 */
function cw_api_save_questions( WP_REST_Request $request ) {
    $body = $request->get_json_params();

    if ( ! is_array( $body ) ) {
        return new WP_Error( 'invalid_data', 'Questions must be a JSON array.', array( 'status' => 400 ) );
    }

    $questions = array();
    foreach ( $body as $index => $q ) {
        if ( empty( $q['id'] ) || ! isset( $q['text'] ) ) {
            continue;
        }
        $question = array(
            'id'      => sanitize_key( $q['id'] ),
            'order'   => $index,
            'text'    => sanitize_text_field( wp_unslash( $q['text'] ) ),
            'answers' => array(),
        );
        foreach ( ( $q['answers'] ?? array() ) as $a ) {
            if ( empty( $a['id'] ) ) {
                continue;
            }
            $question['answers'][] = array(
                'id'   => sanitize_key( $a['id'] ),
                'text' => sanitize_text_field( wp_unslash( $a['text'] ?? '' ) ),
                'slug' => sanitize_key( $a['slug'] ?? '' ),
            );
        }
        $questions[] = $question;
    }

    update_option( 'cw_chatbot_questions', $questions );
    return rest_ensure_response( array( 'success' => true, 'saved' => count( $questions ) ) );
}

/**
 * GET /recommendations — Returns all published cw_recommendation posts with meta.
 */
function cw_api_get_recommendations() {
    $posts = get_posts( array(
        'post_type'      => 'cw_recommendation',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ) );

    $out = array();
    foreach ( $posts as $post ) {
        $out[] = array(
            'id'          => $post->ID,
            'title'       => $post->post_title,
            'description' => get_post_meta( $post->ID, '_cw_rec_description', true ),
            'price'       => get_post_meta( $post->ID, '_cw_rec_price', true ),
            'tag'         => get_post_meta( $post->ID, '_cw_rec_tag', true ),
            // 'url'         => get_post_meta( $post->ID, '_cw_rec_url', true ),
            'url'         => get_permalink($post->ID),
            'icon'        => get_post_meta( $post->ID, '_cw_rec_icon', true ),
        );
    }

    return rest_ensure_response( $out );
}

/**
 * GET /rules — Returns saved rules and fallback from wp_options.
 */
function cw_api_get_rules() {
    $data = get_option( 'cw_chatbot_rules', array( 'rules' => array(), 'fallback' => array() ) );
    return rest_ensure_response( $data );
}

/**
 * POST /rules — Sanitises and saves rules + fallback.
 *
 * Expects body: { rules: [...], fallback: { message: '', url: '' } }
 *
 * @param WP_REST_Request $request
 */
function cw_api_save_rules( WP_REST_Request $request ) {
    $body = $request->get_json_params();

    if ( ! is_array( $body ) ) {
        return new WP_Error( 'invalid_data', 'Invalid payload.', array( 'status' => 400 ) );
    }

    $rules_raw    = $body['rules'] ?? ( isset( $body[0] ) ? $body : array() );
    $fallback_raw = $body['fallback'] ?? array();

    $rules = array();
    foreach ( $rules_raw as $index => $r ) {
        if ( empty( $r['id'] ) || empty( $r['recommendation_id'] ) ) {
            continue;
        }
        $rule = array(
            'id'                => sanitize_key( $r['id'] ),
            'recommendation_id' => absint( $r['recommendation_id'] ),
            'match_mode'        => in_array( $r['match_mode'] ?? 'all', array( 'all', 'any' ), true ) ? $r['match_mode'] : 'all',
            'conditions'        => array(),
        );
        foreach ( ( $r['conditions'] ?? array() ) as $c ) {
            if ( empty( $c['question_id'] ) || empty( $c['answer_slug'] ) ) {
                continue;
            }
            $rule['conditions'][] = array(
                'question_id' => sanitize_key( $c['question_id'] ),
                'answer_slug' => sanitize_key( $c['answer_slug'] ),
            );
        }
        $rules[] = $rule;
    }

    $fallback = array(
        'message' => sanitize_textarea_field( wp_unslash( $fallback_raw['message'] ?? '' ) ),
        'url'     => esc_url_raw( wp_unslash( $fallback_raw['url'] ?? '' ) ),
    );

    update_option( 'cw_chatbot_rules', array( 'rules' => $rules, 'fallback' => $fallback ) );
    return rest_ensure_response( array( 'success' => true, 'saved' => count( $rules ) ) );
}

/**
 * GET /config — Merged config consumed by the frontend widget (single HTTP request).
 */
function cw_api_get_config() {
    $rules_data = get_option( 'cw_chatbot_rules', array( 'rules' => array(), 'fallback' => array() ) );

    $questions_resp       = cw_api_get_questions();
    $recommendations_resp = cw_api_get_recommendations();

    return rest_ensure_response( array(
        'questions'       => $questions_resp->get_data(),
        'recommendations' => $recommendations_resp->get_data(),
        'rules'           => $rules_data['rules'] ?? array(),
        'fallback'        => $rules_data['fallback'] ?? array(),
    ) );
}
