<?php

add_action( 'admin_head-post.php', 'print_scripts_primer_by_chloedigital' );
add_action( 'admin_head-post-new.php', 'print_scripts_primer_by_chloedigital' );
add_action( 'save_post', 'primer_update_post_gallery', 10, 2 );
add_action( 'add_meta_boxes', 'primer_by_chloedigital_options' );
add_action( 'save_post_location', 'primer_save_location' );

function primer_by_chloedigital_options() {
		add_meta_box(
				'primer-box',
				'PRIMER by chloédigital',
				'shoppable_custom_fields',
				'post',
				'normal',
				'default'
		);
}

function shoppable_custom_fields(){
    global $post;
    $gallery_data = get_post_meta( $post->ID, 'primer_data', true );
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'primer_by_chloedigital_nonce' );
		?>

		<?php
		global $wpdb;
    $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}primer_data WHERE ID = 1", OBJECT );
		if ($results[0]->primer_privacy_accepted == "1") :

		?>

		<div id="primer-dynamic-form">
	    <div id="field_wrap">

				<div class="empty-set" <?php if(is_array($gallery_data)){ echo 'style="display:none"'; }else{ echo ''; } ?>>Create your first product set</div>

				<?php
		    if ( isset($gallery_data) && is_array($gallery_data) ):
					foreach ($gallery_data as $key => $data):
		    		?>
						<div class="field_row postbox closed" data-id="<?php echo $key ?>">
							<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Shoppable Image</span><span class="toggle-indicator" aria-hidden="true"></span></button>
							<h3>Product Set</h3>
							<div class="inside">
							<!-- Remove Row -->
								<div class="primer-description set-description">Add a set of products that appears in one or more images.</div>
								<div class="form_field">
									<a onclick="remove_field(this)" class="remove_row"><span class="dashicons dashicons-no"></span></a>
								</div>

								<div class="primer-images-container">
									<!-- Main Image -->
									<div class="field_group">
										<div class="form_field">
											<h4>Main Image*
												<div class="primer-tooltip">
													<span class="dashicons dashicons-editor-help"></span>
													<span class="tooltiptext">Ideal ratio is 2:3</span>
												</div>
											</h4>
											<input type="hidden" class="primer_image required" name="gallery[<?php echo $key ?>][image_id]" value="<?php esc_html_e( $data['image_id'] ); ?>"/>
											<div class="primer-image-input-container">
												<div class="primer-image-input" onclick="add_image(this, false)">
													<div class="primer-image-input-preview" style="background-image: url('<?php echo wp_get_attachment_image_url( $data['image_id'], 'thumbnail' ); ?>')"></div>
												</div>
											</div>
											<div class="primer_image_error_message"></div>
										</div>
									</div>

									<!-- Additional Images (optional) -->
									<div class="field_group">
										<h4>Additional Images
											<div class="primer-tooltip">
												<span class="dashicons dashicons-editor-help"></span>
												<span class="tooltiptext">Ideal ratio is 2:3</span>
											</div>
										</h4>
										<div class="primer-description">Select all other images from the post that feature all products from this set.</div>
										<div class="alt-image-row-wrap">
											<?php if ( isset($data['alt_image_ids']) && is_array($data['alt_image_ids']) ): ?>
												<?php foreach ($data['alt_image_ids'] as $alt_key => $alt_data): ?>
													<div class="form_field">
														<input class="primer_image" value="<?php esc_html_e( $alt_data ); ?>" type="hidden" name="gallery[<?php echo $key ?>][alt_image_ids][<?php echo $alt_key ?>]" />
														<div class="primer-image-input-container">
															<div class="primer-image-input" onclick="add_image(this, true)">
																<div class="primer-image-input-preview" style="background-image: url('<?php echo wp_get_attachment_image_url( $alt_data, 'thumbnail' ); ?>')"></div>
															</div>
															<span class="button remove-image" onclick="remove_alt_image(this)"><span class="dashicons dashicons-trash"></span></span>
														</div>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>

											<!-- Add alt image -->
											<div class="form_field">
												<input class="primer_image" value="" type="hidden" name="gallery[<?php echo $key ?>][alt_image_ids][]" />
												<div class="primer-image-input-container">
													<div class="primer-image-input" onclick="add_image(this, true)">
														<div class="primer-image-input-preview"></div>
													</div>
													<span class="button remove-image" onclick="remove_alt_image(this)" style="display:none"><span class="dashicons dashicons-trash"></span></span>
												</div>
											</div>
										</div>
										<div class="primer_image_error_message"></div>
										<!-- <input class="button" type="button" value="+ Add Alt Image" onclick="add_alt_image_row(this);" /> -->
									</div>

								</div>

								<!-- Location -->
								<div class="field_group">
									<table class="form-table">
										<tbody>
											<tr>
												<th>
													<label>Location</label>
												</th>
												<td>
													<input class="" value="<?php esc_html_e( $data['location'] ); ?>" type="text" name="gallery[<?php echo $key ?>][location]" />
													<div class="primer-tooltip">
														<span class="dashicons dashicons-editor-help"></span>
														<span class="tooltiptext">Type the location the image was shot in.</span>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>

								<!-- Add products -->
								<div class="field_group products_group postbox">
									<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Shoppable Image</span><span class="toggle-indicator" aria-hidden="true"></span></button>
									<h3>Product Information</h3>
									<div class="inside">
										<div class="products-row-wrap">
											<?php if ( isset($data['products']) && is_array($data['products']) ): ?>
												<?php foreach ($data['products'] as $pkey=>$product): ?>
													<div class="products-row">
														<div class="form_field">
															<table class="form-table">
																<tbody>
																	<tr>
																		<th>
																			<label>Product URL*</label>
																		</th>
																		<td>
																			<input class="regular-text url" value="<?php esc_html_e( $product['url'] ); ?>" type="text" name="gallery[<?php echo $key ?>][products][<?php echo $pkey ?>][url]" />
																			<div class="primer-tooltip">
																				<span class="dashicons dashicons-editor-help"></span>
																				<span class="tooltiptext">Paste the link to the exact product page here. Use naked URL’s only, links must not be affiliate shortened. Altered URLs or URLs to product categories will not generate an error, but will not be indexed by engines.</span>
																			</div>
																			<span class="primer-product-description">Links must not be affiliate shortened</span>
																		</td>
																	</tr>
																	<tr>
																		<th>
																			<label>Product Name & Brand</label>
																		</th>
																		<td>
																			<input class="" value="<?php esc_html_e( $product['product_name'] ); ?>" type="text" name="gallery[<?php echo $key ?>][products][<?php echo $pkey ?>][product_name]" />
																			<div class="primer-tooltip">
																				<span class="dashicons dashicons-editor-help"></span>
																				<span class="tooltiptext">Type the name and brand of the product here.</span>
																			</div>
																		</td>
																	</tr>
																</tbody>
															</table>
															<a onclick="remove_product(this)" class="remove_row"><span class="dashicons dashicons-no"></span></a>
															<div style="clear: both"></div>
														</div>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<input class="button add_product" type="button" value="Add Product" onclick="add_product_row(this);" />
								<div style="clear: both"></div>

							</div>
						</div>
    			<?php
					endforeach;
	    	endif;
	    	?>
			</div>

			<div class="primer-description set-description primer-required">*required</div>
			<div id="add_field_row">
				<input class="button" type="button" value="Add Set" onclick="add_field_row();" />
			</div>

		</div>
  <?php
	else: // Terms and Privacy were't accepted
		?>
		<div id="primer-privacy-not-accepted">
			Please confirm your <a href="<?php echo admin_url('admin.php?page=primer-by-chloedigital') ?>">settings</a> in order to use <strong>PRIMER</strong>
		</div>
		<?php
	endif;
}

