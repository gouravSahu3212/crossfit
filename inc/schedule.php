<?php
/**
 * Weekly Schedule Management & Admin Options.
 *
 * Allows managing daily class times and class names for Monday - Sunday in WP Admin.
 * Timings are defined once across the week with a column for each day.
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
 * Return default 7-day schedule data (matrix format).
 *
 * @return array Default days and time-slot matrix.
 */
function cw_get_default_weekly_schedule() {
    return array(
        'days' => array(
            'monday'    => __( 'Monday', 'codyweb-child' ),
            'tuesday'   => __( 'Tuesday', 'codyweb-child' ),
            'wednesday' => __( 'Wednesday', 'codyweb-child' ),
            'thursday'  => __( 'Thursday', 'codyweb-child' ),
            'friday'    => __( 'Friday', 'codyweb-child' ),
            'saturday'  => __( 'Saturday', 'codyweb-child' ),
            'sunday'    => __( 'Sunday', 'codyweb-child' ),
        ),
        'slots' => array(
            array(
                'time'    => '06:30',
                'classes' => array(
                    'monday'    => 'CrossFit WOD',
                    'tuesday'   => 'CrossFit WOD',
                    'wednesday' => 'CrossFit WOD',
                    'thursday'  => 'CrossFit WOD',
                    'friday'    => 'CrossFit WOD',
                    'saturday'  => '',
                    'sunday'    => '',
                ),
            ),
            array(
                'time'    => '09:30',
                'classes' => array(
                    'monday'    => 'Easy WOD',
                    'tuesday'   => '',
                    'wednesday' => 'Easy WOD',
                    'thursday'  => '',
                    'friday'    => '',
                    'saturday'  => '',
                    'sunday'    => '',
                ),
            ),
            array(
                'time'    => '10:00',
                'classes' => array(
                    'monday'    => '',
                    'tuesday'   => '',
                    'wednesday' => '',
                    'thursday'  => '',
                    'friday'    => '',
                    'saturday'  => 'Team WOD',
                    'sunday'    => '',
                ),
            ),
            array(
                'time'    => '11:00',
                'classes' => array(
                    'monday'    => '',
                    'tuesday'   => '',
                    'wednesday' => '',
                    'thursday'  => '',
                    'friday'    => '',
                    'saturday'  => 'Open Gym',
                    'sunday'    => 'Open Gym',
                ),
            ),
            array(
                'time'    => '16:30',
                'classes' => array(
                    'monday'    => 'CrossFit WOD',
                    'tuesday'   => 'Weightlifting',
                    'wednesday' => 'HYROX',
                    'thursday'  => 'Gymnastics',
                    'friday'    => 'CrossFit WOD',
                    'saturday'  => '',
                    'sunday'    => '',
                ),
            ),
            array(
                'time'    => '17:30',
                'classes' => array(
                    'monday'    => 'HYROX',
                    'tuesday'   => 'CrossFit WOD',
                    'wednesday' => 'CrossFit WOD',
                    'thursday'  => 'CrossFit WOD',
                    'friday'    => 'HYROX',
                    'saturday'  => '',
                    'sunday'    => '',
                ),
            ),
            array(
                'time'    => '18:30',
                'classes' => array(
                    'monday'    => 'CrossFit WOD',
                    'tuesday'   => 'On-Ramp',
                    'wednesday' => 'CrossFit WOD',
                    'thursday'  => 'On-Ramp',
                    'friday'    => '',
                    'saturday'  => '',
                    'sunday'    => '',
                ),
            ),
        ),
    );
}

/**
 * Normalize schedule data to ensure compatibility with both the new matrix format
 * and any legacy per-day slot format.
 *
 * @param mixed $raw_data Raw option value from database.
 * @return array Normalized array with 'days' and 'slots' keys.
 */
