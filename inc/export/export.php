<?php
namespace wordpress2jekyll\export;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

class export {
    protected $data = array();
    protected $assets = array();
    protected $jekyll = null;

    public function __construct(\jekyll $jekyll, $data = null)
    {
        $this->jekyll = $jekyll;
        $this->data = (!is_null($data)) ? $data : array();
    }

    public function prepare()
    {

    }

    public function save()
    {

    }

    public function add_assets($assets)//$source, $destination
    {
        if(!empty($assets)) {
            foreach ($assets as $asset) {
                $this->assets[] = array(
                  'source' => $asset['source'],
                  'destination' => $asset['destination']
                );
            }
        }
    }

    public function save_assets()
    {
        if(!empty($this->assets))
        {
            foreach($this->assets as $asset)
            {
                $filename = basename($asset['source']);
                $destination = $this->jekyll->get_path('assets') . DIRECTORY_SEPARATOR . $filename;
                copy($asset['source'], $destination);
            }
        }
    }
}