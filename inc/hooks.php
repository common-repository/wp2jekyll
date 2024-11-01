<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

add_action('save_post',         'wordpress2jekyll_write_post');
add_action('post_updated',      'wordpress2jekyll_update_post', 10, 3);
add_action('delete_post',       'wordpress2jekyll_write_post');
add_action('wp_untrash_post',   'wordpress2jekyll_undelete_post');
add_action('comment_closed',    'wordpress2jekyll_write_post');
add_action('publish_post',      'wordpress2jekyll_write_post', 10, 2);

add_action('profile_update', 'wordpress2jekyll_user_update', 10, 2);

add_action('edit_terms', 'wordpress2jekyll_taxonomy_update', 10, 2);

function wordpress2jekll_save_post($post_id)
{
    if(!get_option('jekyll_enable_auto_build')) return false;

    $wordpress2jekyll = new \wordpress2jekyll\build();
    $wordpress2jekyll->process_post($post_id);
}

function wordpress2jekyll_update_post($post_id, $post_after, $post_before) {

    if(!get_option('jekyll_enable_auto_build')) return false;

    //If these values have changed, it's easier to regenerate all the posts.
    //@todo Check tags and categories, if they've changed then process all posts due to potential new url structure.
    if($post_after->post_status <> $post_before->post_status
        || $post_after->post_date <> $post_before->post_date
    )
    {
        $this->process_posts();
    }
    else {
        $wordpress2jekyll = new \wordpress2jekyll\build();
        $wordpress2jekyll->process_post($post_id);
    }
}

function wordpress2jekyll_delete_post($post_id) {

    if(!get_option('jekyll_enable_auto_build')) return false;
    $this->process_posts();
}
function wordpress2jekyll_undelete_post($post_id) {

    if(!get_option('jekyll_enable_auto_build')) return false;
    $this->process_posts();
}

function wordpress2jekyll_write_post($post_id)
{
    if(!get_option('jekyll_enable_auto_build')) return false;

    $wordpress2jekyll = new \wordpress2jekyll\build();
    $wordpress2jekyll->process_post($post_id);
}

function wordpress2jekyll_user_update($user_id, $old_user_data)
{
    if(!get_option('jekyll_enable_auto_build')) return false;
    if(!get_option('jekyll_export_users')) return false;

    $this->process_authors();
}

function wordpress2jekyll_taxonomy_update($term_id, $taxonomy_id)
{
    if(!get_option('jekyll_enable_auto_build')) return false;
    if(!get_option('jekyll_export_taxonomies')) return false;
    $taxonomies_to_export = get_option('jekyll_taxonomies');

    if(isset($taxonomies_to_export[$taxonomy_id])) {
        $wordpress2jekyll = new \wordpress2jekyll\build();
        $wordpress2jekyll->process_taxonomy($taxonomy_id);
    }
}