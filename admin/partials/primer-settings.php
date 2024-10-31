<div class="wrap">
    <h2></h2>
    <?php if( isset($_GET['msg']) ){ ?>
        <?php if( $_GET['msg'] == "success" || $_GET['msg'] == "privacy-accepted"){ ?>
            <div class="notice notice-success is-dismissible">
                <p>Saved!</p>
            </div>
        <?php } else {?>
            <div class="notice notice-warning is-dismissible">
                <p>PRIMER by chloédigital is disabled</p>
            </div>
        <?php } ?>
    <?php } ?>

    <div class="primer_header_logo_container">
        <img class="primer_header_logo" src="<?php echo plugin_dir_url(__FILE__);?>primer-logo.png">
    </div>

    <form method="POST" id="primer_options" name="primer_options" action="<?php echo esc_attr( admin_url('admin-post.php') ); ?>" >
        
        <input type="hidden" name="action" value="save_primer_data" />

        <div class="primer-blog-name-container">
            <fieldset>
                <h2><!-- empty saved for success message --></h2>
                <h2>Website Name</h2>
                <span>Enter your website name here</span>
                <br/>
                <input type="text" class="regular-text" id="primer_blog_name" name="primer_blog_name" placeholder="Blog Name" value="<?=$results[0]->primer_blog_name;?>"required/>
            </fieldset>
        </div>

        <div class="primer-blog-image-container">
            <!-- Upload image -->
            <fieldset>
                <h2>Profile Image</h2>
                <span>Upload a square (1x1 ratio) image here. Choose a logo, photograph or submark that represents the blog.</span>
                <br/>
                <?php
                    $image_id = $results[0]->primer_image_id;
                    if( intval( $image_id ) > 0 ) {
                        // Selected image
                        $image = wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'id' => 'primer-preview-image' ) );
                    } else {
                        // Default image
                        $image = '<img style="width:200px;height:200px;" id="primer-preview-image" src="'.plugin_dir_url(__FILE__).'placeholder-image.png" />';
                    }
                ?>
                <?php echo $image; ?>
                <br/>
                <input type="hidden" name="primer_image_id" id="primer_image_id" value="<?php echo esc_attr( $image_id ); ?>" class="regular-text" value="<?=$results[0]->primer_image_id?>"/>
                <div id="primer_image_error_message" style="color:red;display:none;">Image is too small, must be 80px min.</div>
                <input type="text" name="primer_image_url" id="primer_image_url" value="<?php echo wp_get_attachment_url( $image_id ); ?>" class="regular-text"  />

                <input type='button' class="button-primary" value="<?php esc_attr_e( 'Select a image', 'mytextdomain' ); ?>" id="primer_media_manager"/>

            </fieldset>
            <fieldset>
                <div class="primer_checkbox_container">
                    <?php if ($results[0]->primer_agreement_accepted == "1") { $checked = "checked"; }else{ $checked = ""; } ?>
                    <input onchange="this.setCustomValidity(validity.valueMissing ? 'Please, indicate that you accept the End User License' : '');" type="checkbox" name="primer_agreement_accepted" value="1" <?=$checked;?> required> I accept the <a target="_blank" href="https://chloedigital.com/primer/end-user-license/">End User License</a><br>
                    <br>
                    <?php if ($results[0]->primer_privacy_accepted == "1") { $checked = "checked"; }else{ $checked = ""; } ?>
                    <input onchange="this.setCustomValidity(validity.valueMissing ? 'Please, indicate that you accept the Privacy Policy' : '');" type="checkbox" name="primer_privacy_accepted" value="1" <?=$checked;?> required> I accept the <a target="_blank" href="https://chloedigital.com/primer/privacy-policy/">Privacy Policy</a><br>
                </div>
            </fieldset>
        </div>
        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>
    </form>

    <div class="primer-help-box">
        <div class="primer-help-box-copy">
            <h2>Connect with us</h2>
            Follow along on social for PRIMER tips, updates and more!
            Follow along on <a href="https://www.instagram.com/primerbycd/" target="_blank" >Instagram</a> for PRIMER tips, updates and more!
        </div>
    </div>
</div>