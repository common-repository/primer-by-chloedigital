<?php

  // save global data
  function save_primer_data() {

    global $wpdb;
    // $primer_enabled = (isset($_POST['primer_enabled']) && !empty($_POST['primer_enabled'])) ? 'true' : 'false';
    $primer_enabled = 'true'; // plugin always enabled

    // Sanitize data

    if ( isset( $_POST[ 'primer_blog_name' ] ) ):
      $primer_blog_name = strip_tags( trim($_POST['primer_blog_name'] ) );
    else:
      return;
    endif;

    // if ( isset( $_POST[ 'primer_admin_email' ] ) ):
    //   $primer_admin_email = strip_tags( trim($_POST['primer_admin_email'] ) );
    // else:
    //   return;
    // endif;

    if ( isset( $_POST[ 'primer_image_id' ] ) ):
      if ( is_int( $_POST['primer_image_id'] ) ):
        $primer_image_id = (int) $_POST['primer_image_id'];
      else:
        $primer_image_id = (int) $_POST['primer_image_id'];
      endif;
    else:
      return;
    endif;

    if ( isset( $_POST[ 'primer_image_url' ] ) ):
      $primer_image_url = filter_var($_POST[ 'primer_image_url' ], FILTER_SANITIZE_URL);
      // if (!filter_var($primer_image_url, FILTER_VALIDATE_URL)):
      //   return;
      // endif;
    else:
      return;
    endif;

    if (isset($_POST['primer_agreement_accepted'])){
      $primer_agreement_accepted = (int)$_POST['primer_agreement_accepted'];
      $primer_agreement_accepted = filter_var($primer_agreement_accepted, FILTER_SANITIZE_NUMBER_INT);
    }else{
      $primer_agreement_accepted = "0";
    }
    if (isset($_POST['primer_privacy_accepted'])){
      $primer_privacy_accepted = (int)$_POST['primer_privacy_accepted'];
      $primer_agreement_accepted = filter_var($primer_agreement_accepted, FILTER_SANITIZE_NUMBER_INT);
    }else{
      $primer_privacy_accepted = "0";
    }

    // define table name
    $table_name = $wpdb->prefix . 'primer_data';

    // check if table is empty
    $count_query = "select count(*) from $table_name";
    $rows = $wpdb->get_var($count_query);

    if ($rows > 0) {
      // update settings
      $wpdb->update(
        $table_name,
        array(
          'primer_enabled'    =>  'true',
          'primer_blog_name'  =>  $primer_blog_name,
          // 'primer_admin_email'  =>  $primer_admin_email,
          'primer_image_id'   =>  $primer_image_id,
          'primer_image_url'  =>  $primer_image_url,
          'primer_onboarding' =>  "1",
          'primer_agreement_accepted' => $primer_agreement_accepted,
          'primer_privacy_accepted' => $primer_privacy_accepted,
        ),
        array('id'=>"1")
      );
    }else{
    // add as first entru with ID = 1
    $wpdb->insert( $table_name,
          array(
            'ID'    =>  1,
            'primer_enabled'    =>  'true',
            'primer_blog_name'  =>  $primer_blog_name,
            // 'primer_admin_email'  =>  $primer_admin_email,
            'primer_image_id'   =>  $primer_image_id,
            'primer_image_url'  =>  $primer_image_url,
            'primer_onboarding' =>  "1",
            'primer_agreement_accepted' => $primer_agreement_accepted,
            'primer_privacy_accepted' => $primer_privacy_accepted,
          ),
          array( '%s', '%s', '%s', '%s', '%s', '%s', '%s')
      );
    }
    
    

    // redirect with message
    if ($primer_enabled == 'true') {
      $redirect = add_query_arg( 'msg', 'success', $_SERVER['HTTP_REFERER'] );
    }else{
      $redirect = add_query_arg( 'msg', 'disabled', $_SERVER['HTTP_REFERER'] );
    }

    // save usage data
    $response = wp_remote_post( 'http://analytics.chloedigital.com/primer/global', array(
        'method' => 'POST',
        'timeout' => 15,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array('x-application-secret' => 'b7f53urnGGL03SC0f62d2774ef14kFto81DCr5ae109758382ce049urtnXXq0eaaaefba061048thnd32Kc41c17d3404'),
        'body' => array( 'blog_name' => $primer_blog_name, 'blog_url' => get_site_url(), 'main_image_url' => $primer_image_url, 'admin_email_address' => get_option('admin_email') ),
        // 'body' => array( 'blog_name' => $primer_blog_name, 'blog_url' => get_site_url(), 'main_image_url' => $primer_image_url, 'admin_email_address' => $primer_admin_email ),
        'cookies' => array()
        )
    );

    // redirect
    wp_redirect( $redirect );
    exit;
  }

  add_action( 'admin_post_nopriv_save_primer_data', 'save_primer_data' );
  add_action( 'admin_post_save_primer_data', 'save_primer_data' );

  // Ajax action to refresh the user image in global settings
  add_action( 'wp_ajax_primer_get_image', 'primer_get_image'   );
  function primer_get_image() {
      if(isset($_GET['id']) ){
        // get image HTML representation
          $image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'medium', false, array( 'id' => 'primer-preview-image' ) );
          // get image attributes
          $img_atts = wp_get_attachment_image_src( $_GET['id'], 'full' );
          $data = array(
              'image'   =>  $image,
              'image_atts'    => $img_atts,
          );
          wp_send_json_success( $data );
      } else {
          wp_send_json_error();
      }
  }
?>
