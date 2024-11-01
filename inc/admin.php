<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

add_action( 'admin_menu', 'wordpress2jekyll_admin_menu' );
add_action( 'admin_init', 'wordpress2jekyll_settings_init' );


function wordpress2jekyll_admin_menu(  ) {

    add_options_page('WordPress2Jekyll',   'WordPress2Jekyll', 'manage_options',   'wordpress2jekyll', 'wordpress2jekyll_options_page');

    add_submenu_page('tools.php',           'Build',           'Jekyll',            'administrator',    'wordpress2jekyll-build',         'wordpress2jekyll_build_page',          'dashicons-migrate');

}

function wordpress2jekyll_settings_init(  ) {

    //Jekyll General
    register_setting( 'wordpress2jekyll_settings', 'jekyll_path',                       'wordpress2jekyll_verify_jekyll_path');
    register_setting( 'wordpress2jekyll_settings', 'jekyll_assets_directory',           'wordpress2jekyll_verify_assets_directory');
    register_setting( 'wordpress2jekyll_settings', 'jekyll_data_directory',             'wordpress2jekyll_verify_data_directory');
    register_setting( 'wordpress2jekyll_settings', 'jekyll_posts_directory',            'wordpress2jekyll_verify_posts_directory');

    //Export
    register_setting( 'wordpress2jekyll_settings', 'jekyll_enable_auto_build',          'wordpress2jekyll_sanitise_checkbox');

    //Posts
    register_setting( 'wordpress2jekyll_settings', 'jekyll_save_pages',                 'wordpress2jekyll_sanitise_checkbox');
    register_setting( 'wordpress2jekyll_settings', 'jekyll_use_wordpress_permalinks',   'wordpress2jekyll_sanitise_checkbox');
    register_setting( 'wordpress2jekyll_settings', 'jekyll_wordpress_preprocess_content',  'wordpress2jekyll_sanitise_checkbox');
    register_setting( 'wordpress2jekyll_settings', 'jekyll_export_post_meta',           'wordpress2jekyll_sanitise_checkbox');

    //Users
    register_setting( 'wordpress2jekyll_settings', 'jekyll_export_users',               'wordpress2jekyll_sanitise_checkbox');

    //Taxonomies
    register_setting( 'wordpress2jekyll_settings', 'jekyll_export_taxonomies',          'wordpress2jekyll_sanitise_checkbox');
    register_setting( 'wordpress2jekyll_settings', 'jekyll_taxonomies',                 'wordpress2jekyll_verify_taxonomies');

    ////// Jekyll
    add_settings_section(
        'wordpress2jekyll_jekyll_settings_section',
        __( 'Jekyll', 'wordpress2jekyll' ),
        'wordpress2jekyll_jekyll_settings_section_callback',
        'wordpress2jekyll_settings'
    );

    add_settings_field(
        'jekyll_path',
        __( 'Jekyll Path', 'wordpress2jekyll' ),
        'wordpress2jekyll_jekyll_path_render',
        'wordpress2jekyll_settings',
        'wordpress2jekyll_jekyll_settings_section'
    );

    add_settings_field(
        'jekyll_posts_directory',
        __( 'Posts directory', 'wordpress2jekyll' ),
        'wordpress2jekyll_jekyll_posts_directory_render',
        'wordpress2jekyll_settings',
        'wordpress2jekyll_jekyll_settings_section'
    );

    add_settings_field(
        'jekyll_assets_directory',
        __( 'Assets directory', 'wordpress2jekyll' ),
        'wordpress2jekyll_jekyll_assets_directory_render',
        'wordpress2jekyll_settings',
        'wordpress2jekyll_jekyll_settings_section'
    );

    add_settings_field(
        'jekyll_data_directory',
        __( 'Data directory', 'wordpress2jekyll' ),
        'wordpress2jekyll_jekyll_data_directory_render',
        'wordpress2jekyll_settings',
        'wordpress2jekyll_jekyll_settings_section'
    );
    /////// Export
    add_settings_section(
        'wordpress2jekyll_export_settings_section',
        __( 'Export', 'wordpress2jekyll' ),
        'wordpress2jekyll_export_settings_section_callback',
        'wordpress2jekyll_settings'
    );

    add_settings_field(
        'jekyll_enable_auto_build',
        __( 'Automatically export content', 'wordpress2jekyll' ),
        'wordpress2jekyll_jekyll_enable_auto_build_render',
        'wordpress2jekyll_settings',
        'wordpress2jekyll_export_settings_section'
    );

    /////// Posts
    add_settings_section(
      'wordpress2jekyll_posts_settings_section',
      __( 'Posts', 'wordpress2jekyll' ),
      'wordpress2jekyll_posts_settings_section_callback',
      'wordpress2jekyll_settings'
    );

    add_settings_field(
        'jekyll_save_pages',
        __( 'Save page post type', 'wordpress2jekyll' ),
        'wordpress2jekyll_jekyll_save_pages_render',
        'wordpress2jekyll_settings',
        'wordpress2jekyll_posts_settings_section'
    );

    add_settings_field(
        'jekyll_use_wordpress_permalinks',
        __( 'Use the Wordpress Permalinks (Overrides Jekyll)', 'wordpress2jekyll' ),
        'wordpress2jekyll_jekyll_use_wordpress_permalinks_render',
        'wordpress2jekyll_settings',
        'wordpress2jekyll_posts_settings_section'
    );

    add_settings_field(
        'jekyll_wordpress_preprocess_content',
        __( 'Allow Wordpress to preprocess the content', 'wordpress2jekyll' ),
        'wordpress2jekyll_jekyll_wordpress_preprocess_content_render',
        'wordpress2jekyll_settings',
        'wordpress2jekyll_posts_settings_section'
    );

    add_settings_field(
      'jekyll_export_post_meta',
      __( 'Export post meta data', 'wordpress2jekyll' ),
      'wordpress2jekyll_jekyll_export_post_meta_render',
      'wordpress2jekyll_settings',
      'wordpress2jekyll_posts_settings_section'
    );

    /////// Users
    add_settings_section(
      'wordpress2jekyll_users_settings_section',
      __( 'Users', 'wordpress2jekyll' ),
      'wordpress2jekyll_users_settings_section_callback',
      'wordpress2jekyll_settings'
    );

    add_settings_field(
      'jekyll_export_users',
      __( 'Export Users', 'wordpress2jekyll' ),
      'wordpress2jekyll_jekyll_export_users_render',
      'wordpress2jekyll_settings',
      'wordpress2jekyll_users_settings_section'
    );

    /////// Taxonomies
    add_settings_section(
      'wordpress2jekyll_taxonomy_settings_section',
      __( 'Taxonomy', 'wordpress2jekyll' ),
      'wordpress2jekyll_taxonomy_settings_section_callback',
      'wordpress2jekyll_settings'
    );

    add_settings_field(
      'jekyll_export_taxonomies',
      __( 'Export Taxonomies', 'wordpress2jekyll' ),
      'wordpress2jekyll_jekyll_export_taxonomies_render',
      'wordpress2jekyll_settings',
      'wordpress2jekyll_taxonomy_settings_section'
    );

    add_settings_field(
      'jekyll_taxonomies',
      __( 'Taxonomies', 'wordpress2jekyll' ),
      'wordpress2jekyll_jekyll_taxonomies_render',
      'wordpress2jekyll_settings',
      'wordpress2jekyll_taxonomy_settings_section'
    );
}


