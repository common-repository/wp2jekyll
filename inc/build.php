<?php
namespace wordpress2jekyll;

// If this file is called directly, abort.

use wordpress2jekyll\export\export;
use wordpress2jekyll\export\post;
use wordpress2jekyll\export\authors;

if ( ! defined( 'WPINC' ) ){
    die;
}

class build
{

    private $cms = null;
    private $jekyll = null;

    public function __construct()
    {
        $this->cms = new cms();

        $post_types = array('post');
        if (get_option('jekyll_save_pages')) {
            $post_types[] = 'page';
        }
        $this->cms->set_post_types($post_types);

        $this->jekyll = new \jekyll();

        $jekyll_path = get_home_path() . get_option('jekyll_path', get_home_path() . '_jekyll/');
        $this->jekyll->set_path($jekyll_path);

        $this->jekyll->set_assets_directory(get_option('jekyll_assets_directory',   '_assets'));
        $this->jekyll->set_data_directory(  get_option('jekyll_data_directory',     '_data'));
        $this->jekyll->set_posts_directory( get_option('jekyll_posts_directory',    '_posts'));

        if (get_option('jekyll_export_taxonomies')) {
            $taxonomies = get_option('jekyll_taxonomies', array());
            $this->cms->set_taxonomies(array_keys($taxonomies));
        }
    }

    public function full_build()
    {
        if(get_option( 'jekyll_export_users', 0)) {
            $this->process_authors();
        }

        $this->process_posts();

        if (get_option('jekyll_export_taxonomies', 0)) {
            $this->process_taxonomies();
        }
    }

    /*
    * USERS
    */

    public function process_authors()
    {
        $authors = $this->get_authors();
        if ($authors) {
            $this->export_authors($authors);
        }

    }

    private function get_authors()
    {
        $authors = $this->cms->get_authors();

        return $authors;
    }

    private function export_authors($authors)
    {
        $this->jekyll->set_authors($authors);
        $this->jekyll->write('authors');

        return true;
    }

    /*
     * POSTS
     */
    public function process_posts()
    {
        $posts = $this->get_posts();
        if ($posts) {
            $this->export_posts($posts);
        }

    }

    public function process_post($post_id)
    {
        $post = $this->get_post($post_id);
        $this->export_post($post);
    }

    private function get_post($post_id)
    {
        $post = $this->cms->get_post($post_id);

        return $post;
    }

    private function get_posts()
    {
        $posts = $this->cms->get_posts();

        return $posts;
    }

    private function export_posts($posts)
    {
        $this->jekyll->set_posts($posts);
        $this->jekyll->write('posts');
    }

    private function export_post($post)
    {
        $this->jekyll->add_post($post);
        $this->jekyll->write('post');
    }

    /*
     * TAXONOMIES
     */
    public function process_taxonomy($taxonomy_id)
    {
        $taxonomy = $this->get_taxonomy($taxonomy_id);
        $taxonomy_terms = $this->get_taxonomy_terms($taxonomy['id']);

        $this->export_taxonomy($taxonomy, $taxonomy_terms);
    }

    public function process_taxonomies()
    {
        $taxomomies = $this->get_taxonomies();
        if ($taxomomies) {
            foreach($taxomomies as $taxonomy_id => $taxonomy) {
                $taxonomy_terms = $this->get_taxonomy_terms($taxonomy['id']);
                $this->export_taxonomy($taxonomy, $taxonomy_terms);
            }
        }
    }

    private function get_taxonomy($taxonomy_id)
    {
        $taxonomy = $this->cms->get_taxonomy($taxonomy_id);

        return $taxonomy;
    }

    private function get_taxonomies()
    {
        $taxonomies = $this->cms->get_taxonomies();

        return $taxonomies;
    }

    private function get_taxonomy_terms($taxonomy_id)
    {
        $terms = $this->cms->get_taxonomy_terms($taxonomy_id);

        return $terms;
    }

    private function export_taxonomy($taxonomy, $taxonomy_terms)
    {
        $this->jekyll->add_taxonomy($taxonomy, $taxonomy_terms);
        $this->jekyll->write('taxonomies');
    }
}