function cw_normalize_weekly_schedule( $raw_data ) {
    $default      = cw_get_default_weekly_schedule();
    $default_days = $default['days'];
    $default_keys = array_keys( $default_days );

    if ( empty( $raw_data ) || ! is_array( $raw_data ) ) {
        return $default;
    }

    // Already in new matrix format
    if ( isset( $raw_data['slots'] ) && is_array( $raw_data['slots'] ) ) {
        $days = isset( $raw_data['days'] ) && is_array( $raw_data['days'] ) ? $raw_data['days'] : array();
        foreach ( $default_days as $dk => $dv ) {
            if ( empty( $days[ $dk ] ) ) {
                $days[ $dk ] = $dv;
            }
        }
        return array(
            'days'  => $days,
            'slots' => $raw_data['slots'],
        );
    }

    // Convert legacy day-based format: $raw_data['monday']['slots'] => ...
    $days = array();
    foreach ( $default_days as $dk => $dv ) {
        $days[ $dk ] = ! empty( $raw_data[ $dk ]['name'] ) ? $raw_data[ $dk ]['name'] : $dv;
    }

    $all_times = array();
    foreach ( $default_keys as $dk ) {
        if ( ! empty( $raw_data[ $dk ]['slots'] ) && is_array( $raw_data[ $dk ]['slots'] ) ) {
            foreach ( $raw_data[ $dk ]['slots'] as $slot ) {
                $t = isset( $slot['time'] ) ? trim( $slot['time'] ) : '';
                if ( '' !== $t && ! in_array( $t, $all_times, true ) ) {
                    $all_times[] = $t;
                }
            }
        }
    }

    usort( $all_times, 'strcmp' );

    $slots = array();
    foreach ( $all_times as $t ) {
        $classes = array();
        foreach ( $default_keys as $dk ) {
            $classes[ $dk ] = '';
            if ( ! empty( $raw_data[ $dk ]['slots'] ) && is_array( $raw_data[ $dk ]['slots'] ) ) {
                foreach ( $raw_data[ $dk ]['slots'] as $slot ) {
                    if ( isset( $slot['time'] ) && trim( $slot['time'] ) === $t ) {
                        $classes[ $dk ] = isset( $slot['class'] ) ? $slot['class'] : '';
                        break;
                    }
                }
            }
        }
        $slots[] = array(
            'time'    => $t,
            'classes' => $classes,
        );
    }

    return array(
        'days'  => $days,
        'slots' => ! empty( $slots ) ? $slots : $default['slots'],
    );
}

/**
 * Get current weekly schedule from DB or fallback to default.
 *
 * @return array Normalized array with 'days' and 'slots'.
 */
function cw_get_weekly_schedule() {
    $saved = get_option( 'cw_weekly_schedule', false );
    return cw_normalize_weekly_schedule( $saved );
}

/**
 * Render the Weekly Schedule admin page.
 */