function wordpress2jekyll_jekyll_path_render(  ) {

    $option = get_option( 'jekyll_path' );
    ?>
    <input type='text' name='jekyll_path' value='<?php echo $option; ?>'>
    <?php

}

function wordpress2jekyll_jekyll_posts_directory_render(  ) {

    $option = get_option( 'jekyll_posts_directory' );
    ?>
    <input type='text' name='jekyll_posts_directory' value='<?php echo $option; ?>'>
    <?php

}

function wordpress2jekyll_jekyll_assets_directory_render(  ) {

    $option = get_option( 'jekyll_assets_directory' );
    ?>
    <input type='text' name='jekyll_assets_directory' value='<?php echo $option; ?>'>
    <?php

}

function wordpress2jekyll_jekyll_data_directory_render(  ) {

    $option = get_option( 'jekyll_data_directory' );
    ?>
    <input type='text' name='jekyll_data_directory' value='<?php echo $option; ?>'>
    <?php

}

function wordpress2jekyll_jekyll_enable_auto_build_render(  ) {

    $option = get_option( 'jekyll_enable_auto_build' );
    ?>
    <input type='checkbox' name='jekyll_enable_auto_build' <?php checked( $option, true ); ?> value='1'>
    <?php

}

function wordpress2jekyll_jekyll_save_pages_render(  ) {

    $option = get_option( 'jekyll_save_pages' );
    ?>
    <input type='checkbox' name='jekyll_save_pages' <?php checked( $option, true ); ?> value='1'>
    <?php

}

