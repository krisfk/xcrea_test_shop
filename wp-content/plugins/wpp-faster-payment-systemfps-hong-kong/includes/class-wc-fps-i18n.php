<?php
/**
 * Define functions for internationalization
 * 
 * @since      1.0.0
 * @package    Woocommerce_Fps
 * @subpackage Woocommerce_Fps/includes
 */
class WC_Fps_i18n {
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'woocommerce-fps',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/i18n/languages/'
		);
	}
}