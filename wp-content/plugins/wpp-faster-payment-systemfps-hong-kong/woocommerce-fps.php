<?php
/**
 * @link              https://bearycommerce.com/
 * @since             1.0.0
 * @package           Woocommerce_Fps
 * 
 * Plugin Name: WooCommerce 轉數快 Faster Payment System (FPS) Hong Kong
 * Description: The easiest way to integrate 轉數快 Faster Payment System (FPS) Hong Kong to your Wordpress WooCommerce shopping cart
 * Version: 1.0.4
 * Author: BearyCommerce
 * Author URI: https://bearycommerce.com/
 * Text Domain: woocommerce-fps
 * Domain Path: /i18n/languages/
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	return;
}

define( 'WC_FPS_VERSION', '1.0.4' );

function activate_wc_fps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-fps-activator.php';
	WC_Fps_Activator::activate();
}

function deactivate_wc_fps() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-fps-deactivator.php';
	WC_Fps_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_wc_fps' );
register_deactivation_hook( __FILE__, 'deactivate_wc_fps' );

require plugin_dir_path( __FILE__ ) . 'includes/class-wc-fps.php';

function run_wc_fps() {
	$plugin = new WC_Fps();
	$plugin->run();
}
run_wc_fps();