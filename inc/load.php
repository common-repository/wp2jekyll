<?php
namespace wordpress2jekyll;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

if ( is_admin() )
{
    require_once plugin_dir_path(__W2J_FILE__) . 'inc' . DIRECTORY_SEPARATOR . 'admin.php';
}

require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'hooks.php';

require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'build.php';
require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'formatting.php';

//HTML to Markdown
require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'converters/Markdownify' . DIRECTORY_SEPARATOR . 'Converter.php';
require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'converters/Markdownify' . DIRECTORY_SEPARATOR . 'ConverterExtra.php';
require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'converters/Markdownify' . DIRECTORY_SEPARATOR . 'Parser.php';

require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'cms' . DIRECTORY_SEPARATOR . 'wordpress.php';

require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'jekyll.php';
require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'export.php';
require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'post.php';
require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'authors.php';
require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'export' . DIRECTORY_SEPARATOR . 'taxonomy.php';
require_once plugin_dir_path( __W2J_FILE__ ) . 'inc' . DIRECTORY_SEPARATOR . 'Spyc.php';