<?php
// Unregister the native Posts post type
add_action('init', function () {
    global $wp_post_types;
    if (isset($wp_post_types['post'])) {
        unset($wp_post_types['post']);
    }
});

// Remove Posts and Comments from admin menu
add_action('admin_menu', function () {

    // Remove default menu items
    remove_menu_page('edit.php'); // Posts
    remove_menu_page('edit-comments.php'); // Comments
    remove_submenu_page('themes.php', 'site-editor.php'); // Site Editor
    remove_submenu_page('themes.php', 'customize.php'); // Requires CSS hide as well
    remove_submenu_page('themes.php', 'site-editor.php?p=/pattern');


    // Production environment restrictions
    if (defined('WP_ENV') && WP_ENV === 'production') {
        remove_menu_page('edit.php?post_type=acf-field-group'); // ACF
    }
});

// Appearance -> Customize link removal - CSS method, because WP adds it dynamically
add_action('admin_head', function () {
    echo '<style>
        #customize-link, 
        a[href*="customize.php"] { display: none !important; }
    </style>';
});

// Remove Customize and Comments from admin bar
add_action('wp_before_admin_bar_render', function () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('customize');
    $wp_admin_bar->remove_menu('comments');
});

// Disable Customizer loader scripts in admin area
add_action('admin_init', function () {
    remove_action('plugins_loaded', '_wp_customize_include', 10);
    remove_action('admin_enqueue_scripts', '_wp_customize_loader_settings', 11);
});

// Disable access to Customizer and Site Editor directly via URL
add_action('load-customize.php', function () {
    wp_die(__('The Customizer is disabled.', THEME_TEXTDOMAIN));
});

add_action('load-site-editor.php', function () {
    wp_die(__('The Site Editor is disabled.', THEME_TEXTDOMAIN));
});

// Remove Post 'Hello World!' whitch is created by default
add_action('after_setup_theme', function () {

    $page_slug = 'hello-world';

    $query = new WP_Query([
        'name'              => $page_slug,
        'post_type'         => 'post',
        'post_status'       => 'any',
        'posts_per_page'    => 1,
    ]);

    $default_post = $query->have_posts() ? $query->posts[0] : null;

    if ($default_post) {
        wp_delete_post($default_post->ID, true); // permanently delete
    }
});


/**
 * Disable "Available to Install" block suggestions.
 *
 * @link https://github.com/n7studios/disable-block-pattern-suggestions
 */
function themeDisableBlockSuggestions() {
    remove_action(
        'enqueue_block_editor_assets',
        'wp_enqueue_editor_block_directory_assets'
    );
    remove_action(
        'enqueue_block_editor_assets',
        'gutenberg_enqueue_block_editor_assets_block_directory'
    );
}

add_action('admin_init', 'themeDisableBlockSuggestions');
