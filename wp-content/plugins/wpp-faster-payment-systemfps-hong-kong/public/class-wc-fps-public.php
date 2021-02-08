<?php
/**
 * Define functions for public area
 * 
 * @since      1.0.0
 * @package    Woocommerce_Fps
 * @subpackage Woocommerce_Fps/public
 */
class WC_Fps_Public {
	private $plugin_name;
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-fps-public.css', array(), $this->version, 'all' );
  }
  
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-fps-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Load public area templates
	 * 
   * @since      1.0.0
   * @param string $template_name
   * @return string template content
   */
	private function load_template($template_name, $echo=false) {
		if (file_exists(plugin_dir_path( __FILE__ ) . 'partials/' . $template_name)) {
			return wc_fps_load_template(plugin_dir_path( __FILE__ ) . 'partials/' . $template_name, $echo);
		} else {
			return false;
		}
	}

	/**
	 * Shortcode for fps reference number input
	 * 
   * @since      1.0.0
   * @param	array $atts
	 * @param string $content
	 * @param string $tag
	 * @return string reference number display
	 * 
   */
	public function fps_reference_number_input_shortcode($atts=[], $content=null, $tag='') {
		$atts = array_change_key_case((array)$atts, CASE_LOWER);
		$form_atts = shortcode_atts([
			'order_id' => '',
		], $atts, $tag);

		if (!$form_atts['order_id']) {
			return false;
		}

		$order = wc_get_order($form_atts['order_id']);
		$fps_reference_no = $order->get_meta('_fps_reference_no');
		if (!$fps_reference_no) {
			// Handle reference number submission
			if (isset($_POST['fps_reference_number']) && $_POST['fps_reference_number'] && isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'update_fps_reference_number')) {
				$fps_reference_no = sanitize_text_field($_POST['fps_reference_number']);
				$order->update_meta_data('_fps_reference_no', $fps_reference_no);
				$order->save();
				unset($_POST['fps_reference_number']);
			} else {
				return $this->load_template('wc-fps-reference-number.php');
			}
		}
		return '';
	}
}