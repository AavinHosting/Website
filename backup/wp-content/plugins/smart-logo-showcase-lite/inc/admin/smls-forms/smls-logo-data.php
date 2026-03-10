<?php
defined('ABSPATH') or die("No script kiddies please!");
$image_url = $_POST[ 'image_url' ];
$image_id = $_POST[ 'image_id' ];
$logo_key = $this->smls_generate_random_string(15);
$logo = 'logo_' . $logo_key;
$smls_field_prefix = "logo_$logo_key";
$logo_data_array = array( $logo_key => array( 'logo_image_url' => $image_url, 'logo_image_id' => $image_id ) );
$logo_data_array = http_build_query($logo_data_array);
?>
<div class="smls-each-logo-item" data-logo-key="<?php echo esc_attr($logo); ?>">
    <input type="hidden" name="smls_option[logo][]" class="smls_logo_details_data" value="<?php echo $logo_data_array; ?>"/>
    <div class="smls-logo-image-preview">
        <div class="smls-each-logo-actions-wrap clearfix">
            <a href="javascript:void(0)" class="smls-edit-logo"><span class="dashicons dashicons-edit"></span></a>
            <a href="javascript:void(0)" class="smls-move-logo"><span class="dashicons dashicons-move"></span></a>
            <a href="javascript:void(0)" class="smls-settings-logo"><span class="dashicons dashicons-admin-generic"></span></a>
            <a href="javascript:void(0)" class="smls-delete-logo"><span class="dashicons dashicons-trash"></span></a>
        </div>
        <div class="smls-setting-image">
            <img  class="smls-logo-image" src="<?php echo esc_attr($image_url); ?>" alt="" width="250">

        </div>
    </div>
    <div class="smls-add-logo-option-wrap" style="display: none;">
        <div class="smls-setting-overlay"></div>
        <div class="smls-logo-item-detail-fields">
            <h4><?php _e('Logo Details', SMLS_TD); ?><span class="dashicons dashicons-no smls-close-popup"></span></h4>
            <div class="smls-option-wrapper">
                <label for="smls_logo_title" class="smls-logo-title"><?php _e('Logo Title', SMLS_TD); ?></label>
                <div class="smls-option-field">
                    <input type="text" class="smls-logo-title" name="<?php echo esc_attr($smls_field_prefix . '[title]'); ?>"  value=""/>
                </div>
            </div>
            <div class="smls-option-wrapper">
                <label for="smls-show-contact-info" class="smls-show-logo-social-link"><?php _e( 'Contact Info', SMLS_TD ); ?></label>
                <div class="smls-option-field">
                    <label class="smls-logo-contact-info-check"><input type="checkbox" class="smls-logo-contact-info"><?php _e( 'Check to show contact details', SMLS_TD ) ?></label>
                    <input type="hidden" name="<?php echo esc_attr( $smls_field_prefix . '[logo_contact_details]' ); ?>" class="smls-logo-contact-info-value" value="0">
                </div>
            </div>
            <div class="smls-contact-detail-wrap"  style="display: none;">
                <div class="smls-option-wrapper">
                    <label for="smls_contact_heading" class="smls-contact-heading"><?php _e( 'Contact Heading', SMLS_TD ); ?></label>
                    <div class="smls-option-field">
                        <input type="text" class="smls-contact-heading" name="<?php echo esc_attr( $smls_field_prefix . '[contact_heading]' ); ?>"  value=""/>
                    </div>
                </div>
                <div class="smls-option-wrapper">
                    <label for="smls_company_name" class="smls-logo-company-name"><?php _e( 'Company Name', SMLS_TD ); ?></label>
                    <div class="smls-option-field">
                        <input type="text" class="smls-company-name" name="<?php echo esc_attr( $smls_field_prefix . '[company_name]' ); ?>"  value=""/>
                    </div>
                </div>
                <div class="smls-option-wrapper">
                    <label for="smls_company_address" class="smls-logo-company-address"><?php _e( 'Company Address', SMLS_TD ); ?></label>
                    <div class="smls-option-field">
                        <input type="text" class="smls-company-address" name="<?php echo esc_attr( $smls_field_prefix . '[company_address]' ); ?>"  value=""/>
                    </div>
                </div>
            </div>
            <div class="smls-option-wrapper">
                <label for="smls-show-link-info" class="smls-show-external-link"><?php _e('External Link', SMLS_TD); ?></label>
                <div class="smls-option-field">
                    <label class="smls-logo-external-link-check"><input type="checkbox" class="smls-logo-external-link-info" <?php checked($smls_value_prefix[ 'logo_external_link' ], 1) ?>><?php _e('Check to show external link', SMLS_TD) ?></label>
                    <input type="hidden" name="<?php echo esc_attr($smls_field_prefix . '[logo_external_link]'); ?>" class="smls-logo-external-link-value" value="">
                </div>
            </div>
            <div class="smls-external-link-wrap" style="display: none;">
                <div class="smls-option-wrapper">
                    <label for="smls_external_link" class="smls-logo-external_url">
                        <?php _e('External URL', SMLS_TD); ?></label>
                        <div class="smls-option-field">
                            <input type="text" class="smls-logo-
                            external-url" name="<?php echo esc_attr($smls_field_prefix . '[logo_external_url]'); ?>"  value=""/>
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0);" class="button-secondary smls-logo-save-trigger" style="display:none;"><?php _e('Save', SMLS_TD); ?></a>
                <input type="hidden" class="smls-logo-image-url" name="<?php echo esc_attr($smls_field_prefix . '[logo_image_url]'); ?>"  value="<?php echo esc_attr($image_url); ?>" />
                <input type="hidden" class="smls-logo-image-id" name="<?php echo esc_attr($smls_field_prefix . '[logo_image_id]'); ?>"  value="<?php echo esc_attr($image_id); ?>" />
            </div>
        </div>
    </div>

