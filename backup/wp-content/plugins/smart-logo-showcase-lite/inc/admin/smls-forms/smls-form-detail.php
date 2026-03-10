<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );
global $post;
$post_id = $post -> ID;
$smls_option = get_post_meta( $post_id, 'smls_option', true );
$smls_field_prefix = $logo_key;
$smls_value_prefix = $smls_option[ 'logo' ][ $logo_key ] = $logo_detail;
?>

<div class="smls-each-logo-item" data-logo-key="<?php echo $logo_key; ?>">
    <input type="hidden" name="smls_option[logo][]" class="smls_logo_details_data" value="<?php echo $logo_serialized_detail; ?>"/>
    <div class="smls-logo-image-preview">
        <div class="smls-each-logo-actions-wrap clearfix">
            <a href="javascript:void(0)" class="smls-edit-logo"><span class="dashicons dashicons-edit"></span></a>
            <a href="javascript:void(0)" class="smls-move-logo"><span class="dashicons dashicons-move"></span></a>
            <a href="javascript:void(0)" class="smls-settings-logo"><span class="dashicons dashicons-admin-generic"></span></a>
            <a href="javascript:void(0)" class="smls-delete-logo"><span class="dashicons dashicons-trash"></span></a>
        </div>
        <div class="smls-setting-image">
            <img  class="smls-logo-image" src="<?php echo esc_attr( $smls_value_prefix[ 'logo_image_url' ] ); ?>" alt="">
        </div>
    </div>
    <div class="smls-add-logo-option-wrap" style="display: none;">
        <div class="smls-setting-overlay"></div>
        <div class="smls-logo-item-detail-fields">
            <h4><?php _e( 'Logo Details', SMLS_TD ); ?><span class="dashicons dashicons-no smls-close-popup"></span></h4>
            <div class="smls-option-wrapper">
                <label for="smls_logo_title" class="smls-logo-title"><?php _e( 'Logo Title', SMLS_TD ); ?></label>
                <div class="smls-option-field">
                    <input type="text" class="smls-logo-title" name="<?php echo esc_attr( $smls_field_prefix . '[title]' ); ?>"  value="<?php
                    if ( isset( $smls_value_prefix[ 'title' ] ) ) {
                        echo esc_attr( $smls_value_prefix[ 'title' ] );
                    }
                    ?>"/>
                </div>
            </div>

