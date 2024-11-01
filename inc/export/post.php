<?php
namespace wordpress2jekyll\export;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

class post extends export {
    private $content = '';
    private $filename = '';
    private $essential_fields = array('slug', 'title', 'published', 'author_username', 'creation_time');

    //Check a few basics to make sure it has the correct data structure
    public function is_post()
    {
        foreach($this->essential_fields as $field)
        {
            if(!isset($this->data[$field])) return false;
        }

        return true;
    }

    public function prepare()
    {
        if(!$this->is_post()) return false;

        $this->filename = date('Y-m-d') . '-' . $this->data['slug'] . '.md'; //Markdown

        $this->add_assets($this->data['assets']);

        $this->content = '---' . PHP_EOL;
        $this->content .= $this->get_header();
        $this->content .= '---' . PHP_EOL;
        $this->content .= $this->get_content();    //Will need to sanitise this

        return true;
    }

    private function get_header()
    {
        $this_post_data = array(
                                'layout' => 'post',
                                'title' =>  $this->data['title'],
                                'published' => $this->data['published'],
                                'author' => $this->data['author_username'],
                                'comments' => $this->data['allow_comments'],
                                'date' => date('Y-m-d h:m:s', $this->data['creation_time']),
                                'tags' => (isset($this->data['tags']) && is_array($this->data['tags']) ? array_values($this->data['tags']) : null),
                                'categories' => (isset($this->data['categories']) && is_array($this->data['categories']) ? array_values($this->data['categories']) : null)

        );

        if(get_option('jekyll_use_wordpress_permalinks') && !empty($this->data['permalink']))
        {
            $this_post_data['permalink'] = $this->data['permalink'];
        }

        if($this->data['meta'])
        {
            $this_post_data['meta'] = $this->data['meta'];
        }

        if($this->data['feature_image'])
        {
            $this_post_data['image']['feature'] = $this->data['feature_image'];
        }

        $yaml = \Spyc::YAMLDump($this_post_data, 4, 60, true);

        return $yaml;
    }

    private function get_content()
    {
        $content = strip_tags($this->data['content']);

        if(!\wordpress2jekyll\formatting::is_markdown($content))
        {
            \wordpress2jekyll\formatting::convert_to_markdown($content);
        }

        return $content;
    }

    public function save()
    {
        $file_path = $this->jekyll->get_path('posts') . DIRECTORY_SEPARATOR . $this->filename;

        if(file_put_contents($file_path, $this->content))
        {
            $this->save_assets();
        }

    }
}