function print_scripts_primer_by_chloedigital(){
    // Check for correct post_type
    global $post;
    if( 'post' != $post->post_type )
        return;
    ?>
    <script type="text/javascript">

			jQuery().ready(function() {
				jQuery("#post").validate({
					ignore: ':not(#primer-dynamic-form *)',
					onkeyup: false,
    			onfocusout: false,
					errorPlacement: function(error, element) {
						// Show error at top of row and scroll to it
						element.parents('.field_row').prepend( error )
						jQuery([document.documentElement, document.body]).animate({
				        scrollTop: jQuery(error).offset().top
				    }, 1000);
					}
				});

				// jQuery(".url").rules("add", {
				//   required:true,
				// 	onkeyup: false,
   	// 			onclick: false
				// });
			});

			var selected_images = []
			function add_image(obj, multiple) {
				var parent = jQuery(obj).parent().parents('div.form_field');
				var inputField = jQuery(parent).find("input.primer_image");
				file_frame = wp.media({
					title: 'Choose Images',
					library: { type: 'image' },
					multiple: multiple ? 'add' : false
				});

				file_frame.on( 'select', function() {
					var state = file_frame.state();
					var selection = state.get('selection');
					if ( ! selection ) return;

					var error_for_multiple = 0;
					selection.each(function(attachment) {
						if(multiple){
							// Alt images (Allow multiple selection)
							if(attachment.attributes.width < 750){
								error_for_multiple++;
								jQuery(parent).parents('.field_group').find(".primer_image_error_message").html(`Images must be at least 750px wide.`)
							}else{
								var clonedGroup = parent.clone()
								// Show "Delete" button on image
								clonedGroup.find('.button.remove-image').css('display', 'block')
								jQuery(clonedGroup).find(".primer-image-input-preview").css('background-image', 'url('+attachment.attributes.url+')')
								var cloned_inputField = jQuery(clonedGroup).find("input.primer_image");
								cloned_inputField.val(attachment.attributes.id);
								clonedGroup.insertBefore(parent);
								if(error_for_multiple == 0){
									jQuery(parent).parents('.field_group').find(".primer_image_error_message").html(``)
								}
							}
						}else{
							// Main Image
							if(attachment.attributes.width < 750){
								jQuery(parent).find(".primer_image_error_message").html("Image is too small, must be at least 750px wide.")
							}else{
								jQuery(parent).find(".primer_image_error_message").html("")
								inputField.val(attachment.attributes.id);
								jQuery(parent).find(".primer-image-input-preview").css('background-image', 'url('+attachment.attributes.url+')')
							}
						}
					});

				});

				file_frame.open();
				return false;
			}

			function remove_field(obj) {

				var parent=jQuery(obj).parent().parent().parent();
				parent.remove();

				// Check if there are sets, and show again "Empty" message
				if(jQuery("#primer-dynamic-form").find(".field_row").length < 1){
					jQuery("#primer-dynamic-form .empty-set").css('display', 'block');
				}
			}

			function add_field_row() {
				var row = jQuery('#master-row').html();

				// Remove empty message
				jQuery("#primer-dynamic-form .empty-set").css('display', 'none');

				var row_key = new Date().getTime();
				var product_row_key = row_key + 1;
				var html = `
								<div class="field_row postbox" data-id=` + row_key + `>

								<button type="button" onclick="toggle(this)" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Shoppable Image</span><span class="toggle-indicator" aria-hidden="true"></span></button>
								<h3>Product Set</h3>
								<div class="inside">

										<div class="primer-description set-description">Add a set of products that appears in one or more images.</div>

										<!-- Remove Row -->
										<div class="form_field">
											<a onclick="remove_field(this)" class="remove_row"><span class="dashicons dashicons-no"></span></a>
										</div>

										<div class="primer-images-container">
											<!-- Main Image -->
											<div class="field_group">
												<h4>Main Image*
												<div class="primer-tooltip">
													<span class="dashicons dashicons-editor-help"></span>
													<span class="tooltiptext">Ideal ratio is 2:3</span>
												</div>
												</h4>
												<div class="primer-description">Select the image from the post that most clearly displays all products from this set.</div>
												<div class="form_field">
													<input class="primer_image required" value="" type="hidden" name="gallery[` + row_key + `][image_id]" />
													<div class="primer-image-input-container">
														<div class="primer-image-input" onclick="add_image(this, false)">
															<div class="primer-image-input-preview"></div>
														</div>
													</div>
													<div class="primer_image_error_message"></div>
												</div>
											</div>

											<!-- Additional Images -->
											<div class="field_group">
												<h4>Additional Images
												<div class="primer-tooltip">
													<span class="dashicons dashicons-editor-help"></span>
													<span class="tooltiptext">Ideal ratio is 2:3</span>
												</div>
												</h4>
												<div class="primer-description">Select all other images from the post that feature all products from this set.</div>
												<div class="alt-image-row-wrap">
													<div class="form_field">
														<input class="primer_image" value="" type="hidden" name="gallery[`+ row_key +`][alt_image_ids][]" />
														<div class="primer-image-input-container">
															<div class="primer-image-input" onclick="add_image(this, true)">
																<div class="primer-image-input-preview"></div>
															</div>
															<span class="button remove-image" onclick="remove_alt_image(this)" style="display:none"><span class="dashicons dashicons-trash"></span></span>
														</div>
													</div>
												</div>
												<div class="primer_image_error_message"></div>
											</div>
										</div>

										<!-- Location -->
										<div class="field_group">
											<table class="form-table">
												<tbody>
													<tr>
														<th>
															<label>Location</label>
														</th>
														<td>
															<input class="" value="" type="text" name="gallery[` + row_key + `][location]" />
															<div class="primer-tooltip">
																<span class="dashicons dashicons-editor-help"></span>
																<span class="tooltiptext">Type the location the image was shot in.</span>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</div>

										<!-- Products -->
										<div class="field_group products_group postbox">
											<button type="button" class="handlediv" onclick="toggle(this)" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Shoppable Image</span><span class="toggle-indicator" aria-hidden="true"></span></button>
											<h3>Product Information</h3>
												<div class="inside">
												<div class="products-row-wrap">
													<div class="products-row">
														<div class="form_field">
															<table class="form-table">
																<tbody>
																	<tr>
																		<th>
																			<label>Product URL*</label>
																		</th>
																		<td>
																			<input class="regular-text url" value="" type="text" name="gallery[` + row_key + `][products][` + product_row_key + `][url]" />
																			<div class="primer-tooltip">
																				<span class="dashicons dashicons-editor-help"></span>
																				<span class="tooltiptext">Paste the link to the exact product page here. Use naked URL’s only, links must not be affiliate shortened. Altered URLs or URLs to product categories will not generate an error, but will not be indexed by engines.</span>
																			</div>
																			<span class="primer-product-description">Links must not be affiliate shortened</span>
																		</td>
																	</tr>
																	<tr>
																		<th>
																			<label>Product Name & Brand</label>
																		</th>
																		<td>
																			<input class="regular-text" value="" type="text" name="gallery[` + row_key + `][products][` + product_row_key + `][product_name]" />
																			<div class="primer-tooltip">
																				<span class="dashicons dashicons-editor-help"></span>
																				<span class="tooltiptext">Type the name and brand of the product here.</span>
																			</div>
																		</td>
																	</tr>
																</tbody>
															</table>
															<a onclick="remove_product(this)" class="remove_row"><span class="dashicons dashicons-no"></span></a>
															<div style="clear: both"></div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<input class="button add_product" type="button" value="Add Product" onclick="add_product_row(this);" />
										<div style="clear: both"></div>
									</div>
								</div>
							`


				jQuery(html).appendTo('#field_wrap');
				jQuery(".url").rules("add", {
					required:true,
					onkeyup: false,
					onclick: false
				});
			}

			function toggle(element) {
					var parent = jQuery(element).closest('.postbox');
					parent.toggleClass('closed');
			}


			function add_alt_image_row(obj){
				var parent = jQuery(obj).parent().parent();
				var id = jQuery(jQuery(obj).parents('.field_row')[0]).attr('data-id')
				var row =
				`
							<div class="form_field">
								<input class="primer_image" value="" type="hidden" name="gallery[`+ id +`][alt_image_ids][]" />
								<div class="primer-image-input-container">
									<div class="primer-image-input" onclick="add_image(this, true)">
										<div class="primer-image-input-preview"></div>
									</div>
									<span class="button remove-image" onclick="remove_alt_image(this)" style="display:none"><span class="dashicons dashicons-trash"></span></span>
								</div>
							`
				jQuery(row).appendTo(parent.find('.alt-image-row-wrap'));
			}

			function add_product_row(obj){

				var parent = jQuery(obj).parent();
				var id = jQuery(jQuery(obj).parents('.field_row')[0]).attr('data-id')
				var product_row_key = new Date().getTime();
				var row =
				`
						<div class="products-row">
								<div class="form_field">
									<table class="form-table">
										<tbody>
											<tr>
												<th>
													<label>Product URL*</label>
												</th>
												<td>
													<input class="regular-text url" value="" type="text" name="gallery[` + id + `][products][` + product_row_key + `][url]" />
													<div class="primer-tooltip">
														<span class="dashicons dashicons-editor-help"></span>
														<span class="tooltiptext">Paste the link to the exact product page here. Use naked URL’s only, links must not be affiliate shortened. Altered URLs or URLs to product categories will not generate an error, but will not be indexed by engines.</span>
													</div>
													<span class="primer-product-description">Links must not be affiliate shortened</span>
												</td>
											</tr>
											<tr>
												<th>
													<label>Product Name & Brand</label>
												</th>
												<td>
													<input class="regular-text" value="" type="text" name="gallery[` + id + `][products][` + product_row_key + `][product_name]" />
													<div class="primer-tooltip">
														<span class="dashicons dashicons-editor-help"></span>
														<span class="tooltiptext">Type the name and brand of the product here.</span>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
									<a onclick="remove_product(this)" class="remove_row"><span class="dashicons dashicons-no"></span></a>
									<div style="clear: both"></div>
								</div>
						</div>`
				jQuery(row).appendTo(parent.find('.products-row-wrap'));
				jQuery(".url").rules("add", {
				  required:true,
					onkeyup: false,
   				onclick: false
				});
			}

			function remove_alt_image(obj){
				var parent=jQuery(obj).parent().parent();
				parent.remove();
			}

			function remove_product(obj){
				var parent=jQuery(obj).parent().parent();
				parent.remove();
			}
    </script>
    <?php
}

