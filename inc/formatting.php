<?php
namespace wordpress2jekyll;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

class formatting {
    /*
     * Check to see if the content has markdown tags in it
     */
    public function is_markdown($content)
    {
       return false;
    }

    public function convert_to_markdown($html)
    {

        $converter = new \Markdownify\ConverterExtra;
        $markdown = $converter->parseString($html);
        return $markdown;
    }
}