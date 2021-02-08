<?php
/**
 * Core functions 
 * 
 * @since      1.0.0
 * @package    Woocommerce_Fps
 * @subpackage Woocommerce_Fps/includes
 */
class WC_Fps {
	protected $loader;
	protected $plugin_name;

	protected $version;

	public function __construct() {
		if (defined('WC_FPS_VERSION')) {
			$this->version = WC_FPS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woocommerce-fps';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();		

		$this->loader->add_action('plugins_loaded', $this, 'load_gateways', 11);
	}

	private function load_dependencies() {
		require plugin_dir_path(dirname(__FILE__)) . 'includes/api/api-helpers.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wc-fps-loader.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wc-fps-i18n.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wc-fps-admin.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wc-fps-public.php';
		$this->loader = new WC_Fps_Loader();
	}

	public function load_gateways() {
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/payment-methods/class-wc-fps-gateway-offline.php';
		$this->define_gateways();
	}

	private function set_locale() {
		$plugin_i18n = new WC_Fps_i18n();
		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	private function define_admin_hooks() {
		$plugin_admin = new WC_Fps_Admin($this->get_plugin_name(), $this->get_version());

		// Actions
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('manage_shop_order_posts_custom_column', $plugin_admin, 'add_reference_number_to_order_list', 11, 1);
		$this->loader->add_action('woocommerce_admin_order_data_after_order_details', $plugin_admin, 'add_reference_number_to_order_detail');

		// Filters
		$this->loader->add_filter('woocommerce_payment_gateways', $plugin_admin, 'add_gateways');
		$this->loader->add_filter('plugin_action_links_' . plugin_basename(__FILE__), $plugin_admin, 'add_plugin_links');
	}

	private function define_public_hooks() {
		$plugin_public = new WC_Fps_Public($this->get_plugin_name(), $this->get_version());

		// Actions
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		// Shortcodes
		$this->loader->add_shortcode('fps_reference_number_input', $plugin_public, 'fps_reference_number_input_shortcode');
	}

	public function define_gateways() {
		$fps_offline = new WC_Fps_Gateway_Offline();
		add_action('woocommerce_update_options_payment_gateways_' . $fps_offline->id, array($fps_offline, 'process_admin_options'));
		add_action('woocommerce_thankyou_' . $fps_offline->id, array($fps_offline, 'thankyou_page'));
		add_action('woocommerce_email_before_order_table', array($fps_offline, 'email_instructions'), 10, 3);
		add_action('woocommerce_order_details_before_order_table', array($fps_offline, 'order_detail_reference_number_input'), 10, 1);
		add_action('woocommerce_get_order_item_totals', array($fps_offline, 'order_detail_instructions'), 10, 3);
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}
}