function primer_update_post_gallery( $post_id, $post_object ){

	$had_primer_items = false;
	// Check if already had items with Primer
	$gallery_data = get_post_meta( $post_id, 'primer_data', true );
	if( is_array( $gallery_data ) ) {
		if( count( $gallery_data ) > 0 ) {
			$had_primer_items = true;
		}else{
			$had_primer_items = false;
		}
	}

	// Doing revision, exit earlier **can be removed**
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	return;

	if (isset($_POST['action'])){
		if ( $_POST['action'] == 'inline-save' )
		return;
		if( $_POST['action'] != 'editpost' )
		return;
	}

	// Doing revision, exit earlier
	if ( 'revision' == $post_object->post_type )
	return;

	// Verify authenticity
	if ( isset($_POST['primer_by_chloedigital_nonce']) && !wp_verify_nonce( $_POST['primer_by_chloedigital_nonce'], plugin_basename( __FILE__ ) ) )
	return;

	// Correct post type
	if ( isset($_POST['post_type']) && 'post' != $_POST['post_type'] )
	return;

	if ( isset($_POST['gallery']) ){
		$gallery_data = $_POST['gallery'];
		// Validate fields
		foreach ($gallery_data as $key => $data):
			// Check for Main Image
			if(!$data['image_id']):
				// Invalid - Show error message
			endif;

			// Check for products
			if(!isset($data['products'])):
				// Invalid - Show error message
			endif;

			// Remove empty alt images
			if (version_compare(phpversion(), '5.3.0', '>')):
				$gallery_data[$key]['alt_image_ids'] = array_filter( $data['alt_image_ids'], function($value) { return $value !== ''; } );
			else:
				$gallery_data[$key]['alt_image_ids'] = array_filter( $data['alt_image_ids'], create_function('$value', 'return $value !== "";') );
			endif;

			// Remove empty products
			if (version_compare(phpversion(), '5.3.0', '>')):
				if(isset($data['products'])):
					$gallery_data[$key]['products'] = array_filter( $data['products'], function($value) { return $value['url'] !== ''; } );
				endif;
			else:
				if(isset($data['products'])):
					$gallery_data[$key]['products'] = array_filter( $data['products'], create_function('$value', 'return $value["url"] !== "";') );
				endif;
			endif;

		endforeach;

		// Sanitize
		foreach ($gallery_data as $key => $data):
			$gallery_data[$key]['image_id'] = filter_var($data['image_id'], FILTER_SANITIZE_NUMBER_INT);

			$gallery_data[$key]['location'] = strip_tags( trim( $gallery_data[$key]['location'] ) );

			foreach ($gallery_data[$key]['alt_image_ids'] as $a_key => $alt_image) :
				$gallery_data[$key]['alt_image_ids'][$a_key] = filter_var($gallery_data[$key]['alt_image_ids'][$a_key], FILTER_SANITIZE_NUMBER_INT);
			endforeach;
			if(isset($gallery_data[$key]['products'])):
				foreach ($gallery_data[$key]['products'] as $p_key => $product) :
					$gallery_data[$key]['products'][$p_key]['product_name'] = strip_tags( trim( $gallery_data[$key]['products'][$p_key]['product_name'] ) );
					$gallery_data[$key]['products'][$p_key]['url'] = filter_var( $gallery_data[$key]['products'][$p_key]['url'] , FILTER_SANITIZE_URL);
					if (!filter_var($gallery_data[$key]['products'][$p_key]['url'], FILTER_VALIDATE_URL)):
						$gallery_data[$key]['products'][$p_key]['url'] = "";
					endif;
				endforeach;
			endif;
		endforeach;

		if ( $gallery_data ):
			pbcd_save_frontend_code($post_id, $gallery_data);
			try {
				pbcd_post_data($post_id, $gallery_data);
			} catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			}
			update_post_meta( $post_id, 'primer_data', $gallery_data );
		else:
			delete_post_meta( $post_id, 'primer_data' );
			delete_post_meta( $post_id, 'primer_data_frontend' );
		endif;
	}else{
		delete_post_meta( $post_id, 'primer_data' );
	}
	pbcd_update_primer_post_count($post_id, $had_primer_items);
}

