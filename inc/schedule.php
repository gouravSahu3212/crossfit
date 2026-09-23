<?php
/**
 * Weekly Schedule Management & Admin Options.
 *
 * Allows managing daily class times and class names for Monday - Sunday in WP Admin.
 *
 * @package Codyweb_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register "Weekly Schedule" admin menu page.
 */
function cw_register_schedule_admin_menu() {
    add_menu_page(
        __( 'Weekly Schedule', 'codyweb-child' ),
        __( 'Weekly Schedule', 'codyweb-child' ),
        'manage_options',
        'cw-weekly-schedule',
        'cw_render_schedule_admin_page',
        'dashicons-calendar-alt',
        32
    );
}
add_action( 'admin_menu', 'cw_register_schedule_admin_menu' );

/**
 * Return default 7-day schedule data.
 *
 * @return array Default days and class slots.
 */
function cw_get_default_weekly_schedule() {
    return array(
        'monday' => array(
            'name'  => 'Monday',
            'slots' => array(
                array( 'time' => '06:30', 'class' => 'CrossFit WOD' ),
                array( 'time' => '09:30', 'class' => 'Easy WOD' ),
                array( 'time' => '16:30', 'class' => 'CrossFit WOD' ),
                array( 'time' => '17:30', 'class' => 'HYROX' ),
                array( 'time' => '18:30', 'class' => 'CrossFit WOD' ),
            ),
        ),
        'tuesday' => array(
            'name'  => 'Tuesday',
            'slots' => array(
                array( 'time' => '06:30', 'class' => 'CrossFit WOD' ),
                array( 'time' => '16:30', 'class' => 'Weightlifting' ),
                array( 'time' => '17:30', 'class' => 'CrossFit WOD' ),
                array( 'time' => '18:30', 'class' => 'On-Ramp' ),
            ),
        ),
        'wednesday' => array(
            'name'  => 'Wednesday',
            'slots' => array(
                array( 'time' => '06:30', 'class' => 'CrossFit WOD' ),
                array( 'time' => '09:30', 'class' => 'Easy WOD' ),
                array( 'time' => '16:30', 'class' => 'HYROX' ),
                array( 'time' => '17:30', 'class' => 'CrossFit WOD' ),
                array( 'time' => '18:30', 'class' => 'CrossFit WOD' ),
            ),
        ),
        'thursday' => array(
            'name'  => 'Thursday',
            'slots' => array(
                array( 'time' => '06:30', 'class' => 'CrossFit WOD' ),
                array( 'time' => '16:30', 'class' => 'Gymnastics' ),
                array( 'time' => '17:30', 'class' => 'CrossFit WOD' ),
                array( 'time' => '18:30', 'class' => 'On-Ramp' ),
            ),
        ),
        'friday' => array(
            'name'  => 'Friday',
            'slots' => array(
                array( 'time' => '06:30', 'class' => 'CrossFit WOD' ),
                array( 'time' => '16:30', 'class' => 'CrossFit WOD' ),
                array( 'time' => '17:30', 'class' => 'HYROX' ),
            ),
        ),
        'saturday' => array(
            'name'  => 'Saturday',
            'slots' => array(
                array( 'time' => '10:00', 'class' => 'Team WOD' ),
                array( 'time' => '11:00', 'class' => 'Open Gym' ),
            ),
        ),
        'sunday' => array(
            'name'  => 'Sunday',
            'slots' => array(
                array( 'time' => '11:00', 'class' => 'Open Gym' ),
            ),
        ),
    );
}

/**
 * Get current weekly schedule from DB or fallback to default.
 *
 * @return array
 */
function cw_get_weekly_schedule() {
    $saved = get_option( 'cw_weekly_schedule', false );
    if ( false === $saved || ! is_array( $saved ) || empty( $saved ) ) {
        return cw_get_default_weekly_schedule();
    }
    return $saved;
}

/**
 * Render the Weekly Schedule admin page.
 */
