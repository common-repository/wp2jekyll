<?php
namespace wordpress2jekyll\export;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

class authors extends export {
    private $content = '';
    private $filename = 'authors.yml';

    public function prepare()
    {

        $this->content = '# Authors generated ' . date('r') . PHP_EOL;
        $this->content .= $this->format_authors();

        return true;
    }

    private function format_authors()
    {
        $authors = array();
        foreach($this->data as $author_id => $author)
        {
            $authors[$author['username']] = array(
                                        //'user_id' => $author['user_id'],
                                        //'username' => $author['username'],
                                        'name' => $author['name'],
                                        'email' => $author['email'],
                                        'bio' =>    $author['bio'],
                                        'avatar' => null,
                                        'social' => array(
                                            'twitter' => null,
                                            'google' => null,

                                        )
            );

        }

        $yaml = \Spyc::YAMLDump($authors, 4, 60, true);

        return $yaml;
    }

    public function save()
    {
        $file_path = $this->jekyll->get_path('data') . DIRECTORY_SEPARATOR . $this->filename;

        file_put_contents($file_path, $this->content);
    }
}