function pbcd_update_primer_post_count($post_ID, $had_primer_items){
	global $wpdb;

	$table_name = $wpdb->prefix .'primer_data';
	$settings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}primer_data WHERE ID = 1", OBJECT );
	if(property_exists($settings[0], 'primer_posts_count')){ // Check if primer_post_count exists
		$primer_posts_count = $settings[0]->primer_posts_count;
		$gallery_data = get_post_meta( $post_ID, 'primer_data', true );
		if ( is_array($gallery_data) ) {
			if(count($gallery_data) > 0 ){
				if(!$had_primer_items){
					$primer_posts_count = $primer_posts_count + 1;
					$wpdb->update(
						$table_name, array( 'primer_posts_count' => $primer_posts_count, ), array('id'=>"1" )
					);
				}
			}else{
				if($had_primer_items){
					$primer_posts_count = $primer_posts_count - 1;
					$wpdb->update(
						$table_name, array( 'primer_posts_count' => $primer_posts_count, ), array('id'=>"1" )
					);
				}
			}
		}
	}
}

function pbcd_post_data($post_id, $primer_data){
	$parsed_data["post_id"] = get_the_ID();
	$parsed_data["blog_url"] = get_site_url();
	$parsed_data["post_url"] = get_the_permalink();
	$parsed_data["items"] = array();
	if ( isset($primer_data) && is_array($primer_data) ):
		foreach ($primer_data as $key => $data):

			$item["main_image"] = wp_get_attachment_image_url($data['image_id']);
			$item["location"] = $data['location'];

			// Products
			$products = array();
			if ( isset($data['products']) && is_array($data['products']) ):
				foreach ($data['products'] as $p_key => $product_data):
					$product["name"] = $product_data["product_name"];
					$product["url"] = $product_data["url"];
					array_push($products, $product);
				endforeach;
			endif;
			$item["products"] = $products;

			// Additional Images (optional)
			$alt_images = array();
			if ( isset($data['alt_image_ids']) && is_array($data['alt_image_ids']) ):
				foreach ($data['alt_image_ids'] as $alt_key => $alt_data):
					$alt_image = wp_get_attachment_image_url($alt_data);
					array_push($alt_images, $alt_image);
				endforeach;
			endif;
			$item["alt_images"] = $alt_images;

			array_push($parsed_data["items"], $item);
		endforeach;
	endif;

	$data_to_send["tracking_data"] = $parsed_data;

	$response = wp_remote_post( 'http://analytics.chloedigital.com/primer/post_details', array(
	 'method' => 'POST',
	 'timeout' => 15,
	 'redirection' => 5,
	 'httpversion' => '1.0',
	 'blocking' => true,
	 'Content-Type' => 'application/json',
	 'headers' => array('x-application-secret' => 'b7f53urnGGL03SC0f62d2774ef14kFto81DCr5ae109758382ce049urtnXXq0eaaaefba061048thnd32Kc41c17d3404'),
	 'body' => $data_to_send,
	 'cookies' => array()
	 )
	);
}