function wordpress2jekyll_jekyll_use_wordpress_permalinks_render(  ) {

    $option = get_option( 'jekyll_use_wordpress_permalinks' );
    ?>
    <input type='checkbox' name='jekyll_use_wordpress_permalinks' <?php checked( $option, true ); ?> value='1'>
    <?php

}

function wordpress2jekyll_jekyll_wordpress_preprocess_content_render(  ) {

    $option = get_option( 'jekyll_wordpress_preprocess_content' );
    ?>
    <input type='checkbox' name='jekyll_wordpress_preprocess_content' <?php checked( $option, true ); ?> value='1'>
    <?php

}

function wordpress2jekyll_jekyll_export_post_meta_render(  ) {

    $option = get_option( 'jekyll_export_post_meta' );
    ?>
    <input type='checkbox' name='jekyll_export_post_meta' <?php checked( $option, true ); ?> value='1'>
    <?php

}

function wordpress2jekyll_jekyll_export_users_render() {
    $option = get_option( 'jekyll_export_users' );
    ?>
    <input type='checkbox' name='jekyll_export_users' <?php checked( $option, true ); ?> value='1'>
    <?php

}

function wordpress2jekyll_jekyll_export_taxonomies_render() {
    $option = get_option( 'jekyll_export_taxonomies' );
    ?>
    <input type='checkbox' name='jekyll_export_taxonomies' <?php checked( $option, true ); ?> value='1'>
    <?php

}

function wordpress2jekyll_jekyll_taxonomies_render(  ) {

    $options = get_option('jekyll_taxonomies');

    $taxonomies = get_taxonomies(array(), 'objects');
    foreach($taxonomies as $taxonomy) {

        $option = isset($options[$taxonomy->name]) ? TRUE : FALSE;
        ?>
        <input type='checkbox' name='jekyll_taxonomies[<?php echo $taxonomy->name ?>]' <?php checked($option, TRUE); ?> value='1'>
        <strong><?php echo $taxonomy->label ?></strong><br />
        <?php
    }

}

//Option sections callbacks
function wordpress2jekyll_jekyll_settings_section_callback( $arg ) {

    echo __( 'Configure Jekyll', 'wordpress2jekyll' );
}

function wordpress2jekyll_export_settings_section_callback( $arg ) {

    echo __( 'How WordPress2Jekyll should export Wordpress content.', 'wordpress2jekyll' );
}


function wordpress2jekyll_posts_settings_section_callback( $arg ) {

    echo __( 'Post related export settings.', 'wordpress2jekyll' );
}

function wordpress2jekyll_users_settings_section_callback( $arg ) {

    echo __( 'Fancy exporting your users too? ', 'wordpress2jekyll' );
}

function wordpress2jekyll_taxonomy_settings_section_callback( $arg ) {

    echo __( 'Settings related to the exportation of taxonomy terms in to the data directory in a yml format.', 'wordpress2jekyll' );
}

function wordpress2jekyll_options_page(  ) {

    ?>
    <form action='options.php' method='post'>

        <h2>WordPress2Jekyll Options</h2>
        <?php
        settings_fields( 'wordpress2jekyll_settings' );
        do_settings_sections( 'wordpress2jekyll_settings' );
        submit_button();
        ?>

    </form>
    <?php

}

function wordpress2jekyll_verify_jekyll_path($value)
{
    $value = trim($value);

    if(empty($value))
    {
        add_settings_error(
            'jekyll_path',
            esc_attr( 'settings_updated' ),
            'The Jekyll path can not be empty',
            'error'
        );

        return false;
    }

    $jekyll = new \jekyll();

    $jekyll->set_path(get_home_path() . $value);

    if($jekyll->exists('jekyll'))
    {
            add_settings_error(
                'jekyll_path',
                esc_attr( 'settings_updated' ),
                'Jekyll was found!',
                'updated'
            );
    }
    else
    {
        add_settings_error(
            'jekyll_path',
            esc_attr( 'settings_updated' ),
            'Unable to find a Jekyll at this location (' . ($jekyll->get_path()) . ')<br> This path must be relative to the root Wordpress directory. This directory expects the assets, data and posts directories to exist.',
            'error'
        );
    }

    return $value;
}

