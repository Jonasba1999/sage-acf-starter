<?php

/**
 * Theme filters.
 */

namespace App;


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