function cw_render_schedule_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $saved_notice = false;
    $default_keys = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );
    $default_names = array(
        'monday'    => __( 'Monday', 'codyweb-child' ),
        'tuesday'   => __( 'Tuesday', 'codyweb-child' ),
        'wednesday' => __( 'Wednesday', 'codyweb-child' ),
        'thursday'  => __( 'Thursday', 'codyweb-child' ),
        'friday'    => __( 'Friday', 'codyweb-child' ),
        'saturday'  => __( 'Saturday', 'codyweb-child' ),
        'sunday'    => __( 'Sunday', 'codyweb-child' ),
    );

    // Handle Form Save
    if ( isset( $_POST['cw_save_schedule_nonce'] ) && wp_verify_nonce( $_POST['cw_save_schedule_nonce'], 'cw_save_schedule' ) ) {
        $raw_days  = isset( $_POST['cw_schedule_days'] ) && is_array( $_POST['cw_schedule_days'] ) ? $_POST['cw_schedule_days'] : array();
        $raw_slots = isset( $_POST['cw_schedule_slots'] ) && is_array( $_POST['cw_schedule_slots'] ) ? $_POST['cw_schedule_slots'] : array();

        $clean_days = array();
        foreach ( $default_keys as $day_key ) {
            $clean_days[ $day_key ] = ! empty( $raw_days[ $day_key ] )
                ? sanitize_text_field( wp_unslash( $raw_days[ $day_key ] ) )
                : $default_names[ $day_key ];
        }

        $clean_slots = array();
        foreach ( $raw_slots as $slot_item ) {
            $time = isset( $slot_item['time'] ) ? sanitize_text_field( wp_unslash( $slot_item['time'] ) ) : '';
            $classes = array();
            $has_content = ( '' !== $time );

            foreach ( $default_keys as $day_key ) {
                $cls = isset( $slot_item['classes'][ $day_key ] ) ? sanitize_text_field( wp_unslash( $slot_item['classes'][ $day_key ] ) ) : '';
                $classes[ $day_key ] = $cls;
                if ( '' !== $cls ) {
                    $has_content = true;
                }
            }

            if ( $has_content ) {
                $clean_slots[] = array(
                    'time'    => $time,
                    'classes' => $classes,
                );
            }
        }

        $clean_schedule = array(
            'days'  => $clean_days,
            'slots' => $clean_slots,
        );

        update_option( 'cw_weekly_schedule', $clean_schedule );
        $saved_notice = true;
    }

    $schedule = cw_get_weekly_schedule();
    $days     = isset( $schedule['days'] ) ? $schedule['days'] : $default_names;
    $slots    = isset( $schedule['slots'] ) && is_array( $schedule['slots'] ) ? $schedule['slots'] : array();
    ?>
    <style>
        .cw-sched-wrap {
            max-width: 1400px;
            margin: 20px 20px 40px 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
        .cw-sched-header {
            background: #fff;
            padding: 24px 30px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            gap: 20px;
            flex-wrap: wrap;
        }
        .cw-sched-header-content h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 6px;
        }
        .cw-sched-header-content p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
            line-height: 1.5;
        }
        .cw-sched-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .cw-table-card {
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .cw-table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .cw-admin-matrix-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .cw-admin-matrix-table th,
        .cw-admin-matrix-table td {
            padding: 10px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }
        .cw-admin-matrix-table thead th {
            background: #0f172a;
            color: #fff;
            font-weight: 700;
            text-align: center;
            padding: 12px 8px;
        }
        .cw-admin-matrix-table thead th.cw-th-time {
            width: 110px;
            min-width: 100px;
            background: #1e293b;
        }
        .cw-admin-matrix-table thead th.cw-th-actions {
            width: 80px;
            background: #1e293b;
        }
        .cw-day-head-input {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            color: #fff !important;
            font-weight: 700 !important;
            text-align: center;
            font-size: 13px !important;
            border-radius: 4px;
            padding: 4px 6px !important;
            width: 100%;
            max-width: 140px;
            transition: all 0.2s ease;
        }
        .cw-day-head-input:focus {
            background: #fff !important;
            color: #0f172a !important;
            border-color: #bc8e54 !important;
            outline: none;
        }
        .cw-row-time {
            width: 90px !important;
            font-weight: 700 !important;
            color: #b45309 !important;
            background: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 4px;
            text-align: center;
            padding: 6px 4px !important;
            font-size: 13px !important;
        }
        .cw-row-time:focus {
            background: #fff !important;
            border-color: #b45309 !important;
            box-shadow: 0 0 0 1px #b45309 !important;
        }
        .cw-row-class {
            width: 100% !important;
            min-width: 120px;
            border: 1px solid #e2e8f0 !important;
            border-radius: 4px;
            padding: 6px 8px !important;
            font-size: 13px !important;
            background: #ffffff !important;
            transition: all 0.15s ease;
        }
        .cw-row-class:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 1px #3b82f6 !important;
            background: #fff !important;
        }
        .cw-row-class:not(:placeholder-shown) {
            background: #f0fdf4 !important;
            font-weight: 600;
            color: #166534;
        }
        .cw-admin-matrix-table tbody tr:hover {
            background: #f8fafc;
        }
        .cw-action-btns {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }
        .cw-btn-del {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
            font-size: 18px;
            line-height: 1;
            padding: 5px 8px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .cw-btn-del:hover {
            background: #dc2626;
            color: #fff;
            border-color: #dc2626;
        }
        .cw-btn-dup {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-size: 12px;
            padding: 5px 7px;
            border-radius: 4px;
            cursor: pointer;
            line-height: 1;
            transition: all 0.15s ease;
        }
        .cw-btn-dup:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .cw-table-bottom-bar {
            padding: 14px 20px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
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

        <form method="POST" action="" id="cw-schedule-form">
            <?php wp_nonce_field( 'cw_save_schedule', 'cw_save_schedule_nonce' ); ?>

            <div class="cw-sched-header">
                <div class="cw-sched-header-content">
                    <h1><?php esc_html_e( 'Weekly Gym Schedule', 'codyweb-child' ); ?></h1>
                    <p><?php esc_html_e( 'Define class timings once on the left and enter the class description for each day of the week. Day column titles are also editable.', 'codyweb-child' ); ?></p>
                </div>
                <div class="cw-sched-header-actions">
                    <button type="button" class="button" id="cw-sort-rows-top-btn" title="Sort rows chronologically by timing">
                        <span class="dashicons dashicons-sort" style="vertical-align: middle; margin-top: -2px;"></span> <?php esc_html_e( 'Sort by Time', 'codyweb-child' ); ?>
                    </button>
                    <button type="button" class="button button-secondary" id="cw-add-row-top-btn">
                        + <?php esc_html_e( 'Add Time Slot', 'codyweb-child' ); ?>
                    </button>
                    <button type="submit" class="button button-primary button-large" style="font-weight: 700;">
                        <?php esc_html_e( 'Save Schedule', 'codyweb-child' ); ?>
                    </button>
                </div>
            </div>

            <div class="cw-table-card">
                <div class="cw-table-responsive">
                    <table class="cw-admin-matrix-table" id="cw-matrix-table">
                        <thead>
                            <tr>
                                <th class="cw-th-time"><?php esc_html_e( 'Timing', 'codyweb-child' ); ?></th>
                                <?php foreach ( $default_keys as $day_key ) : ?>
                                    <th>
                                        <input type="text"
                                               class="cw-day-head-input"
                                               name="cw_schedule_days[<?php echo esc_attr( $day_key ); ?>]"
                                               value="<?php echo esc_attr( isset( $days[ $day_key ] ) ? $days[ $day_key ] : $default_names[ $day_key ] ); ?>"
                                               title="<?php esc_attr_e( 'Click to rename day column', 'codyweb-child' ); ?>">
                                    </th>
                                <?php endforeach; ?>
                                <th class="cw-th-actions"><?php esc_html_e( 'Actions', 'codyweb-child' ); ?></th>
                            </tr>
                        </thead>
                        <tbody id="cw-schedule-tbody">
                            <?php
                            if ( ! empty( $slots ) ) :
                                foreach ( $slots as $s_idx => $slot ) :
                                    $time = isset( $slot['time'] ) ? $slot['time'] : '';
                                    ?>
                                    <tr class="cw-time-row" data-row-id="<?php echo esc_attr( $s_idx ); ?>">
                                        <td style="text-align: center;">
                                            <input type="text"
                                                   class="cw-row-time"
                                                   name="cw_schedule_slots[<?php echo esc_attr( $s_idx ); ?>][time]"
                                                   value="<?php echo esc_attr( $time ); ?>"
                                                   placeholder="06:30">
                                        </td>
                                        <?php foreach ( $default_keys as $day_key ) : 
                                            $class_val = isset( $slot['classes'][ $day_key ] ) ? $slot['classes'][ $day_key ] : '';
                                        ?>
                                            <td>
                                                <input type="text"
                                                       class="cw-row-class"
                                                       name="cw_schedule_slots[<?php echo esc_attr( $s_idx ); ?>][classes][<?php echo esc_attr( $day_key ); ?>]"
                                                       value="<?php echo esc_attr( $class_val ); ?>"
                                                       placeholder="&mdash;">
                                            </td>
                                        <?php endforeach; ?>
                                        <td>
                                            <div class="cw-action-btns">
                                                <button type="button" class="cw-btn-dup" title="<?php esc_attr_e( 'Duplicate this row', 'codyweb-child' ); ?>">&#x2398;</button>
                                                <button type="button" class="cw-btn-del" title="<?php esc_attr_e( 'Delete this time row', 'codyweb-child' ); ?>">&times;</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                endforeach;
                            else :
                                ?>
                                <tr class="cw-time-row" data-row-id="0">
                                    <td style="text-align: center;">
                                        <input type="text" class="cw-row-time" name="cw_schedule_slots[0][time]" value="" placeholder="06:30">
                                    </td>
                                    <?php foreach ( $default_keys as $day_key ) : ?>
                                        <td>
                                            <input type="text" class="cw-row-class" name="cw_schedule_slots[0][classes][<?php echo esc_attr( $day_key ); ?>]" value="" placeholder="&mdash;">
                                        </td>
                                    <?php endforeach; ?>
                                    <td>
                                        <div class="cw-action-btns">
                                            <button type="button" class="cw-btn-dup" title="<?php esc_attr_e( 'Duplicate this row', 'codyweb-child' ); ?>">&#x2398;</button>
                                            <button type="button" class="cw-btn-del" title="<?php esc_attr_e( 'Delete this time row', 'codyweb-child' ); ?>">&times;</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="cw-table-bottom-bar">
                    <button type="button" class="button button-secondary" id="cw-add-row-btn" style="font-weight: 600;">
                        + <?php esc_html_e( 'Add Another Time Slot', 'codyweb-child' ); ?>
                    </button>
                    <button type="button" class="button" id="cw-sort-rows-btn">
                        <span class="dashicons dashicons-sort" style="vertical-align: middle; margin-top: -2px;"></span> <?php esc_html_e( 'Sort Rows Chronologically', 'codyweb-child' ); ?>
                    </button>
                </div>
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

    <!-- Interactive script to add, duplicate, remove, and sort time rows -->
    <script>
    (function($){
        $(document).ready(function(){
            var defaultKeys = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

            function buildRowHtml(idx, timeVal, classes) {
                timeVal = timeVal || '';
                classes = classes || {};
                var html = '<tr class="cw-time-row" data-row-id="' + idx + '">';
                html += '<td style="text-align: center;"><input type="text" class="cw-row-time" name="cw_schedule_slots[' + idx + '][time]" value="' + $('<div>').text(timeVal).html() + '" placeholder="06:30"></td>';

                $.each(defaultKeys, function(i, key){
                    var val = classes[key] || '';
                    html += '<td><input type="text" class="cw-row-class" name="cw_schedule_slots[' + idx + '][classes][' + key + ']" value="' + $('<div>').text(val).html() + '" placeholder="&mdash;"></td>';
                });

                html += '<td><div class="cw-action-btns">' +
                    '<button type="button" class="cw-btn-dup" title="<?php echo esc_js( __( 'Duplicate this row', 'codyweb-child' ) ); ?>">&#x2398;</button>' +
                    '<button type="button" class="cw-btn-del" title="<?php echo esc_js( __( 'Delete this time row', 'codyweb-child' ) ); ?>">&times;</button>' +
                    '</div></td>';
                html += '</tr>';
                return html;
            }

            // Add Time Slot
            function addRow(focus) {
                var idx = Date.now();
                var rowHtml = buildRowHtml(idx, '', {});
                var $newRow = $(rowHtml).hide();
                $('#cw-schedule-tbody').append($newRow);
                $newRow.fadeIn(150);
                if (focus) {
                    $newRow.find('.cw-row-time').focus();
                }
            }

            $('#cw-add-row-btn, #cw-add-row-top-btn').on('click', function(e){
                e.preventDefault();
                addRow(true);
            });

            // Duplicate Row
            $(document).on('click', '.cw-btn-dup', function(e){
                e.preventDefault();
                var $sourceRow = $(this).closest('tr');
                var timeVal = $sourceRow.find('.cw-row-time').val();
                var classes = {};

                $.each(defaultKeys, function(i, key){
                    classes[key] = $sourceRow.find('input[name*="[classes][' + key + ']"]').val();
                });

                var idx = Date.now();
                var rowHtml = buildRowHtml(idx, timeVal, classes);
                var $newRow = $(rowHtml).hide();
                $sourceRow.after($newRow);
                $newRow.fadeIn(150);
                $newRow.find('.cw-row-time').focus();
            });

            // Delete Row
            $(document).on('click', '.cw-btn-del', function(e){
                e.preventDefault();
                var $tr = $(this).closest('tr');
                if ($('#cw-schedule-tbody tr').length > 1) {
                    $tr.fadeOut(150, function(){
                        $tr.remove();
                    });
                } else {
                    $tr.find('input').val('');
                }
            });

            // Sort Chronologically
            function sortRows() {
                var $rows = $('#cw-schedule-tbody tr.cw-time-row').get();
                $rows.sort(function(a, b){
                    var timeA = $(a).find('.cw-row-time').val().trim();
                    var timeB = $(b).find('.cw-row-time').val().trim();
                    return timeA.localeCompare(timeB, undefined, { numeric: true, sensitivity: 'base' });
                });
                $.each($rows, function(index, row){
                    $('#cw-schedule-tbody').append(row);
                });
            }

            $('#cw-sort-rows-btn, #cw-sort-rows-top-btn').on('click', function(e){
                e.preventDefault();
                sortRows();
            });
        });
    })(jQuery);
    </script>
    <?php
}