function cw_render_schedule_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $saved_notice = false;

    // Handle Form Save
    if ( isset( $_POST['cw_save_schedule_nonce'] ) && wp_verify_nonce( $_POST['cw_save_schedule_nonce'], 'cw_save_schedule' ) ) {
        $raw_days = isset( $_POST['cw_schedule_days'] ) && is_array( $_POST['cw_schedule_days'] ) ? $_POST['cw_schedule_days'] : array();
        $clean_schedule = array();

        $default_keys = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
        $default_names = array(
            'monday'    => 'Monday',
            'tuesday'   => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday'  => 'Thursday',
            'friday'    => 'Friday',
            'saturday'  => 'Saturday',
            'sunday'    => 'Sunday',
        );

        foreach ( $default_keys as $day_key ) {
            $day_data = isset( $raw_days[ $day_key ] ) ? $raw_days[ $day_key ] : array();
            $day_name = isset( $day_data['name'] ) && ! empty( $day_data['name'] ) ? sanitize_text_field( $day_data['name'] ) : $default_names[ $day_key ];

            $clean_slots = array();
            if ( isset( $day_data['slots'] ) && is_array( $day_data['slots'] ) ) {
                foreach ( $day_data['slots'] as $slot ) {
                    $time  = isset( $slot['time'] ) ? sanitize_text_field( $slot['time'] ) : '';
                    $class = isset( $slot['class'] ) ? sanitize_text_field( $slot['class'] ) : '';

                    // Only save slot if at least class or time is provided
                    if ( '' !== $time || '' !== $class ) {
                        $clean_slots[] = array(
                            'time'  => $time,
                            'class' => $class,
                        );
                    }
                }
            }

            $clean_schedule[ $day_key ] = array(
                'name'  => $day_name,
                'slots' => $clean_slots,
            );
        }

        update_option( 'cw_weekly_schedule', $clean_schedule );
        $saved_notice = true;
    }

    $schedule = cw_get_weekly_schedule();
    ?>
    <style>
        .cw-sched-wrap {
            max-width: 1280px;
            margin: 20px 20px 40px 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
        .cw-sched-header {
            background: #fff;
            padding: 24px 30px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .cw-sched-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 8px;
        }
        .cw-sched-header p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
            line-height: 1.5;
        }
        .cw-sched-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .cw-day-card {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .cw-day-card-head {
            background: #0f172a;
            color: #fff;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .cw-day-card-head input.cw-day-name-input {
            background: transparent;
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            padding: 0;
            margin: 0;
            width: 100%;
        }
        .cw-day-card-head input.cw-day-name-input:focus {
            outline: none;
            border-bottom: 1px solid #c9a84c;
        }
        .cw-day-card-body {
            padding: 16px;
            flex-grow: 1;
        }
        .cw-slots-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 14px;
            min-height: 40px;
        }
        .cw-slot-row {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 10px;
            transition: border-color 0.15s ease;
        }
        .cw-slot-row:hover {
            border-color: #cbd5e1;
        }
        .cw-slot-time-input {
            width: 70px !important;
            font-weight: 700 !important;
            color: #b45309 !important;
            background: #fff !important;
            text-align: center;
            border: 1px solid #cbd5e1 !important;
            border-radius: 4px;
            padding: 5px 4px !important;
            font-size: 13px !important;
        }
        .cw-slot-class-input {
            flex-grow: 1;
            font-size: 13px !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 4px;
            padding: 5px 8px !important;
            background: #fff !important;
        }
        .cw-remove-slot-btn {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 18px;
            cursor: pointer;
            padding: 0 4px;
            line-height: 1;
            border-radius: 3px;
        }
        .cw-remove-slot-btn:hover {
            color: #ef4444;
            background: #fee2e2;
        }
        .cw-add-slot-btn {
            width: 100%;
            background: #f1f5f9;
            border: 1px dashed #94a3b8;
            border-radius: 6px;
            padding: 8px 12px;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.15s ease;
        }
        .cw-add-slot-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
            border-color: #64748b;
        }
        .cw-sched-footer {
            background: #fff;
            padding: 18px 24px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            z-index: 10;
        }
        .cw-shortcode-hint {
            font-size: 13px;
            color: #64748b;
        }
        .cw-shortcode-hint code {
            background: #f1f5f9;
            padding: 3px 6px;
            border-radius: 4px;
            color: #0f172a;
            font-weight: 600;
        }
    </style>

    <div class="wrap cw-sched-wrap">
        <?php if ( $saved_notice ) : ?>
            <div class="notice notice-success is-dismissible" style="margin-bottom: 20px;">
                <p><strong><?php esc_html_e( 'Weekly Schedule updated successfully!', 'codyweb-child' ); ?></strong></p>
            </div>
        <?php endif; ?>

        <div class="cw-sched-header">
            <h1><?php esc_html_e( 'Weekly Gym Schedule', 'codyweb-child' ); ?></h1>
            <p><?php esc_html_e( 'Manage daily class slots (time and class name) for all 7 days. Changes will immediately reflect in the [weekly_schedule] shortcode on the homepage and any other page.', 'codyweb-child' ); ?></p>
        </div>

        <form method="POST" action="">
            <?php wp_nonce_field( 'cw_save_schedule', 'cw_save_schedule_nonce' ); ?>

            <div class="cw-sched-grid">
                <?php
                foreach ( $schedule as $day_key => $day ) :
                    $day_name = isset( $day['name'] ) ? $day['name'] : ucfirst( $day_key );
                    $slots    = isset( $day['slots'] ) && is_array( $day['slots'] ) ? $day['slots'] : array();
                    ?>
                    <div class="cw-day-card" data-day="<?php echo esc_attr( $day_key ); ?>">
                        <div class="cw-day-card-head">
                            <input type="text"
                                   class="cw-day-name-input"
                                   name="cw_schedule_days[<?php echo esc_attr( $day_key ); ?>][name]"
                                   value="<?php echo esc_attr( $day_name ); ?>">
                        </div>

                        <div class="cw-day-card-body">
                            <div class="cw-slots-list" id="slots-<?php echo esc_attr( $day_key ); ?>">
                                <?php
                                if ( ! empty( $slots ) ) :
                                    foreach ( $slots as $s_idx => $slot ) :
                                        $time  = isset( $slot['time'] ) ? $slot['time'] : '';
                                        $class = isset( $slot['class'] ) ? $slot['class'] : '';
                                        ?>
                                        <div class="cw-slot-row">
                                            <input type="text"
                                                   class="cw-slot-time-input"
                                                   name="cw_schedule_days[<?php echo esc_attr( $day_key ); ?>][slots][<?php echo esc_attr( $s_idx ); ?>][time]"
                                                   value="<?php echo esc_attr( $time ); ?>"
                                                   placeholder="06:30">
                                            <input type="text"
                                                   class="cw-slot-class-input"
                                                   name="cw_schedule_days[<?php echo esc_attr( $day_key ); ?>][slots][<?php echo esc_attr( $s_idx ); ?>][class]"
                                                   value="<?php echo esc_attr( $class ); ?>"
                                                   placeholder="Class name">
                                            <button type="button" class="cw-remove-slot-btn" title="Remove slot">&times;</button>
                                        </div>
                                        <?php
                                    endforeach;
                                endif;
                                ?>
                            </div>

                            <button type="button" class="cw-add-slot-btn" data-day="<?php echo esc_attr( $day_key ); ?>">
                                + <?php esc_html_e( 'Add Class Slot', 'codyweb-child' ); ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cw-sched-footer">
                <div class="cw-shortcode-hint">
                    <?php esc_html_e( 'Shortcode:', 'codyweb-child' ); ?> <code>[weekly_schedule]</code> &bull; <code>[weekly_schedule title="Weekly Schedule"]</code>
                </div>
                <button type="submit" class="button button-primary button-large" style="padding: 4px 28px; font-weight: 700;">
                    <?php esc_html_e( 'Save Schedule', 'codyweb-child' ); ?>
                </button>
            </div>
        </form>
    </div>

    <!-- Interactive script to add and remove class slots -->
    <script>
    (function($){
        $(document).ready(function(){
            // Add slot
            $('.cw-add-slot-btn').on('click', function(e){
                e.preventDefault();
                var day = $(this).attr('data-day');
                var $container = $('#slots-' + day);
                var timestamp = Date.now();

                var rowHtml = '<div class="cw-slot-row">' +
                    '<input type="text" class="cw-slot-time-input" name="cw_schedule_days[' + day + '][slots][' + timestamp + '][time]" value="" placeholder="06:30">' +
                    '<input type="text" class="cw-slot-class-input" name="cw_schedule_days[' + day + '][slots][' + timestamp + '][class]" value="" placeholder="Class name">' +
                    '<button type="button" class="cw-remove-slot-btn" title="Remove slot">&times;</button>' +
                    '</div>';

                var $newRow = $(rowHtml).hide();
                $container.append($newRow);
                $newRow.fadeIn(150);
                $newRow.find('.cw-slot-time-input').focus();
            });

            // Remove slot
            $(document).on('click', '.cw-remove-slot-btn', function(e){
                e.preventDefault();
                var $row = $(this).closest('.cw-slot-row');
                $row.fadeOut(150, function(){
                    $row.remove();
                });
            });
        });
    })(jQuery);
    </script>
    <?php
}
