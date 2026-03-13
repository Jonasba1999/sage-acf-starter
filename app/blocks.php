<?php

namespace App;

use function Roots\asset;

/* Block registration */

add_action('init', function () {

    add_filter('block_categories_all', function ($categories) {

        array_push($categories, [
            'slug'  => 'brand-category',
            'title' => __('Brand Blocks', THEME_TEXTDOMAIN),
            'icon'  => 'dashicons-admin-customizer',
        ]);

        array_push($categories, [
            'slug'  => 'content-category',
            'title' => __('Content Blocks', THEME_TEXTDOMAIN),
            'icon'  => 'dashicons-admin-customizer',
        ]);

        return $categories;
    });

    foreach (glob(get_theme_file_path('/app/Blocks/*/block.json')) as $block_json) {
        $block = json_decode(file_get_contents($block_json), true);

        register_block_type($block_json, [
            'render_callback' => function ($block) {
                $slug = str_replace('acf/', '', $block['name']);
                if (file_exists($template = get_theme_file_path("resources/views/blocks/{$slug}.blade.php"))) {
                    echo view("blocks.{$slug}", ['block' => $block])->render();
                }
            }
        ]);
    }
});

/* ACF block type registration */
add_filter(
    'allowed_block_types_all',
    function ($allow, $post) {

        $allow = [];

        $defaults = [
            // 'core/block',
            // 'core/html',
            // 'core/quote',
            // 'acf/gallery',
            // 'core/image',
            // 'core/table',
            // 'core/paragraph',
            // 'core/heading',
            // 'core/list-item',
            // 'core/list',
            // 'woocommerce/classic-shortcode',
        ];

        foreach (glob(get_theme_file_path('/app/Blocks/*/block.json')) as $block_json) {
            $block = json_decode(file_get_contents($block_json), true);
            $allow[] = $block['name'];
        }

        $allow = array_merge($allow, $defaults);

        return $allow;
    },
    10,
    2
);

/* ACF block rendering */
function renderAcfBlock($block) {
    $slug = str_replace('acf/', '', $block['name']);
    // Renders block editor preview image
    if (isset($block['data']['is_preview'])) {
        $relative = str_replace(get_theme_file_path('/'), '', $block['path']);
        echo '<img style="max-width: 100%; height: auto;" src="' . get_theme_file_uri('/' . $relative . '/preview.jpg') . '" alt="">';
    } else {
        global $block_index;
        $block_index++;
        if (file_exists(get_theme_file_path("resources/views/blocks/{$slug}.blade.php"))) {
            echo view("blocks.{$slug}", ['block' => $block])->render();
        }
    };
}
