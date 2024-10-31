<?php

/**
* Fired during plugin activation
*
* @link       primerbychloedigital.com
* @since      1.0.0
*
* @package    Primer_By_Chloedigital
* @subpackage Primer_By_Chloedigital/includes
*/

/**
* Fired during plugin activation.
*
* This class defines all code necessary to run during the plugin's activation.
*
* @since      1.0.0
* @package    Primer_By_Chloedigital
* @subpackage Primer_By_Chloedigital/includes
* @author     chloédigital <info@chloedigital.com>
*/
include_once(plugin_dir_path( dirname( __FILE__ ) ) . 'database_changes.php');
class Primer_By_Chloedigital_Activator {

	/**
	* Short Description. (use period)
	*
	* Long Description.
	*
	* @since    1.0.0
	*/
	public static function activate() {
		// create tables in DB
		global $wpdb;
		$table_name = $wpdb->prefix . "primer_data";
		$primer_db_version = '1.0.0';
		$charset_collate = $wpdb->get_charset_collate();

		if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {

			$sql = "CREATE TABLE $table_name (
				ID mediumint(9) NOT NULL AUTO_INCREMENT,
				`primer_enabled` text NOT NULL,
				`primer_blog_name` text NOT NULL,
				`primer_image_id` text,
				`primer_image_url` text,
				`primer_onboarding` text,
				`primer_privacy_accepted` text,
				`primer_agreement_accepted` text,
				PRIMARY KEY  (ID)
			)    $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
			add_option( 'my_db_version', $primer_db_version );
		}

		// Store first entry
		$wpdb->insert( $table_name, array(
				'primer_enabled'    =>  'true',
				'primer_blog_name'  =>  get_bloginfo( 'name' ),
				'primer_image_id'   =>  '',
				'primer_image_url'  =>  '',
				'primer_onboarding'  => "0",
				'primer_privacy_accepted'  => "0",
				'primer_agreement_accepted'  => "0",
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s', '%s')
		);

		add_primer_post_count_column();
		update_primer_post_count_column();
		add_email_field_global();
	}
}