<?php
/**
 * Define functions for admin area
 * 
 * @since      1.0.0
 * @package    Woocommerce_Fps
 * @subpackage Woocommerce_Fps/admin
 */
class WC_Fps_Admin {
	private $plugin_name;
	private $version;

	public function __construct($plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wc-fps-admin.css', array(), $this->version, 'all');
  }
  
	public function enqueue_scripts() {
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wc-fps-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * Add FPS gateways to WC gateway list
	 * 
	 * @since 1.0.0
	 * @param array $gateways all available WC gateways
	 * @return array $gateways all WC gateways + FPS gateways
	 */
	public function add_gateways($gateways) {
		$gateways[] = 'WC_Fps_Gateway_Offline';
		return $gateways;
	}

	/**
	 * Adds plugin page links
	 * 
	 * @since 1.0.0
	 * @param array $links all plugin links
	 * @return array $links all plugin links + configuration link
	 */
	public function add_plugin_links($links) {
		$plugin_links = array(
			'<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=offline_gateway') . '">' . __('Settings', 'woocommerce-fps') . '</a>'
		);
		
		return array_merge($plugin_links, $links);
	}

	/**
	 * Load admin area templates
	 * 
   * @since      1.0.0
   * @param string $template_name
   * @return string template content
   */
	private function load_template($template_name, $params=array(), $echo=false) {
		if (file_exists(plugin_dir_path( __FILE__ ) . 'partials/' . $template_name)) {
			return wc_fps_load_template(plugin_dir_path( __FILE__ ) . 'partials/' . $template_name, $params, $echo);
		} else {
			return false;
		}
	}

	/**
	 * Add fps reference number to order list
	 * 
	 * @since 1.0.0
	 * @param array $column
	 * @return array $links all plugin links + configuration link
	 */
	public function add_reference_number_to_order_list($column) {
		global $post;
	
		if ($column == 'order_number') {
			$order = wc_get_order($post->ID);
			$payment_method = $order->get_payment_method();
			if (preg_match('/^fps_/', $payment_method) === 1) {
				$reference_no = $order->get_meta('_fps_reference_no');
				$this->load_template('wc-fps-reference-number.php', array('reference_no' => $reference_no), true);
			}
		}
	}

	/**
	 * Add fps reference number to order detail
	 * 
	 * @since 1.0.0
	 * @param array $column
	 * @return array $links all plugin links + configuration link
	 */
	public function add_reference_number_to_order_detail($order) {
		$payment_method = $order->get_payment_method();
		if (preg_match('/^fps_/', $payment_method) === 1) {
			$reference_no = $order->get_meta('_fps_reference_no');
			$this->load_template('wc-fps-reference-number.php', array('reference_no' => $reference_no), true);
		}
	}
}