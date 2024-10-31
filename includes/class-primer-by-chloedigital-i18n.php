<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       primerbychloedigital.com
 * @since      1.0.0
 *
 * @package    Primer_By_Chloedigital
 * @subpackage Primer_By_Chloedigital/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Primer_By_Chloedigital
 * @subpackage Primer_By_Chloedigital/includes
 * @author     chloédigital <info@chloedigital.com>
 */
class Primer_By_Chloedigital_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'primer-by-chloedigital',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
