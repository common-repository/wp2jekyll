<?php
/*
 * Cleanup Wordpress after an uninstall
 */

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

$options = array(
                'jekyll_path',
                'jekyll_posts_directory',
                'jekyll_assets_directory',
                'jekyll_data_directory',
                'jekyll_enable_auto_build',
                'jekyll_save_pages',
                'jekyll_use_wordpress_permalinks',
                'jekyll_wordpress_preprocess_content',
                'jekyll_export_post_meta',
                'jekyll_export_users',
                'jekyll_export_taxonomies',
                'jekyll_taxonomies'
);

foreach($options as $option)
{
    delete_option($option);
}

?>