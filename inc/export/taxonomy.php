<?php
namespace wordpress2jekyll\export;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

class taxonomy extends export {
    private $content = '';
    private $filename = 'authors.yml';

    public function __construct(\jekyll $jekyll, $data = null)
    {
        parent::__construct($jekyll, $data);

        $this->filename = $this->data['id'] . '.yml';

    }

    public function prepare()
    {

        $this->content = '# ' . $this->data['name'] . ' Taxonomy generated ' . date('r') . PHP_EOL;
        $this->content .= $this->format_taxonomy();

        return true;
    }

    private function format_taxonomy()
    {
        $taxonomy = array();
        foreach($this->data['terms'] as $term_id => $term)
        {
            $taxonomy[$term_id] = array(
                                        'name' => $term['name'],
            );

        }

        $yaml = \Spyc::YAMLDump($taxonomy, 4, 60, true);

        return $yaml;
    }

    public function save()
    {
        $file_path = $this->jekyll->get_path('data') . DIRECTORY_SEPARATOR . $this->filename;

        file_put_contents($file_path, $this->content);
    }
}