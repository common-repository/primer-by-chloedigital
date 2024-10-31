(function( $ ) {
  'use strict';

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

    $(function() {

      $('input#primer_media_manager').click(function(e) {

        e.preventDefault();
           var image_frame;
           if(image_frame){
               image_frame.open();
           }
           // Define image_frame as wp.media object
           image_frame = wp.media({
              title: 'Select Media',
              multiple : false,
              library : {
                  type : 'image',
              }
           });

          image_frame.on('open',function() {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manager
            var selection =  image_frame.state().get('selection');
            var ids = jQuery('input#primer_image_id').val().split(',');
            ids.forEach(function(id) {
              var attachment = wp.media.attachment(id);
              attachment.fetch();
              selection.add( attachment ? [ attachment ] : [] );
            });
          });

          image_frame.on('close',function() {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection =  image_frame.state().get('selection');
            var gallery_ids = new Array();
            var gallery_urls = new Array();
            var my_index = 0;
            selection.each(function(attachment) {
              // check iamge widht and height are above 80px
              var image_width = attachment['attributes']['width'];
              var image_height = attachment['attributes']['height'];
              if ( image_width > 79 && image_height > 79 ){
                gallery_ids[my_index] = attachment['id'];
                gallery_urls[my_index] = attachment['attributes']['url'];
                my_index++;
              }
            });
            if ( gallery_ids.length > 0 ) {
              jQuery('#primer_image_error_message').hide();
              var ids = gallery_ids.join(",");
              var urls = gallery_urls.join(",");
              jQuery('input#primer_image_id').val(ids);
              jQuery('input#primer_image_url').val(urls);
              Refresh_Image(ids);
            }else{
              // Image is smaller than 80px
              jQuery('#primer_image_error_message').show();
            }
          });
          image_frame.open();
      });
    });

    // Ajax request to refresh the image preview
    function Refresh_Image(the_id){
      var data = {
          action: 'primer_get_image',
          id: the_id
      };
      jQuery.get(ajaxurl, data, function(response) {
          if(response.success === true) {
            jQuery('#primer-preview-image').replaceWith( response.data.image );
          }
      });
    }
})( jQuery );