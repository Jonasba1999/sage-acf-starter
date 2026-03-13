<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});


/**
 * Bump ACF blocks to V3
 * 
 * @return int
 */
add_filter('acf/blocks/default_block_version', function () {
    return 3;
}, 10, 2);


/**
 * Enable SVG uploads for admins
 */
add_filter('upload_mimes', function ($mimes) {
    if (current_user_can('administrator')) {
        $mimes['svg'] = 'image/svg+xml';
        $mimes['svgz'] = 'image/svg+xml';
    }
    return $mimes;
});