<!-- Updated Part -->

            <div class="smls-option-wrapper">
            <label for="smls-show-contact-info" class="smls-show-logo-social-link"><?php _e( 'Contact Info', SMLS_TD ); ?></label>
            <div class="smls-option-field">
                <label class="smls-logo-contact-info-check"><input type="checkbox" class="smls-logo-contact-info" <?php
                if ( isset( $smls_value_prefix[ 'logo_contact_details' ] ) )
                    checked( $smls_value_prefix[ 'logo_contact_details' ], 1 )
                ?>><?php _e( 'Check to show contact details', SMLS_TD ) ?></label>
                <input type="hidden" name="<?php echo esc_attr( $smls_field_prefix . '[logo_contact_details]' ); ?>" class="smls-logo-contact-info-value" value="<?php
                if ( isset( $smls_value_prefix[ 'logo_contact_details' ] ) ) {
                    echo esc_attr( $smls_value_prefix[ 'logo_contact_details' ] );
                }
                ?>">
            </div>
        </div>
        <div class="smls-contact-detail-wrap" <?php if ( isset( $smls_value_prefix[ 'logo_contact_details' ] ) && $smls_value_prefix[ 'logo_contact_details' ] != 1 ) { ?> style="display: none;" <?php } ?>>
            <div class="smls-option-wrapper">
                <label for="smls_contact_heading" class="smls-contact-heading"><?php _e( 'Contact Heading', SMLS_TD ); ?></label>
                <div class="smls-option-field">
                    <input type="text" class="smls-contact-heading" name="<?php echo esc_attr( $smls_field_prefix . '[contact_heading]' ); ?>"  value="<?php
                    if ( isset( $smls_value_prefix[ 'contact_heading' ] ) ) {
                        echo esc_attr( $smls_value_prefix[ 'contact_heading' ] );
                    }
                    ?>"/>
                </div>
            </div>
            <div class="smls-option-wrapper">
                <label for="smls_company_name" class="smls-logo-company-name"><?php _e( 'Company name', SMLS_TD ); ?></label>
                <div class="smls-option-field">
                    <input type="text" class="smls-company-name" name="<?php echo esc_attr( $smls_field_prefix . '[company_name]' ); ?>"  value="<?php
                    if ( isset( $smls_value_prefix[ 'company_name' ] ) ) {
                        echo esc_attr( $smls_value_prefix[ 'company_name' ] );
                    }
                    ?>"/>
                </div>
            </div>
            <div class="smls-option-wrapper">
                <label for="smls_company_address" class="smls-logo-company-address"><?php _e( 'Company Address', SMLS_TD ); ?></label>
                <div class="smls-option-field">
                    <input type="text" class="smls-company-address" name="<?php echo esc_attr( $smls_field_prefix . '[company_address]' ); ?>"  value="<?php
                    if ( isset( $smls_value_prefix[ 'company_address' ] ) ) {
                        echo esc_attr( $smls_value_prefix[ 'company_address' ] );
                    }
                    ?>"/>
                </div>
            </div>
        </div>
 <!-- Updated Till Here -->       
            <div class="smls-option-wrapper">
                <label for="smls-show-link-info" class="smls-show-external-link"><?php _e( 'External Link', SMLS_TD ); ?></label>
                <div class="smls-option-field">
                    <label class="smls-logo-external-link-check"><input type="checkbox" class="smls-logo-external-link-info" <?php
                    if ( isset( $smls_value_prefix[ 'logo_external_link' ] ) )
                        checked( $smls_value_prefix[ 'logo_external_link' ], 1 )
                    ?>><?php _e( 'Check to show external link', SMLS_TD ) ?>
                </label>
                <input type="hidden" name="<?php echo esc_attr( $smls_field_prefix . '[logo_external_link]' ); ?>" class="smls-logo-external-link-value" value="<?php
                if ( isset( $smls_value_prefix[ 'logo_external_link' ] ) ) {
                    echo esc_attr( $smls_value_prefix[ 'logo_external_link' ] );
                }
                ?>">
            </div>
        </div>

        <div class="smls-external-link-wrap" <?php if ( isset( $smls_value_prefix[ 'logo_external_link' ] ) && $smls_value_prefix[ 'logo_external_link' ] != 1 ) { ?> style="display: none;" <?php } ?>>
            <div class="smls-option-wrapper">
                <label for="smls_external_link" class="smls-logo-external-url">
                    <?php _e( 'External URL', SMLS_TD ); ?></label>
                    <div class="smls-option-field">
                        <input type="text" class="smls-logo-
                        external-url" name="<?php echo esc_attr( $smls_field_prefix . '[logo_external_url]' ); ?>"  value="<?php
                        if ( isset( $smls_value_prefix[ 'logo_external_url' ] ) ) {
                         echo esc_attr( $smls_value_prefix[ 'logo_external_url' ] );
                     }
                     ?>"/>
                 </div>
             </div>
         </div>
         <input type="hidden" class="smls-logo-image-url" name="<?php echo esc_attr( $smls_field_prefix . '[logo_image_url]' ); ?>"  value="<?php
         if ( isset( $smls_value_prefix[ 'logo_image_url' ] ) ) {
            echo esc_attr( $smls_value_prefix[ 'logo_image_url' ] );
        }
        ?>" />
        <input type="hidden" class="smls-logo-image-id" name="<?php echo esc_attr( $smls_field_prefix . '[logo_image_id]' ); ?>"  value="<?php
        if ( isset( $smls_value_prefix[ 'logo_image_id' ] ) ) {
            echo esc_attr( $smls_value_prefix[ 'logo_image_id' ] );
        }
        ?>" />
        <a href="javascript:void(0);" class="button-secondary smls-logo-save-trigger"><?php _e( 'Save', SMLS_TD ); ?></a>
    </div>
</div>
</div>