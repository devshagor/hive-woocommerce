<?php 
/*
Plugin Name:  hive-woocommerce
Plugin URI: http://github.com/hive-woo/
Author: devshagor
Author URI: https://github.com/devshagor/
Version: 1.0.0
Text Domain: hive-woocommerce
Description: hive-woocommerce is a assessment for hive-woocommerce WordPress.
*/

if ( ! defined( 'ABSPATH' ) ) {
	wp_die(esc_html__("Direct Access Not Allow",'hive-woocommerce'));
}

function hive_woo_add_admin_page(){
    // Add main menu item
    add_menu_page('Hive Options','Hive Woo','manage_options','devshagor_hive_woo','hive_woo_plugin_create_page',plugins_url("/assets/images/woo.png",__FILE__),110);
    
    // Activate Custom Settings
    add_action("admin_init","hive_woo_custom_settings");
}

add_action('admin_menu','hive_woo_add_admin_page');


function hive_woo_custom_settings(){
    register_setting("hive_woo-settings-group","cart_quantity");

    add_settings_section('hive_woo-page-options','Hive WooCommerce Options','','devshagor_hive_woo');

    add_settings_field('cart_quantity', "Cart Discount Percentange","hive_woo_quantity", "devshagor_hive_woo", "hive_woo-page-options");
}


function hive_woo_quantity(){
    $cart_discount = esc_attr(get_option('cart_quantity'));
    echo '<input type="number" class="plugs-input" name="cart_quantity" value="'.$cart_discount.'" />';
}


/**
 *  enqueue scripts and styles
 */
function hive_woo_assets() {
    wp_enqueue_style( 'hive_woo', plugins_url("/assets/css/style.css",__FILE__),false,"1.0.0" );
    wp_enqueue_script("hive_woo-js", plugins_url("/assets/js/script.js",__FILE__),array("jquery"),"1.0.0",true);
}
add_action( 'wp_enqueue_scripts', 'hive_woo_assets' );

/**
 * Admin scripting assets
 */
function hive_woo_custom_admin_assets() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( "admin-style", plugins_url("/assets/css/admin-style.css",__FILE__),false,"1.0.0" );
    
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_script("hive_woo-js", plugins_url("/assets/js/admin-script.js",__FILE__),array("jquery"),"1.0.0",true);
}
add_action( 'admin_enqueue_scripts', 'hive_woo_custom_admin_assets' );

/**
 * For main menu 
 */
function hive_woo_plugin_create_page(){
    require_once("inc/templates/admin.php");
} 

// woo-hive cart quantity function 
add_action( 'woocommerce_cart_calculate_fees','wc_cart_quantity_discount', 10, 1 );

if(! function_exists ('wc_cart_quantity_discount')) {
    function wc_cart_quantity_discount( $cart_object ) {
        if ( is_admin() && ! defined( 'DOING_AJAX' ) )
            return;

        // define variable to set discount 
        $percent = 0;
        $discount = 0;
        $cart_item_count = $cart_object->get_cart_contents_count();
        $cart_total_excl_tax = $cart_object->subtotal_ex_tax;

        // checking the conditional percentage
        // if( $cart_item_count <= 2 ) {	
        // 	$percent = 0;
        // }

        $cart_discount = esc_attr(get_option('cart_quantity'));

        // var_dump( $cart_discount);

        if( $cart_item_count >= 3 && $cart_discount !='' ) {
            $percent = $cart_discount;
        }
        

        // discount calculation  
        $discount -= ($cart_total_excl_tax / 100) * $percent;

        // applying calculated discount taxable 
        if( $percent > 0 ) {
            $cart_object->add_fee( __( "Quantity discount $percent%", "storefront-child" ), $discount, true);
        }
    }
}

// product ajax load 
require_once("inc/templates/ajax-product.php");

