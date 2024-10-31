<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       primerbychloedigital.com
 * @since      1.0.0
 *
 * @package    Primer_By_Chloedigital
 * @subpackage Primer_By_Chloedigital/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Primer_By_Chloedigital
 * @subpackage Primer_By_Chloedigital/public
 * @author     chloédigital <info@chloedigital.com>
 */
class Primer_By_Chloedigital_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Primer_By_Chloedigital_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Primer_By_Chloedigital_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/primer-by-chloedigital-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Primer_By_Chloedigital_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Primer_By_Chloedigital_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/primer-by-chloedigital-public.js', array( 'jquery' ), $this->version, false );

	}

	public function load_primer_script() {
		global $wpdb;
  		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}primer_data WHERE ID = 1", OBJECT );

  	// if ($results[0]->primer_enabled == "true"){
  		echo '<script type="application/ld+json">';
    	echo '{';
    	echo '"@context": "http://schema.org",';
    	echo '"@type": "Blog",';
    	echo '"name": "'.$results[0]->primer_blog_name.'",';
    	echo '"image": "'.$results[0]->primer_image_url.'"';
    	echo '}';
    	echo '</script>';
  	// }
    	$code = "";
		if (is_singular("post")):

			$post_id = get_the_ID();
			$gallery_data = get_post_meta( get_the_ID(), 'primer_data', true );
			$author_id = get_post_field ('post_author', $post_id);
			$author = get_the_author_meta('display_name', $author_id);
			
			if ( isset($gallery_data) && is_array($gallery_data) ):
			  foreach ($gallery_data as $key => $data):

			    // Best View image (required) with products
			    $code .=  '<script type="application/ld+json">';
			    $code .=  '{';
			    $code .=  '"@context": "http://schema.org",';
			    $code .=  '"@type": "ImageObject",';
			    $code .=  '"author": "'. $author .'",';
			    $code .=  '"mainEntityOfPage": {';
			    $code .=  '"@type": "WebPage",';
			    $code .=  '"@id": "'. get_the_permalink() . '"';
			    $code .=  '}, ';

			    if(isset($data['location'])):
			      $code .=  '"contentLocation": "'. $data['location'] .'",';
			    endif;

					$urllocal = explode(site_url(), wp_get_attachment_image_url( $data['image_id'], 'full'))[1]; //output local path

					if($urllocal == NULL){
						$code .=  '"contentUrl": "'. wp_get_attachment_image_url( $data['image_id'], 'full') .'",';
					}else{
						$code .=  '"contentUrl": "'. site_url() . $urllocal .'",';
					}

			    $code .=  '"datePublished": "'.get_the_date('Y-m-d').'",';
			    $code .=  '"offers":[';
			    if ( isset($data['products']) && is_array($data['products']) ):
			      $index = 0;
			      foreach ($data['products'] as $p_key => $product_data):
			        $code .=  '{';
			        $code .=  '"@type": "Offer",';
			        $code .=  '"name": "' . $product_data["product_name"] . '",';
			        $code .=  '"url": "' . $product_data["url"] . '"';
			        $code .=  ($index == count($data['products']) - 1) ? '}' : '},';
			        $index++;
			      endforeach;
			    endif;
			    $code .=  ']';
			    $code .=  '}';
			    $code .=  '</script>';

			    if ( isset($data['alt_image_ids']) && is_array($data['alt_image_ids']) ):
			      foreach ($data['alt_image_ids'] as $alt_key => $alt_data):
			        // Alt images with same products as Best View image (required)
			        $code .=  '<script type="application/ld+json">';
			        $code .=  '{';
			        $code .=  '"@context": "http://schema.org",';
			        $code .=  '"@type": "ImageObject",';
			        $code .=  '"author": "'. $author .'",';

			        if(isset($data['location'])):
			          $code .=  '"contentLocation": "'. $data['location'] .'",';
			        endif;

							$urllocal = explode(site_url(), wp_get_attachment_image_url( $alt_data, 'full'))[1]; //output local path
							if($urllocal == NULL){
								$code .=  '"contentUrl": "'. wp_get_attachment_image_url( $alt_data, 'full') .'",';
							}else{
								$code .=  '"contentUrl": "'. site_url() . $urllocal .'",';
							}
			        $code .=  '"datePublished": "'.get_the_date('Y-m-d').'",';
			        $code .=  '"offers":[';
			        if ( isset($data['products']) && is_array($data['products']) ):
			          $index = 0;
			          foreach ($data['products'] as $p_key => $product_data):
			            $code .=  '{';
			            $code .=  '"@type": "Offer",';
			            $code .=  '"name": "' . $product_data["product_name"] . '",';
			            $code .=  '"url": "' . $product_data["url"] . '"';
			            $code .=  ($index == count($data['products']) - 1) ? '}' : '},';
			            $index++;
			          endforeach;
			        endif;
			        $code .=  ']';
			        $code .=  '}';
			        $code .=  '</script>';

			      endforeach;
			    endif;

			  endforeach;
			endif;
		endif;

		echo $code;
	}
}
