<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Typehub_Store {

    private $store;
    private $data;
    
    public function __construct() {
        $this->store = array();
        $this->data = get_option( 'typehub_data', array(
            'font_schemes' => array(),
            'values' => array(),
            'settings' => array(),
            'custom' => array()
        ));	
    }

    public function get_store() {
         
        $this->store['fontSchemes'] = $this->get_fonts();
        $this->store['optionConfig'] = $this->get_options();
        $this->store['savedValues'] = $this->data['values'];
        $this->store['settings'] = !empty( $this->data['settings'] ) ? $this->data['settings'] : array();
        $this->store['custom'] =  !empty( $this->data['custom'] ) ? $this->data['custom'] : array();

        return $this->store;

    }

    public function get_fonts() {
        return Typehub_Font_Schemes::getInstance()->get_schemes();
    }

    public function get_options() {
        $predefined_options = Typehub_Options::getInstance()->get_options();
        $custom_options = array();
        // CUSTOM OPTIONS feature moved to a later update.
        if( !empty( $this->data['custom']['options'] ) ) {
            $custom_options = $this->data['custom']['options'];
            return array_merge( $predefined_options, $custom_options );
        }
        return $predefined_options;
    }
    
    public function ajax_save() {
        check_ajax_referer( 'typehub-security', 'security' );

        if( !array_key_exists( 'store', $_POST ) ) {
            echo 'failure';
            wp_die();
        }

        $store = json_decode( stripslashes( $_POST['store'] ), true );
        $data['fontSchemes'] = ( array_key_exists( 'fontSchemes', $store ) ) ? $store['fontSchemes'] : array();
        $data['savedValues'] = ( is_array( $store['initConfig']['savedValues'] ) ) ? $store['initConfig']['savedValues'] : array();
        $data['settings'] = is_array( $store['settings']) ? $store['settings'] : array();
        $data['custom'] = is_array( $store['custom']) ? $store['custom'] : array();
        $save_store = $this->save_store( $data );
        if( $save_store ) {
            echo 'success';
        } else {
            echo 'failure';
        }
        wp_die();
    }
    
    public function ajax_get_typekit_fonts() {

        check_ajax_referer( 'typehub-security', 'security' );

        if( !array_key_exists( 'typekitId', $_POST ) ) {
            echo 'failure';
            wp_die();
        }
        $typekit_data = typehub_get_typekit_data($_POST['typekitId']);
        if( empty( $typekit_data ) ){
            echo false;
        } else {
            echo json_encode( $typekit_data );
        }
        

        wp_die();
    }

    public function ajax_get_local_font_details(){

        check_ajax_referer( 'typehub-security', 'security' );
        
        $local_fonts = get_saved_fonts();

        echo json_encode($local_fonts);
        wp_die(); 
    }

    public function ajax_download_font(){

        check_ajax_referer( 'typehub-security', 'security' );
        
        if( !array_key_exists( 'fontName', $_POST ) ) {
            echo 'failure';
            wp_die();
        }
        $result = typehub_download_font_from_google( $_POST['fontName'] );
        echo $result;
        wp_die();
    }
    public function ajax_refresh_changes(){

        typehub_delete_unused_fonts();
        $saved_fonts = get_saved_fonts();
        foreach( $saved_fonts as $saved_font => $value ){
            typehub_write_css_link_to_file( $value );
        }
        echo 'success';
        wp_die();

    }

    public function ajax_sync_typekit( ){
        check_ajax_referer( 'typehub-security', 'security' );
        
        if( !array_key_exists( 'typekitId', $_POST ) ) {
            echo 'failure';
            wp_die();
        }
        $typekitId = $_POST['typekitId'];
        delete_transient( 'typehub_typekit_'.$typekitId );
        echo 1;
        wp_die();
    }

    public function ajax_add_custom_font(){

        $filename = $_FILES["file"]["name"];
        $filebasename = basename($filename, '.zip');
        $upload_dir = wp_upload_dir();
        $typehub_font_dir = $upload_dir['basedir'] . '/'. 'typehub/custom/'. $filebasename .'/';
        $typehub_font_url = $upload_dir['baseurl'] . '/'. 'typehub/custom/'. $filebasename .'/styles.css';

        if( file_exists( $typehub_font_dir ) ){
            $result = array(
                'status' => 'file already exists'
            );
            echo json_encode($result);
            wp_die();
        }

        $upload = wp_upload_bits($filename, null, file_get_contents($_FILES["file"]["tmp_name"]));
        $access_type = get_filesystem_method();
        if( empty( $upload['error'] ) ){

            if( $access_type !== 'direct' ){
                $result = array(
                    'status' => 'write permission denied'
                );
                echo json_encode($result);
                wp_die();
            }

            global $wp_filesystem;
            if ( empty( $wp_filesystem ) ) {
                require_once ( ABSPATH.'/wp-admin/includes/file.php' );
                WP_Filesystem();
            }

            $zip_handle = unzip_file($upload['file'], $typehub_font_dir );

            if( !is_wp_error( $zip_handle ) ){

                $zip_content = list_files( $typehub_font_dir, 1 );
                $compatible_formats = array('.otf','.ttf','.woff','.woff2','.svg','.eot','.html','.css');
                $count = 0;
                foreach( $zip_content as $item){
                    foreach( $compatible_formats as $format ){
                        $endsWith = substr_compare( $item, $format, -strlen( $format ) ) === 0;
                        if($endsWith){
                            $count++;
                        }
                    }
                }

                if( $count === count($zip_content) ){
                    $result = array(
                        'status' => 'success',
                        'url'  => $typehub_font_url,
                        'name' => $filebasename
                    );
                    wp_delete_file($upload['file']);
                } else {
                    $result = array(
                        'status' => 'invalid_zip'
                    );
                    wp_delete_file($upload['file']);
                    $wp_filesystem->rmdir( $typehub_font_dir, true );
                }


            } else {
                $result = array(
                    'status' => 'failed',
                    'url'  => $typehub_font_url,
                    'name' => $filebasename
                );
            }

        } else {
            $result = array(
                'status' => 'failed'
            );
        }

        echo json_encode($result);
        wp_die();
    }

    public function ajax_remove_custom_font(){
        $name = $_POST['name'];

        global $wp_filesystem;
        if ( empty( $wp_filesystem ) ) {
            require_once ( ABSPATH.'/wp-admin/includes/file.php' );
            WP_Filesystem();
        }

        $upload_dir = wp_upload_dir();
        $typehub_font_dir = $upload_dir['basedir'] . '/'. 'typehub/custom/'. $name.'/';

        $wp_filesystem->rmdir( $typehub_font_dir, true );
        echo 'success';
        wp_die();
    }

    public function save_store( $data ) {
        
        $this->data['font_schemes'] = $data['fontSchemes'];
        $this->data['values'] = $data['savedValues'];
        $this->data['settings'] = $data['settings'];
        $this->data['custom'] = $data['custom'];

        return update_option( 'typehub_data', $this->data );
    }
    
}