<?php
namespace wordpress2jekyll;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ){
    die;
}

class cms {

  private $assets = array();
  private $post_types = array('post');
  private $taxonomies = array();

  public function set_post_types(array $post_types)
  {
	  $this->post_types = $post_types;
  }

  public function set_taxonomies(array $taxonomies)
  {
	$this->taxonomies = $taxonomies;
  }

  public function get_authors() {
	$parameters = array(
	  'orderby' => 'ID',
	  'order'   => 'ASC'
	  //'who'   => 'authors'
	);

	$raw_users = get_users($parameters);

	$authors = array();
	foreach ($raw_users as $user) {
	  $authors[$user->ID] = array(
		'user_id'   => $user->ID,
		'username'  => $user->user_login,
		'name' 		=> $user->display_name,
		'email'     => $user->user_email,
		'website'   => $user->user_url,
		'bio'		=> get_the_author_meta('description', $user->ID)
	  );
	}
	return $authors;
  }

  public function get_posts() {
	global $wpdb;

	$post_types_sql = implode('\', \'', $this->post_types);

	$sql = "SELECT p.*, u.user_login
			FROM $wpdb->posts AS p
			LEFT JOIN  $wpdb->users AS u ON p.post_author = u.ID
			WHERE
			p.post_type IN ('$post_types_sql')";

	$sql .= "AND p.post_status = 'publish'
			AND p.post_date < NOW()
			ORDER BY p.post_date DESC
			";

	$raw_posts = $wpdb->get_results($sql, OBJECT);

	//Reformat the results
	$posts = array();
	foreach ($raw_posts as $post) {

	  $this_post = $this->extract_post($post);

	  $posts[] = $this_post;
	}

	return $posts;
  }

  public function get_post($post_id) {
	global $wpdb;

	  $post_types_sql = implode('\', \'', $this->post_types);

	  $sql = "SELECT p.*, u.user_login
			FROM $wpdb->posts AS p
			LEFT JOIN  $wpdb->users AS u ON p.post_author = u.ID
			WHERE
			p.ID = " . intval($post_id) . "
			AND p.post_type IN ('$post_types_sql')
			";

	$raw_post = $wpdb->get_row($sql, OBJECT);

	$post = $this->extract_post($raw_post);
	return $post;
  }

  private function extract_post($post) {

	if(get_option('jekyll_wordpress_preprocess_content', 1))
	{
	  $post->post_content = apply_filters('the_content', $post->post_content);
	}

	if (formatting::is_markdown($post->post_content)) {
	  $post_content = $post->post_content;
	}
	else
	{
	  $post_content = formatting::convert_to_markdown($post->post_content);
	}

	$permalink = $this->get_post_permalink($post->ID);

	$categories = $this->get_post_categories($post->ID);
	$tags       = $this->get_post_tags($post->ID);

	if($feature_image_data = $this->get_feature_image($post->ID))
	{
	  	$this->add_asset($feature_image_data['image_src'], $feature_image_data['filename']);
		$feature_image = $feature_image_data['filename'];
	}

	$meta = array();
	if(get_option('jekyll_export_post_meta', 1))
	{
	  $meta = $this->get_post_meta($post->ID);
	}

	$this_post = array(
        'type' 			=> $post->post_type,
	    'title'           => $post->post_title,
        'published'       => ($post->post_status == 'publish' ? TRUE : FALSE),
        //'author_id' => intval($post->post_author),
        'author_username' => $post->user_login,
        'allow_comments'  => ($post->comment_status == 'open' ? TRUE : FALSE),
        'slug'            => $post->post_name,
        'permalink'		=> $permalink,
        'creation_time'   => strtotime($post->post_date),
        'update_time'     => strtotime($post->post_modified),
        'content'         => $post_content,
        'feature_image'	=> $feature_image,
        'categories'      => $categories,
        'tags'            => $tags,
        'assets'			=> $this->assets,
	  	'meta'				=> $meta
	);

	return $this_post;
  }

  public function get_post_permalink($post_id)
  {
		$permalink_url = get_permalink($post_id);
		$permalink = str_replace(home_url(), '', $permalink_url);
		$permalink = trim($permalink, '/');
		$permalink = '/' . $permalink;

		return $permalink;
  }

  public function get_post_categories($post_id) {
	$raw_categories = get_the_category($post_id);
	$categories     = array();

	if ($raw_categories) {
	  foreach ($raw_categories as $category) {
		$categories[$category->cat_ID] = $category->category_nicename;
	  }
	}
	return $categories;
  }

  public function get_post_tags($post_id) {
	$raw_tags = wp_get_post_tags($post_id);
	$tags     = array();

	if ($raw_tags) {
	  foreach ($raw_tags as $tag) {
		$tags[$tag->slug] = $tag->name;
	  }
	}
	return $tags;
  }

  public function get_post_meta($post_id) {
	$meta = array();

	//convert traditional post_meta values, hide hidden values
	$post_custom = get_post_custom($post_id);
	foreach ( $post_custom as $key => $value ) {

	  if ( substr( $key, 0, 1 ) == '_' )
		continue;

	  $output[$key] = $value;

	}

	return $meta;
  }

  public function get_feature_image($post_id)
  {

	if(has_post_thumbnail($post_id))
	{
	  $post_featured_image_id = get_post_thumbnail_id($post_id, 'full');

	  $image_src = get_attached_file($post_featured_image_id);

	  $filename = pathinfo($image_src, PATHINFO_BASENAME );

	  return array(
				  'image_src'	=> $image_src,
				  'filename' 		=> $filename
	  );
	}

	return false;
  }

		public function get_taxonomy($taxonomy_id)
		{
				$raw_taxonomy = get_taxonomy($taxonomy_id);

				$taxonomy = array(
					'id'   => $raw_taxonomy->name,
					'name' => $raw_taxonomy->label
				);

				return $taxonomy;
		}

  public function get_taxonomies()
  {
			$taxonomies = array();
			//Doesn't grab anything yet.
			foreach($this->taxonomies as $taxonomy_id) {

				$raw_taxonomy = get_taxonomy($taxonomy_id);//(array('name' => $taxonomy_id), 'objects');
				//$raw_taxonomy = list($raw_taxonomy);

				$taxonomies[$raw_taxonomy->name] = array(
					'id'   => $raw_taxonomy->name,
					'name' => $raw_taxonomy->label
				);

			}

			return $taxonomies;
  }

  public function get_taxonomy_terms($taxonomy_id)
  {
			$args = array(
				'orderby'                => 'name',
				'order'                  => 'ASC',
				'hide_empty'             => false,
				'include'                => array(),
				'exclude'                => array(),
				'exclude_tree'           => array(),
				'number'                 => '',
				'offset'                 => '',
				'fields'                 => 'all',
				'name'                   => '',
				'slug'                   => '',
				'hierarchical'           => true,
				'search'                 => '',
				'name__like'             => '',
				'description__like'      => '',
				'pad_counts'             => false,
				'get'                    => '',
				'child_of'               => 0,
				'parent'                 => '',
				'childless'              => false,
				'cache_domain'           => 'core',
				'update_term_meta_cache' => true,
				'meta_query'             => ''
			);

			$raw_terms = get_terms($taxonomy_id, $args);
			$terms = array();
			foreach($raw_terms as $term)
			{
				$terms[$term->name] = array(
																		'id' => $term->name,
																		'name' => $term->name
				);
			}

			return $terms;
  }

  public function add_asset($source, $destination)
  {
	$this->assets[] = array(
	  'source' => $source,
	  'destination' => $destination
	);
  }
}