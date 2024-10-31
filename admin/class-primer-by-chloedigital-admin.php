<?php

/**
* The admin-specific functionality of the plugin.
*
* @link       primerbychloedigital.com
* @since      1.0.25
*
* @package    Primer_By_Chloedigital
* @subpackage Primer_By_Chloedigital/admin
*/

/**
* The admin-specific functionality of the plugin.
*
* Defines the plugin name, version, and two examples hooks for how to
* enqueue the admin-specific stylesheet and JavaScript.
*
* @package    Primer_By_Chloedigital
* @subpackage Primer_By_Chloedigital/admin
* @author     chloédigital <info@chloedigital.com>
*/

include_once(plugin_dir_path( dirname( __FILE__ ) ) . 'database_changes.php');

class Primer_By_Chloedigital_Admin {

	/**
	* The ID of this plugin.
	*
	* @since    1.0.25
	* @access   private
	* @var      string    $plugin_name    The ID of this plugin.
	*/
	private $plugin_name;

	/**
	* The version of this plugin.
	*
	* @since    1.0.25
	* @access   private
	* @var      string    $version    The current version of this plugin.
	*/
	private $version;

	/**
	* Initialize the class and set its properties.
	*
	* @since    1.0.25
	* @param      string    $plugin_name       The name of this plugin.
	* @param      string    $version    The version of this plugin.
	*/
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	* Register the stylesheets for the admin area.
	*
	* @since    1.0.25
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/primer-by-chloedigital-admin.css', array(), $this->version, 'all' );

	}

	/**
	* Register the JavaScript for the admin area.
	*
	* @since    1.0.25
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/primer-by-chloedigital-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('primer-script', plugin_dir_url( __FILE__ ) . 'js/admin-scripts.js');

		// Validation Scripts for Post Options
		wp_enqueue_script('validation-script', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', array( 'jquery' ), $this->version, false);
	}

	/**
	* Register the administration menu for this plugin into the WordPress Dashboard menu.
	*
	* @since    1.0.25
	*/

	public function add_plugin_admin_menu() {

		/*
		* Add a settings page for this plugin to the Settings menu.
		*
		* NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		*
		*        Administration Menus: http://codex.wordpress.org/Administration_Menus
		*
		*/
		// remove later
		add_menu_page(
			'PRIMER by chloédigital Setup',
			'PRIMER',
			'manage_options',
			$this->plugin_name,
			array($this, 'display_plugin_setup_page'),
			'dashicons-primer-mark-wht', 80
			//'' // remove this line and uncomment previous to add icon to sidebar menu
		);
	}

	/**
	* Add settings action link to the plugins page.
	*
	* @since    1.0.25
	*/

	public function add_action_links( $links ) {
		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		*/
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );

	}

	/**
	* Render the settings page for this plugin.
	*
	* @since    1.0.25
	*/

	public function display_plugin_setup_page() {
		include_once( 'partials/primer-by-chloedigital-admin-display.php' );
	}

	public function add_post_options() {
		include_once('inc/post-options.php');
	}

	public function primer_columns_head($defaults) {
		$defaults['primer_by_chloedigital'] = '<img title="PRIMER" src= "'.plugin_dir_url(__FILE__) .'/images/primer-logo-2-black.svg"  width="18"/>';
		return $defaults ;
	}

	// SHOW THE FEATURED IMAGE
	public function primer_columns_content($column_name, $post_ID) {
	    if ($column_name == 'primer_by_chloedigital') {
			$gallery_data = get_post_meta( $post_ID, 'primer_data', true );
			if ( is_array($gallery_data) ) {
				if(count($gallery_data) > 0){
					echo '<img src= "'.plugin_dir_url(__FILE__) .'/images/check.svg" width="15" style="margin-top: 12px"/>';
				}else{
					echo '';
				}
			}
	    }
	}

	public function create_primer_post_count_column($upgrader_object, $options){
		// Create new column in primer_data table if it doesn't exist
		$primer_plugin = 'shoppable-images/primer-by-chloedigital.php';
		$primer_plugin_2 = 'primer-by-chloedigital/primer-by-chloedigital.php';
		if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
			foreach( $options['plugins'] as $plugin ) {
				if( $plugin == $primer_plugin || $plugin == $primer_plugin_2 ) {
					add_primer_post_count_column();
					update_primer_post_count_column();
				}
			}
		}
	}

	// add admin email field to store from global settings (added in version 1.0.25)
	public function add_email_field_global() {
		$primer_plugin = 'shoppable-images/primer-by-chloedigital.php';
		$primer_plugin_2 = 'primer-by-chloedigital/primer-by-chloedigital.php';
		if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
			foreach( $options['plugins'] as $plugin ) {
				// if( $plugin == $primer_plugin || $plugin == $primer_plugin_2 ) {
					global $wpdb;
					$table_name = $wpdb->prefix . "primer_data";
					$row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $table_name . "' AND column_name = 'primer_admin_email'"  );
					if(empty($row)): // If doesn't exist
						$wpdb->query("ALTER TABLE " . $table_name . " ADD primer_admin_email TEXT NOT NULL DEFAULT ''");
					endif;
				// }
			}
		}
	}

}
