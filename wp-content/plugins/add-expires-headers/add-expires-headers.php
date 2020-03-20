<?php

/*
Plugin Name: Add Expires Headers
Plugin URI: http://www.addexpiresheaders.com/wp-plugin
Description: This plugin will add expires headers for various types of resources of website to have better performance optimization.
Author: Passionate Brains
Version: 2.0
Author URI: http://www.addexpiresheaders.com/
License: GPLv2 or later
*/
/* initiating freemius */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'dd_aeh' ) ) {
    dd_aeh()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'dd_aeh' ) ) {
        // Create a helper function for easy SDK access.
        function dd_aeh()
        {
            global  $dd_aeh ;
            
            if ( !isset( $dd_aeh ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $dd_aeh = fs_dynamic_init( array(
                    'id'             => '5598',
                    'slug'           => 'AddExpiresHeaders',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_ba20d9daf118a0e03f28dbbc805e3',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'    => 'aeh_pro_plugin_options',
                    'support' => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $dd_aeh;
        }
        
        // Init Freemius.
        dd_aeh();
        // Signal that SDK was initiated.
        do_action( 'dd_aeh_loaded' );
    }
    
    /* Defining some of constant which will be helpful throughout */
    if ( !defined( 'AEH_BASENAME' ) ) {
        define( 'AEH_BASENAME', plugin_basename( __FILE__ ) );
    }
    if ( !defined( 'AEH_DIR' ) ) {
        define( 'AEH_DIR', plugin_dir_path( __FILE__ ) );
    }
    if ( !defined( 'AEH_URL' ) ) {
        define( 'AEH_URL', plugin_dir_url( __FILE__ ) );
    }
    if ( !defined( 'SITE_URL' ) ) {
        define( 'SITE_URL', site_url() );
    }
    if ( !defined( 'SITE_DOMAIN' ) ) {
        define( 'SITE_DOMAIN', trim( str_ireplace( array( 'http://', 'https://' ), '', trim( SITE_URL, '/' ) ) ) );
    }
    if ( !defined( 'AEH_PREFIX' ) ) {
        define( 'AEH_PREFIX', 'AEH_' );
    }
    if ( !defined( 'AEH_VERSION' ) ) {
        define( 'AEH_VERSION', '1.0' );
    }
    /* Definining main class */
    if ( !class_exists( 'AEH_Pro' ) ) {
        class AEH_Pro
        {
            private static  $instance = null ;
            private  $main ;
            private  $admin ;
            public static function get_instance()
            {
                if ( !self::$instance ) {
                    self::$instance = new self();
                }
                return self::$instance;
            }
            
            private function __construct()
            {
                $this->includes();
                $this->init();
            }
            
            /*loads other support classes*/
            private function includes()
            {
                /*
                 * Settings class.
                 */
                require_once AEH_DIR . 'main/class-aeh-settings.php';
                /*
                 * Include core class.
                 */
                require_once AEH_DIR . 'main/class-aeh-main.php';
                /*
                 * Include admin class.
                 */
                require_once AEH_DIR . 'main/class-aeh-admin.php';
            }
            
            /* init support classes*/
            private function init()
            {
                $this->main = new AEH_Main();
                $this->admin = new AEH_Admin();
            }
            
            /* returning main class object */
            public function main()
            {
                return $this->main;
            }
            
            /* returning admin class object */
            public function admin()
            {
                return $this->admin;
            }
            
            public static function dd_aeh_uninstall_cleanup()
            {
                AEH_Pro::get_instance()->main()->remove_settings();
            }
            
            /* checking compatibility for plugin to get activated and working */
            public static function aeh_compat_checker()
            {
                global  $wp_version ;
                $error = '';
                #getiing wp version upto 2 decimal points
                # php version requirements
                if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
                    $error = 'Add Expires Headers requires PHP 5.4 or higher. You’re still on ' . PHP_VERSION;
                }
                if ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) {
					if ( stripos( $_SERVER['SERVER_SOFTWARE'], 'Apache' ) !== false ) {
						# php extension requirements
						if (function_exists('apache_get_modules')){
							if ( !in_array( 'mod_expires', apache_get_modules() ) ) {
								$error = 'Add Expires Headers requires the apache mod_expires module to be installed on the server.';
							}
						} else {
							$error = 'Not getting apache modules info';
                        }							
					} else {
						$error = 'plugin compatibility is limited to apache only';
					}
                } else {
					$error = 'we did not get server software info for compatibility check';
				}	
                # wp version requirements
                if ( version_compare( $GLOBALS['wp_version'], '4.5', '<' ) ) {
                    $error = 'Add Expires Headers requires WP 4.5 or higher. You’re still on ' . $GLOBALS['wp_version'];
                }
                # disabling plugin on error
                
                if ( is_plugin_active( plugin_basename( __FILE__ ) ) && !empty($error) || !empty($error) ) {
                    if ( isset( $_GET['activate'] ) ) {
                        unset( $_GET['activate'] );
                    }
                    //deactivate_plugins( plugin_basename( __FILE__ ) );
                    add_action( 'admin_notices', function () use( $error ) {
                        echo  '<div class="notice notice-error is-dismissible"><p>' . $error . '<strong> Please Deactivate Add Expires Headers Plugin for Normal Operation of Website</strong></p></div>' ;
                    } );
                    $current_url = $_SERVER['REQUEST_URI'];
                    $plugin_url = admin_url( 'plugins.php' );
                    if ( is_admin() ) {
                        if ( !stripos( $plugin_url, $_SERVER['REQUEST_URI'] ) ) {
                            wp_redirect( $plugin_url );
                        }
                    }
                }
            
            }
        
        }
    }
    // Init the plugin and load the plugin instance for the first time.
    add_action( 'plugins_loaded', array( 'AEH_Pro', 'get_instance' ) );
    add_action( 'admin_init', array( 'AEH_Pro', 'aeh_compat_checker' ) );
    /* deactivation hook */
    dd_aeh()->add_action( 'after_uninstall', array( 'AEH_Pro', 'dd_aeh_uninstall_cleanup' ) );
    register_deactivation_hook( __FILE__, array( 'AEH_Pro', 'dd_aeh_uninstall_cleanup' ) );
}