function pbcd_save_frontend_code($post_id, $gallery_data){
	$author_id = get_post_field ('post_author', $post_id);
	$author = get_the_author_meta('display_name', $author_id);
	$code = "";
	if ( isset($gallery_data) && is_array($gallery_data) ):
	  foreach ($gallery_data as $key => $data):

	    // Best View image (required) with products
	    $code .=  '<script type="application/ld+json">';
	    $code .=  '{';
	    $code .=  '"@context": "http://schema.org",';
	    $code .=  '"@type": "ImageObject",';
	    $code .=  '"author": "'. $author .'",';
	    $code .=  '"mainEntityOfPage": {';
	    $code .=  '"@type": "WebPage",';
	    $code .=  '"@id": "'. get_the_permalink() . '"';
	    $code .=  '}, ';

	    if(isset($data['location'])):
	      $code .=  '"contentLocation": "'. $data['location'] .'",';
	    endif;

			$urllocal = explode(site_url(), wp_get_attachment_image_url( $data['image_id'], 'full'))[1]; //output local path

	    $code .=  '"contentUrl": "'. site_url() . $urllocal .'",';
	    $code .=  '"datePublished": "'.get_the_date('Y-m-d').'",';
	    $code .=  '"offers":[';
	    if ( isset($data['products']) && is_array($data['products']) ):
	      $index = 0;
	      foreach ($data['products'] as $p_key => $product_data):
	        $code .=  '{';
	        $code .=  '"@type": "Offer",';
	        $code .=  '"name": "' . $product_data["product_name"] . '",';
	        $code .=  '"url": "' . $product_data["url"] . '"';
	        $code .=  ($index == count($data['products']) - 1) ? '}' : '},';
	        $index++;
	      endforeach;
	    endif;
	    $code .=  ']';
	    $code .=  '}';
	    $code .=  '</script>';

	    if ( isset($data['alt_image_ids']) && is_array($data['alt_image_ids']) ):
	      foreach ($data['alt_image_ids'] as $alt_key => $alt_data):
	        // Alt images with same products as Best View image (required)
	        $code .=  '<script type="application/ld+json">';
	        $code .=  '{';
	        $code .=  '"@context": "http://schema.org",';
	        $code .=  '"@type": "ImageObject",';
	        $code .=  '"author": "'. $author .'",';

	        if(isset($data['location'])):
	          $code .=  '"contentLocation": "'. $data['location'] .'",';
	        endif;

	        $code .=  '"contentUrl": "'. site_url() . $urllocal .'",';
	        $code .=  '"datePublished": "'.get_the_date('Y-m-d').'",';
	        $code .=  '"offers":[';
	        if ( isset($data['products']) && is_array($data['products']) ):
	          $index = 0;
	          foreach ($data['products'] as $p_key => $product_data):
	            $code .=  '{';
	            $code .=  '"@type": "Offer",';
	            $code .=  '"name": "' . $product_data["product_name"] . '",';
	            $code .=  '"url": "' . $product_data["url"] . '"';
	            $code .=  ($index == count($data['products']) - 1) ? '}' : '},';
	            $index++;
	          endforeach;
	        endif;
	        $code .=  ']';
	        $code .=  '}';
	        $code .=  '</script>';

	      endforeach;
	    endif;

	  endforeach;
	endif;

	update_post_meta( $post_id, 'primer_data_frontend', $code );
}

?>
