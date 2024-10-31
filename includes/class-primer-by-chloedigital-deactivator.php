<?php

/**
 * Fired during plugin deactivation
 *
 * @link       primerbychloedigital.com
 * @since      1.0.0
 *
 * @package    Primer_By_Chloedigital
 * @subpackage Primer_By_Chloedigital/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Primer_By_Chloedigital
 * @subpackage Primer_By_Chloedigital/includes
 * @author     chloédigital <info@chloedigital.com>
 */
class Primer_By_Chloedigital_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Remove tables on plugin deactivation
		// global $wpdb;
		// $table_name = $wpdb->prefix . 'primer_data';
		// $sql = "DROP TABLE IF EXISTS $table_name";
		// $wpdb->query($sql);
		// delete_option("my_plugin_db_version");
	}

}
