<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              primerbychloedigital.com
 * @since             1.0.25
 * @package           Primer_By_Chloedigital
 *
 * @wordpress-plugin
 * Plugin Name:       PRIMER by chloédigital
 * Plugin URI:        primerbychloedigital.com
 * Description:       The best plugin to help grow your organic traffic via product-based images. Start making your images discoverable through product searches today.
 * Version:           1.0.25
 * Author:            chloédigital
 * Author URI:        chloedigital.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       primer-by-chloedigital
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.25 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PBCD_PLUGIN_VERSION', '1.0.25' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-primer-by-chloedigital-activator.php
 */
function activate_primer_by_chloedigital() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-primer-by-chloedigital-activator.php';
	Primer_By_Chloedigital_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-primer-by-chloedigital-deactivator.php
 */
function deactivate_primer_by_chloedigital() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-primer-by-chloedigital-deactivator.php';
	Primer_By_Chloedigital_Deactivator::deactivate();
}

function general_admin_notice(){
	global $wpdb;
    global $pagenow;
    if ( $pagenow != 'admin.php' ) { // show in all screens except plugin global settings until set up
    	$onboarding = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}primer_data WHERE ID = 1", OBJECT );
    	if ($onboarding[0]->primer_onboarding != '1') {
    		echo '<div class="primer-settings-message notice notice-info is-dismissible">
					<p>PRIMER by chloédigital plugin <span>is active.</span></p>
					<div class="primer_check_settings_message"><a href="'. admin_url('admin.php?page=primer-by-chloedigital').'">Please check your Settings</a></div>
					<div class="primer_message_submark_container"><img  class="primer_message_submark" src="'.plugin_dir_url( __FILE__ ).'admin/images/primer-submark.png"/></div>
	         	  </div>';
    	}
    }
}
add_action('admin_notices', 'general_admin_notice');

register_activation_hook( __FILE__, 'activate_primer_by_chloedigital' );
register_deactivation_hook( __FILE__, 'deactivate_primer_by_chloedigital' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-primer-by-chloedigital.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.25
 */
function run_primer_by_chloedigital() {

	$plugin = new Primer_By_Chloedigital();
	$plugin->run();

}
run_primer_by_chloedigital();

include('admin/inc/post-options.php');
include('admin/inc/save-global-data.php');
include('admin/inc/notifications.php');
