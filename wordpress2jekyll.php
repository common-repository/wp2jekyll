<?php
/*
Plugin Name: WordPress2Jekyll
Plugin URI:  https://wordpress.org/plugins/wp2jekyll/
Description: This allows you to use WordPress as an interface to Jekyll. It will save posts, taxonomies and author information out in a Jekyll friendly format.
Version:     0.4
Author:      Liam Bowers
Author URI:  http://liam.pw
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

//Activation hook

register_activation_hook(__FILE__, 'wordpress2jekyll_activate');

function wordpress2jekyll_activate()
{
    update_option('jekyll_path',                        '../_jekyll');
    update_option('jekyll_posts_directory',             '_posts');
    update_option('jekyll_assets_directory',            '_assets');
    update_option('jekyll_data_directory',              '_data');
    update_option('jekyll_enable_auto_build',           0);
    update_option('jekyll_save_pages',                  0);
    update_option('jekyll_use_wordpress_permalinks',    1);
    update_option('jekyll_wordpress_preprocess_content',1);
    update_option('jekyll_export_post_meta',            0);
    update_option('jekyll_export_users',                0);
    update_option('jekyll_export_taxonomies',           0);
    update_option('jekyll_taxonomies',                  array());
}

// Need to store this variable before leaving this file
define( '__W2J_FILE__', __FILE__ );

if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
    if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if ( ! is_plugin_active( plugin_basename( __FP_FILE__ ) ) ){
            wp_print_styles( 'open-sans' );
            echo "<style>body{margin: 0 2px;font-family: 'Open Sans',sans-serif;font-size: 13px;line-height: 1.5em;}</style>";
            echo wp_kses_post( __( '<b>Wordpress2Jekyll</b> requires PHP 5.3 or higher, and the plugin has now disabled itself.', 'wordpress2jekyll' ) ) .
                '<br />' .
                esc_attr__( 'Contact your Hosting or your system administrator and ask for this Upgrade to version 5.3 of PHP.', 'wordpress2jekyll' );
            exit();
        }

        deactivate_plugins( __W2J_FILE__ );
    }
} else {

    require_once plugin_dir_path(__W2J_FILE__) . 'inc' . DIRECTORY_SEPARATOR . 'load.php';

}