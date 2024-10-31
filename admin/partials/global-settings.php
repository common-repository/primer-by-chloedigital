<?php
    // This will enqueue the Media Uploader script
    wp_enqueue_media();
    // check for rows in table
    global $wpdb;
    $table_name = $wpdb->prefix . 'primer_data';
    $count_query = "select count(*) from $table_name";
    $rows = $wpdb->get_var($count_query);
    // if zero rows insert default data
    if ($rows == 0) {
    	// Store first entry with enabled = false
		$wpdb->insert( $table_name,
	        array(
	        	'ID' => 1,
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
    }
    // get row from table
    $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}primer_data WHERE ID = 1", OBJECT );
    // Show settings page
    include('primer-settings.php');
?>
