<?php

add_action( 'wp_ajax_primer_dismiss_notification_handler', 'primer_dismiss_post_count_notification' );
function primer_dismiss_post_count_notification() {
  update_option( 'primer_notification_posts_count_dismissed', TRUE );
}

add_action( 'wp_ajax_primer_show_later_notification_handler', 'primer_show_later_post_count_notification' );
function primer_show_later_post_count_notification() {
  $expire = date("Y-m-d H:i:s", strtotime('+7 days'));
  update_option( 'primer_notification_posts_count_hide_until', $expire);
}

function general_admin_notice_primer_post_count(){
	global $wpdb;
	global $pagenow;

  $today = date("Y-m-d H:i:s");
  $date_show_again = get_option( 'primer_notification_posts_count_hide_until');

	if ( $pagenow != 'admin.php' && get_option('primer_notification_posts_count_dismissed') != TRUE && $today > $date_show_again	 ) {
		$settings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}primer_data WHERE ID = 1", OBJECT );
    if ( $settings[0] ) {
      if(property_exists($settings[0], 'primer_posts_count')){ // Check if primer_post_count exists
        $primer_posts_count = $settings[0]->primer_posts_count;
        if($primer_posts_count >= 10):
          echo '<div id="primer-post-count-notification" class="primer-settings-message notice notice-info is-dismissible">';
          echo '<div class="primer-notification-left">';
          echo "You recently hit {$primer_posts_count} number of PRIMED posts, that’s amazing! Celebrate this milestone by rating PRIMER by chloédigital 5-stars to help spread the word!";
          echo '</div>';
          echo '<span class="primer-notification-container-buttons">';
          echo '<a href="https://wordpress.org/support/plugin/primer-by-chloedigital/reviews/#new-post" target="_blank" class="primer-notification-btn primer-rate">Rate PRIMER</a>';
          echo '<a class="primer-notification-btn primer-show-later-post-count-notification">Maybe later</a>';
          echo '<a class="primer-notification-btn primer-dismiss-post-count-notification">I already did this</a>';
          echo '</span>';
          echo '<div class="primer_message_submark_container"><img  class="primer_message_submark" src="'.plugin_dir_url( __FILE__ ).'../images/primer-submark.png"/></div>';
          echo '</div>';
        endif;
      }
    }
	}
}
add_action('admin_notices', 'general_admin_notice_primer_post_count');

?>
