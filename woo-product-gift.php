<?php
/**
*Plugin Name: Gift Products For Woocommerce
*Description: This plugin allows create product gift.
* Version: 1.0
* Author: Ocean Infotech
* Author URI: https://www.xeeshop.com
* Copyright: 2019 
*/

if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('OCWG_PLUGIN_NAME')) {
  define('OCWG_PLUGIN_NAME', 'Woocommerce Gift Product');
}
if (!defined('OCWG_PLUGIN_VERSION')) {
  define('OCWG_PLUGIN_VERSION', '1.0.0');
}
if (!defined('OCWG_PLUGIN_FILE')) {
  define('OCWG_PLUGIN_FILE', __FILE__);
}
if (!defined('OCWG_PLUGIN_DIR')) {
  define('OCWG_PLUGIN_DIR',plugins_url('', __FILE__));
}
if (!defined('OCWG_DOMAIN')) {
  define('OCWG_DOMAIN', 'ocwg');
}

if (!class_exists('OCWG')) {

  	class OCWG {

	    protected static $OCWG_instance;
	    /**
	   	* Constructor.
	   	*
	   	* @version 3.2.3
	   	*/
	    function __construct() {
	        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	        //check plugin activted or not
	        add_action('admin_init', array($this, 'OCWG_check_plugin_state'));
	    }


	    function OCWG_check_plugin_state(){
	      	if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
	        	set_transient( get_current_user_id() . 'ocwgerror', 'message' );
	      	}
	    }


	    function init() {
	      	add_action( 'admin_notices', array($this, 'OCWG_show_notice'));
	      	add_action( 'admin_enqueue_scripts', array($this, 'OCWG_load_admin'));
	      	add_action( 'wp_enqueue_scripts',  array($this, 'OCWG_load_front'));
	      	add_filter( 'wp_enqueue_scripts', array($this, 'insert_jquery'),1);
	    }


	    function OCWG_show_notice() {
	        if ( get_transient( get_current_user_id() . 'ocwgerror' ) ) {

	          	deactivate_plugins( plugin_basename( __FILE__ ) );

	          	delete_transient( get_current_user_id() . 'ocwgerror' );

	          	echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">WooCommerce</a> plugin installed and activated.</p></div>';
	        }
	    }

	    function insert_jquery() {
		    wp_enqueue_script('jquery', false, array(), false, false);
	    }

	    //Add JS and CSS on Backend
	    function OCWG_load_admin() {
	      	wp_enqueue_style( 'OCWG_admin_style', OCWG_PLUGIN_DIR . '/includes/css/wg_admin_style.css', false, '1.0.0' );
	      	wp_enqueue_script( 'OCWG_admin_script', OCWG_PLUGIN_DIR . '/includes/js/wg_admin_script.js', array( 'jquery', 'select2') );
	      	wp_localize_script( 'ajaxloadpost', 'ajax_postajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	      	wp_enqueue_style( 'woocommerce_admin_styles-css', WP_PLUGIN_URL. '/woocommerce/assets/css/admin.css',false,'1.0',"all");
	    }

	    function OCWG_load_front() {
		    wp_enqueue_style( 'OCWG_front_style', OCWG_PLUGIN_DIR . '/includes/css/wg_front_style.css', false, '1.0.0' );
		    wp_enqueue_style( 'OCWG_owl-min', OCWG_PLUGIN_DIR . '/includes/js/owlcarousel/assets/owl.carousel.min.css' );
	        wp_enqueue_style( 'OCWG_owl-theme', OCWG_PLUGIN_DIR . '/includes/js/owlcarousel/assets/owl.theme.default.min.css');
	        wp_enqueue_script( 'OCWG_owl', OCWG_PLUGIN_DIR . '/includes/js/owlcarousel/owl.carousel.js' );
	    }

	    //Load all includes files
	    function includes() {
		    include_once('admin/wg-backend.php');		    
		    include_once('front/wg-frontend.php');
	    }

	    //Plugin Rating
	    public static function OCWG_do_activation() {
	      	set_transient('wg-first-rating', true, MONTH_IN_SECONDS);
	    }

	    public static function OCWG_instance() {
	      	if (!isset(self::$OCWG_instance)) {
	        	self::$OCWG_instance = new self();
	        	self::$OCWG_instance->init();
	        	self::$OCWG_instance->includes();
	      	}
	      	return self::$OCWG_instance;
	    }
  	}
  	add_action('plugins_loaded', array('OCWG', 'OCWG_instance'));
  	register_activation_hook(OCWG_PLUGIN_FILE, array('OCWG', 'OCWG_do_activation'));
}
