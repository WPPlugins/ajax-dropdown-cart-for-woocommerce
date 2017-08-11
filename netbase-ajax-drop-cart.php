<?php
/*
Plugin Name: WooCommerce Ajax Drop Down Cart - Netbase
Plugin URI: http://cmsmart.net/
Description: drop down cart for woocommerce
Version: 1.0.0
Author: Netbaseteam
Author URI: http://netbasejsc.com/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!defined('NB_AJAX_DROP_CART_PATH')) define( 'NB_AJAX_DROP_CART_PATH', plugin_dir_path(__FILE__) );
if(!defined('NB_AJAX_DROP_CART_URL')) define( 'NB_AJAX_DROP_CART_URL', plugin_dir_url(__FILE__) );

include_once(NB_AJAX_DROP_CART_PATH . "includes/class-ajax-drop-cart.php");

add_action( 'init', 'netbase_load_textdomain' );

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function netbase_load_textdomain() {
  load_plugin_textdomain( 'netbasecart', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

/**
 * Initialization of 'NB_ADCart' class
 */
$_GLOBALS['nbadcart'] = new NB_ADCart();

if(!function_exists("woocommerce_mini_cart")) {
	function woocommerce_mini_cart( $args = array() ) {

	    $defaults = array( 'list_class' => '' );
	    $args = wp_parse_args( $args, $defaults );

	    woocommerce_get_template( 'cart/mini-cart.php', $args );

	}
}
/*shortcode [wpnetbase_ajaxcart]*/

function wpnetbase_adcart($args)
    {
        if (empty($args))
        {
            $args = array();
        }
        include(NB_AJAX_DROP_CART_PATH . "templates/base.php");
    }
add_shortcode('wpnetbase_ajaxcart', 'wpnetbase_adcart');

//add cart to header
add_action( 'wp_head','cartshowdm' );
function cartshowdm(){
   // require_once('templates/base.php');
    include(NB_AJAX_DROP_CART_PATH . "templates/base.php");
}

function nb_get_template($file, $args) {

	extract($args);
	include_once(dirname(__FILE__)."/../".$file);

}

/**
 * activation/deactivation hooks
 *
 * multi-site ready
 */

register_activation_hook( __FILE__, 'nb_adcart_activate' );
register_deactivation_hook( __FILE__, 'nb_adcart_deactivate' );

function nb_adcart_activate($networkwide) {
    global $wpdb;

    if (function_exists('is_multisite') && is_multisite()) {
        // check if it is a network activation - if so, run the activation function for each blog id
        if ($networkwide) {
            $old_blog = $wpdb->blogid;
            // Get all blog ids
            $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blogids as $blog_id) {
                switch_to_blog($blog_id);
                _nb_adcart_activate();
            }
            switch_to_blog($old_blog);
            return;
        }
    }
    _nb_adcart_activate();
}

function nb_adcart_deactivate($networkwide) {
    global $wpdb;

    if (function_exists('is_multisite') && is_multisite()) {
        // check if it is a network activation - if so, run the activation function for each blog id
        if ($networkwide) {
            $old_blog = $wpdb->blogid;
            // Get all blog ids
            $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ($blogids as $blog_id) {
                switch_to_blog($blog_id);
                _nb_adcart_deactivate();
            }
            switch_to_blog($old_blog);
            return;
        }
    }
    _nb_adcart_deactivate();
}

function _nb_adcart_activate() {

	if(!get_option( "nbadcart_plugin_settings" )) {

		$settings = array(
            'adcart-skin' => 'pink',
            'adcart-style' => 'cus',
			'adcart-position' => 'top',
            'adcart-numsub' => 'sub',
			'adcart-subtotal' => 1,
			'adcart-border-radius' => 0,
            'adcart-width'  => 300,

			'adcart-icon-color' => '000000',
			'adcart-text-color' => '000000',
			'adcart-link-color' => '000000',
            'adcart-link-hover-color'  => '90949a',
			'adcart-button-text-color' => 'ffffff',
            'adcart-button-bg-color' => 'e4e4e4',
            'adcart-button-bghv-color' => '000000',
			'adcart-background-color' => 'ffffff',
			'adcart-background-border-color' => 'e4e4e4',
            'adcart-icon-display' =>'show',
            'adcart-icon-skin' => '0',
			'adcart-icon-style' => '1',
			'adcart-custom-icon' => '',
			'adcart-use-custom-icon' => 0,
			'adcart-drop-trigger' => 'click',
            'adcart-speed' => 2000,
            'adcart-x' => 10,
            'adcart-y' => 10,
			//'adcart-display-cart' => array('shop', 'category', 'product_tag', 'product', 'cart', 'checkout', 'account')
		);

		add_option( "nbadcart_plugin_settings", $settings );
	}
    $settings = true;
    wp_cache_set( 'settings', $settings );

}

function _nb_adcart_deactivate() {

	if(get_option( "nbadcart_plugin_settings" )) {
		delete_option('nbadcart_plugin_settings');
	}

}
//img + pick color
function nb_enqueue_scripts(){  
    if( is_admin() ) {
        wp_enqueue_media();
        wp_enqueue_style( 'wp-color-picker' );      
    }
}
add_action( 'admin_enqueue_scripts','nb_enqueue_scripts');
/**
 *
 * multi-site
 */
add_action( 'wpmu_new_blog', 'nb_adcart_new_blog', 10, 6);

function bw_adcart_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    global $wpdb;

    if (is_plugin_active_for_network('woocommerce-netbase-addcart/netbase-ajax-drop-cart.php')) {
        $old_blog = $wpdb->blogid;
        switch_to_blog($blog_id);
        _nb_adcart_activate();
        switch_to_blog($old_blog);
    }
}

// add_action( 'init', 'woocommerce_clear_cart_url' );
// function woocommerce_clear_cart_url() {
//     global $woocommerce;
//     if ( isset( $_GET['empty-cart'] ) ) {
//         $woocommerce->cart->empty_cart();
//     }
// }
// add_action( 'woocommerce_cart_actions', 'patricks_add_clear_cart_button', 20 );
// function patricks_add_clear_cart_button() {
//     echo "<a class='button' href='?empty-cart=true'>" . __( 'Empty Cart', 'woocommerce' ) . "</a>";
// }