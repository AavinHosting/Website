<?php
class Featuredlite_Popup {
  function active(){
        $this->default_homepage_setup();
    if(!get_option( "thunk_customizer_disable_popup")):
    add_action('customize_controls_print_styles', array($this,'popup_styles'));
    add_action( 'customize_controls_enqueue_scripts', array($this,'popup_scripts'));
    endif;
  }

function active_plugin(){
        $plugin_slug = 'themehunk-customizer';
        $active_file_name =  $plugin_slug.'/'.$plugin_slug.'.php';
        $button_class = 'install-now button button-primary button-large';

                $button_txt = esc_html__( 'Install Plugin & Setup Homepage', 'featuredlite' );
                $status     = is_dir( WP_PLUGIN_DIR . '/'.$plugin_slug );

                if ( ! $status ) {
                    $install_url = wp_nonce_url(
                        add_query_arg(
                            array(
                                'action' => 'install-plugin',
                                'plugin' => $plugin_slug
                            ),
                            network_admin_url( 'update.php' )
                        ),
                        'install-plugin_'.$plugin_slug
                    );

                } else {
                    $install_url = add_query_arg(array(
                        'action' => 'activate',
                        'plugin' => rawurlencode( $active_file_name ),
                        'plugin_status' => 'all',
                        'paged' => '1',
                        '_wpnonce' => wp_create_nonce('activate-plugin_' . $active_file_name ),
                    ), network_admin_url('plugins.php'));
                    $button_class = 'activate-now button-primary button-large';
                    $button_txt = esc_html__( 'Setup Homepage', 'featuredlite' );
                }

        $url = esc_url($install_url);
    return "<a href='javascript:void' onclick=\"featuredlite_install('{$url}'); return false;\"  data-slug='".esc_attr($plugin_slug)."' class='".esc_attr( $button_class )."'>{$button_txt}</a>";

}

function popup_styles() {
    wp_enqueue_style('featuredlite_customizer_popup', get_template_directory_uri() . '/inc/theme-setup/customizer-popup-styles.css');
}

function popup_scripts() {
    wp_enqueue_script( 'featuredlite_customizer_popup', get_template_directory_uri() . '/inc/theme-setup/customizer-popup.js', array("jquery"), '', true  );
}
 function default_homepage_setup() {
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'home-template.php'
    ));
      $post_id = isset($pages[0]->ID)?$pages[0]->ID:false;
    if(empty($pages)){
          $post_id = wp_insert_post(array (
           'post_type' => 'page',
           'post_title' => 'Home',
           'post_content' => '',
           'post_status' => 'publish',
           'comment_status' => 'closed',   // if you prefer
           'ping_status' => 'closed',      // if you prefer
           'page_template' =>'home-template.php', //Sets the template for the page.
        ));
      }
          if($post_id && get_option( 'page_on_front')==$post_id){
            update_option( 'page_on_front', $post_id );
            update_option( 'show_on_front', 'page' );
        }
 }
}
function customizer_disable_popup(){
      $value = intval(@$_POST['value']);
      update_option( "thunk_customizer_disable_popup", $value );
      die();
  }
add_action('wp_ajax_customizer_disable_popup', 'customizer_disable_popup');