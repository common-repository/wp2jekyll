<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

class jekyll
{
    private $jekyll_path = '';

    private $assets_directory = '_assets';
    private $data_directory = '_data';
    private $posts_directory = '_posts';

    private $purge_posts_directory = true;  //Placeholder

    private $data = array();

    public function __construct()
    {

    }

    public function set_path($path)
    {
        $path = rtrim($path, '/');

        $this->jekyll_path = $path;
    }

    public function set_assets_directory($path)
    {
        $path = trim($path, '/');

        $this->assets_directory = $path;
    }

    public function set_data_directory($path)
    {
        $path = trim($path, '/');

        $this->data_directory = $path;
    }

    public function set_posts_directory($path)
    {
        $path = trim($path, '/');

        $this->posts_directory = $path;
    }

    public function exists($what = null)
    {
        switch($what) {
            case 'assets':
                $paths[] = $this->get_path('assets');
                break;

            case 'data':
                $paths[] = $this->get_path('data');
                break;

            case 'posts':
                $paths[] = $this->get_path('posts');
                break;

            case 'jekyll':
                $paths[] = $this->get_path();
                break;

            case 'all':
            default:

                $paths = array(
                    $this->get_path(),
                    $this->get_path('assets'),
                    $this->get_path('data'),
                    $this->get_path('posts')
                );
        }

        foreach($paths as $path)
        {
            if(!file_exists($path))
            {
                return false;
            }
        }

        return true;    //Best guess
    }

    //Check to see the directories can be written to. If they can't, bad things will happen.
    public function check_file_permissions($what = null)
    {
        $errors = array();

        switch($what) {

            case 'assets':
                $writable_directories[] = $this->get_path('assets');
                break;

            case 'data':
                $writable_directories[] = $this->get_path('data');
                break;

            case 'posts':
                $writable_directories[] = $this->get_path('posts');
                break;

            default:

                $writable_directories = array(
                    'assets_path' => $this->get_path('assets'),
                    'data_path' => $this->get_path('data'),
                    'posts_path' => $this->get_path('posts')
                );
        }
        foreach($writable_directories as $key => $directory)
        {
            //@mkdir($directory, true);     //No need to make the directories. Make the user do it as it's safer.
            if(!wp_is_writable($directory))
            {
                $errors[$key] = $directory . ' is not writable';
            }
        }

        return empty($errors) ? true : $errors;
    }

    public function get_path($what = null)
    {
        switch($what)
        {
            case 'assets':
                return $this->jekyll_path . DIRECTORY_SEPARATOR . $this->assets_directory;

            case 'data':
                return $this->jekyll_path . DIRECTORY_SEPARATOR . $this->data_directory;

            case 'posts':
                return $this->jekyll_path . DIRECTORY_SEPARATOR . $this->posts_directory;

            default:
                return $this->jekyll_path;
        }
    }

    public function write($what = null)
    {
        switch($what)
        {
            case 'authors':

                $this->write_authors();
                break;

            case 'post':        ///Added so that the entire directory won't be purged if just one post was modified.

                $this->write_post();
                break;

            case 'posts':

                $this->write_posts();
                break;

            case 'taxonomies':

                $this->write_taxonomies();
                break;
            //case 'pages':

                //$this->write_pages();
                //break;

            default:
                $this->write_authors();
                $this->write_posts();
                $this->write_taxonomies();
        }
    }

    //Authors
    public function set_authors($data)
    {
        $this->data['authors'] = $data;
    }

    public function write_authors()
    {
        $authors = new \wordpress2jekyll\export\authors($this, $this->data['authors']);
        if($authors->prepare()) {
            $authors->save();
        }
    }

    //Posts
    public function set_posts($data)
    {
        $this->data['posts'] = $data;
    }

    public function add_post($post)
    {
        $this->data['posts'][] = $post;
    }

    public function write_post()
    {
        foreach($this->data['posts'] as $post) {
            $posts = new \wordpress2jekyll\export\post($this, $post);
            if ($posts->prepare()) {
                $posts->save();
            }
        }
    }

    public function write_posts()
    {
        if($this->purge_posts_directory)
        {
            $this->purge_posts_directory();
        }

        foreach($this->data['posts'] as $post) {
            $posts = new \wordpress2jekyll\export\post($this, $post);
            if ($posts->prepare()) {
                $posts->save();
            }
        }
    }

    private function purge_posts_directory()
    {
        foreach (new \DirectoryIterator($this->get_path('posts')) as $fileInfo) {
            if(!$fileInfo->isDot()) {
                unlink($fileInfo->getPathname());
            }
        }
    }

    //Taxonomies
    public function add_taxonomy($taxonomy, $terms)
    {
        $this->data['taxonomies'][$taxonomy['id']] = array(
                                                            'id'  => $taxonomy['id'],
                                                            'name'  => $taxonomy['name'],
                                                            'terms' => $terms
        );
    }

    public function write_taxonomies()
    {
        foreach($this->data['taxonomies'] as $taxonomy_id => $taxonomy) {
            $taxonomy = new \wordpress2jekyll\export\taxonomy($this, $taxonomy);
            if ($taxonomy->prepare()) {
                $taxonomy->save();
            }
        }
    }
}