function wordpress2jekyll_verify_assets_directory($value)
{
    $value = trim($value);

    if(empty($value))
    {
        add_settings_error(
            'jekyll_assets_directory',
            esc_attr( 'settings_updated' ),
            'The assets directory can not be empty',
            'error'
        );

        return false;
    }

    $jekyll = new \jekyll();

    $jekyll->set_path(get_home_path() . $_POST['jekyll_path']);

    $jekyll->set_assets_directory($_POST['jekyll_assets_directory']);

    if($jekyll->exists('assets'))
    {
        if(!$jekyll->check_file_permissions('assets'))
        {
            add_settings_error(
                'jekyll_assets_directory',
                esc_attr( 'settings_updated' ),
                'Unable to write to the assets directory (' . realpath($jekyll->get_path('assets')) . ')',
                'error'
            );
        }
    }
    else
    {
        add_settings_error(
            'jekyll_assets_directory',
            esc_attr( 'settings_updated' ),
            'Unable to find the assets directory at this location (' . ($jekyll->get_path('assets')) . ')',
            'error'
        );
    }

    return $value;
}

function wordpress2jekyll_verify_data_directory($value)
{
    $value = trim($value);

    if(empty($value))
    {
        add_settings_error(
            'jekyll_data_directory',
            esc_attr( 'settings_updated' ),
            'The data directory can not be empty',
            'error'
        );

        return false;
    }

    $jekyll = new \jekyll();

    $jekyll->set_path(get_home_path() . $_POST['jekyll_path']);

    $jekyll->set_data_directory($value);

    if($jekyll->exists('data'))
    {
        if(!$jekyll->check_file_permissions('data'))
        {
            add_settings_error(
                'jekyll_data_directory',
                esc_attr( 'settings_updated' ),
                'Unable to write to the data directory (' . realpath($jekyll->get_path('data')) . ')',
                'error'
            );
        }
    }
    else
    {
        add_settings_error(
            'jekyll_data_directory',
            esc_attr( 'settings_updated' ),
            'Unable to find the data directory at this location (' . ($jekyll->get_path('data')) . ')',
            'error'
        );
    }

    return $value;
}

function wordpress2jekyll_verify_posts_directory($value)
{
    $value = trim($value);

    if(empty($value))
    {
        add_settings_error(
            'jekyll_posts_directory',
            esc_attr( 'settings_updated' ),
            'The posts directory can not be empty',
            'error'
        );

        return false;
    }

    $jekyll = new \jekyll();

    $jekyll->set_path(get_home_path() . $_POST['jekyll_path']);

    $jekyll->set_posts_directory($value);

    if($jekyll->exists('posts'))
    {
        if(!$jekyll->check_file_permissions('posts'))
        {
            add_settings_error(
                'jekyll_posts_directory',
                esc_attr( 'settings_updated' ),
                'Unable to write to the posts directory (' . realpath($jekyll->get_path('posts')) . ')',
                'error'
            );
        }
    }
    else
    {
        add_settings_error(
            'jekyll_posts_directory',
            esc_attr( 'settings_updated' ),
            'Unable to find the posts directory at this location (' . ($jekyll->get_path('posts')) . ')',
            'error'
        );
    }

    return $value;
}

function wordpress2jekyll_sanitise_checkbox($value)
{
    return $value == 1 ?: 0;
}

function wordpress2jekyll_verify_taxonomies( $values ) {
    $filtered_values = array();

    if ( ! empty( $values ) ) {
        $taxonomies = get_taxonomies( array(), 'objects' );

        foreach ( $values as $taxonomy_id => $value ) {
            if ( isset( $taxonomies[ $taxonomy_id ] ) ) {
                $filtered_values[ $taxonomy_id ] = true;
            }
        }
    }

    return $filtered_values;
}

/*
 * Build Page
 */

function wordpress2jekyll_build_page() {
    //@todo Create a better settings page with errors
    ?>
    <div class="wrap">
        <h2><?php _e('WordPress2Jekyll Build', 'wordpress2jekyll') ?></h2>

        <form method="post" action="admin.php?page=wordpress2jekyll-build">
            <p>Export all WordPress posts and authors in to the Jekyll directory.</p>

            <?php submit_button('Export', 'primary', 'export'); ?>

        </form>
    </div>
    <?php

    if(isset($_POST['export']))
    {
        echo '<li>Exporting';
        $wordpress2jekyll = new \wordpress2jekyll\build();
        $wordpress2jekyll->full_build();
        echo '<li>Done.';
    }
}
?>