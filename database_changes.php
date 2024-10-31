<?php

function add_primer_post_count_column(){
  global $wpdb;
  $table_name = $wpdb->prefix . "primer_data";
  $row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $table_name . "' AND column_name = 'primer_posts_count'"  );
  if(empty($row)): // If doesn't exist
    $wpdb->query("ALTER TABLE " . $table_name . " ADD primer_posts_count INT(6) NOT NULL DEFAULT 0");

    // Loop through all existing posts
    $query = array( 'post_type' => 'post', 'post_status' => array('publish'), 'posts_per_page' => 200 );
    $loop = new WP_Query($query);
    $post_count = 0;
    while ( $loop->have_posts() ) : $loop->the_post();
      $gallery_data = get_post_meta( get_the_ID(), 'primer_data', true );
      if(count($gallery_data) > 0 && is_array($gallery_data)) $post_count++;
    endwhile;
    $wpdb->update(
      $table_name, array( 'primer_posts_count' => $post_count, ), array('id'=>"1" )
    );
  endif;
}

function update_primer_post_count_column(){
  global $wpdb;
  $query = array( 'post_type' => 'post', 'post_status' => array('publish'), 'posts_per_page' => 200 );
  $loop = new WP_Query($query);
  $post_count = 0;
  $table_name = $wpdb->prefix . "primer_data";
  while ( $loop->have_posts() ) : $loop->the_post();
    $gallery_data = get_post_meta( get_the_ID(), 'primer_data', true );
    if(count($gallery_data) > 0 && is_array($gallery_data)) $post_count++;
  endwhile;
  $wpdb->update(
    $table_name, array( 'primer_posts_count' => $post_count, ), array('id'=>"1" )
  );
}

// add admin email field to store from global settings (added in version 1.0.18)
function add_email_field_global() {
  global $wpdb;
  $table_name = $wpdb->prefix . "primer_data";
  $row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $table_name . "' AND column_name = 'primer_admin_email'"  );
  if(empty($row)): // If doesn't exist
    $wpdb->query("ALTER TABLE " . $table_name . " ADD primer_admin_email TEXT NOT NULL DEFAULT ''");
  endif;
}

?>