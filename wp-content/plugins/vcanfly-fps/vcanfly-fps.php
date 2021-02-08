<?php
/**
 * Plugin Name: Vcanfly FPS
 * Description: Enable FPS payment provided via Vcanfly FPS.
 * Version: 1.0.2
 * Author: Vcanfly
 * Text Domain:	vcanfly-fps
 * Domain Path: /language
 */
if ( ! defined( 'ABSPATH' ) ) exit; 
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return;

include dirname( __FILE__ ) .'/includes/functions.php';
include dirname( __FILE__ ) .'/includes/settings.php';
include dirname( __FILE__ ) .'/includes/gateway.php';
include dirname( __FILE__ ) .'/includes/wc_gateway_offline.php';

function vcanfps_qrcode_callback($order_id)
{
  load_plugin_textdomain( 'vcanfly-fps', false, dirname(plugin_basename(__FILE__ )).'/language/' );
  $order = wc_get_order( $order_id );

  $option = vcanfps_get_options();
  $apiendpoint = $option['fpsplugin_apiendpoint'];
  $userid = $option['fpsplugin_userid'];
  $apikey = $option['fpsplugin_apikey'];

  if(strcasecmp($order->get_status(),'on-hold')==0){
      if(!empty($apiendpoint)&&!empty($userid)&&!empty($apikey))
      {
				$body = array(
					'userId' => $userid,
					'amount' => (double)$order->get_total(),
					'refId'  => vcanfps_buildRefId($order)
				);

				$body = wp_json_encode($body);

				$header = array(
					'Content-Type' => 'application/json',
					'x-api-key'    => $apikey
				);

				$args = array(
					'body'  => $body,
					'headers'     => $header,
				);

				$response = wp_remote_post( $apiendpoint, $args );
				$response_body = wp_remote_retrieve_body( $response );

				if (is_wp_error($response)) {
					echo __( 'Error on loading FPS QR code, please refresh this page or check your settings.', 'vcanfly-fps' );
				} else {
					$jsonData = json_decode($response_body,true);

					$url = $jsonData["url"];

					echo '<div class="fpsqrcode">';
					echo __( 'Please scan the FPS QR code to complete payment.', 'vcanfly-fps' );
					echo "<br/>";
					echo __( 'Please verify payment amount.', 'vcanfly-fps' );
					echo "<br/>";
					echo __( 'Thank you for your order!', 'vcanfly-fps' );
					echo "<br/>";
					echo '<img src="'.$url.'" />';
					echo '</div>';
					echo '<hr/>';
				}
	    }else{
	  echo __( 'Plugin setting not working, please check your setting', 'vcanfly-fps' );
	    }
	}
}

add_action('woocommerce_order_details_after_order_table','vcanfps_qrcode_callback');

/**
 * Add action link for setting to admin > plugin list
 **/
function vcanfps_action_links( $links ) {
	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/options-general.php?page=vcanfps' ) ) . '">' . __( 'Settings', 'vcanfly-fps' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'vcanfps_action_links' );

?>