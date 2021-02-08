<?php
/**
 * Define FPS offline payment gateway
 * 
 * @since      1.0.0
 * @package    Woocommerce_Fps
 * @subpackage Woocommerce_Fps/includes
 */
class WC_Fps_Gateway_Offline extends WC_Payment_Gateway {
  protected $account;

  public function __construct() {
    $this->id                 = 'fps_offline_gateway';
    $this->icon               =  plugin_dir_url( dirname( __DIR__ ) ) . 'public/images/fps-icon-small.png';
    $this->has_fields         = false;
    $this->method_title       = __( 'FPS Payments', 'woocommerce-fps' );
    $this->method_description = __( "Allows FPS payments. If you need any help on customized FPS integration, feel free to contact us at <a href='mailto:info@bearycommerce.com'>info@bearycommerce.com</a>", 'woocommerce-fps' );
    $this->account = get_option('woocommerce_fps_offline_gateway_account');

    // Load the settings.
    $this->init_form_fields();
    $this->init_settings();

    // Define user set variables
    $this->title              = $this->get_option( 'title' );
    $this->description        = $this->get_option( 'description' );
    $this->instructions       = $this->get_option( 'instructions', $this->description );
		$this->enabled            = $this->get_option( 'enabled' );
  }

  /**
   * @since      1.0.0
	 * Initialize Gateway Settings Form Fields.
	 */
  public function init_form_fields() {
    $this->form_fields = apply_filters( 'wc_fps_offline_form_fields', array(
      'enabled' => array(
        'title'   => __( 'Enable/Disable', 'woocommerce-fps' ),
        'type'    => 'checkbox',
        'label'   => __( 'Enable FPS Payments', 'woocommerce-fps' ),
        'default' => 'yes'
      ),
      
      'title' => array(
        'title'       => __( 'Title', 'woocommerce-fps' ),
        'type'        => 'text',
        'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'woocommerce-fps' ),
        'default'     => __( 'FPS Payments', 'woocommerce-fps' ),
        'desc_tip'    => true,
      ),
      
      'description' => array(
        'title'       => __( 'Description', 'woocommerce-fps' ),
        'type'        => 'textarea',
        'description' => __( 'Payment method description that the customer will see on your checkout.', 'woocommerce-fps' ),
        'default'     => __( 'Please remit payment to the following FPS Account:', 'woocommerce-fps' ),
        'desc_tip'    => true,
      ),
      
      'instructions' => array(
        'title'       => __( 'Instructions', 'woocommerce-fps' ),
        'type'        => 'textarea',
        'description' => __( 'Instructions that will be added to the thank you page and emails.', 'woocommerce-fps' ),
        'default'     => __( 'Please provide your reference number in Order Detail page after remitted payment through FPS.', 'woocommerce-fps' ),
        'desc_tip'    => true,
      ),
    ));
  }

  /**
   * @since      1.0.0
	 * Payment description at checkout page
	 */
  public function payment_fields() {
		$description = $this->get_description();
		if ( $description ) {
			echo wpautop( wptexturize( $description ) );
    }
    echo '<div class="payment-fps-tooltip">'. __( 'How to use FPS payment?', 'woocommerce-fps' ) .'<img class="payment-fps-tooltip-img" src="' . plugin_dir_url( dirname( __DIR__ ) ) . 'public/images/fps-instruction.jpg"></img></div>';
	}

  /**
   * @since      1.0.0
	 * Add content to the order received page.
	 */
  public function thankyou_page() {
    if ( $this->instructions ) {
      echo wpautop( wptexturize( $this->instructions ) );
    }
  }

  /**
   * @since      1.0.0
   * Add content to the WC emails.
   *
   * @access public
   * @param WC_Order $order
   * @param bool $sent_to_admin
   * @param bool $plain_text
   */
  public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
    if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
      echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
    }
  }

  /**
   * @since      1.0.0
   * Process the payment and return the result
   *
   * @param int $order_id
   * @return array
   */
  public function process_payment( $order_id ) {
    $order = wc_get_order( $order_id );
    $order->update_status( 'on-hold', __( 'Awaiting FPS payments', 'woocommerce-fps' ) );
    $order->reduce_order_stock();
    WC()->cart->empty_cart();
    
    return array(
      'result' 	=> 'success',
      'redirect'	=> $this->get_return_url( $order )
    );
  }

  /**
   * @since      1.0.0
   * Add payment instruction and reference number fields to order detail page
   *
   * @param array $total_rows
   * @param WC_Order $order
   * @param mixed $tax_display
   * @return array
   */
  public function order_detail_instructions($total_rows, $order, $tax_display) {
    $payment_method = $order->get_payment_method();
    $order_status = $order->get_status();
    if ($payment_method == $this->id) {
      $reference_no = $order->get_meta('_fps_reference_no');
      $payment_description = array(
        'label' => __('Payment description:', 'woocommerce-fps'),
        'value' => $this->description
      );
      $payment_method_pos = array_search('payment_method', array_keys($total_rows));
      if (!$reference_no) {
        $order->read_meta_data(true);
        $reference_no = $order->get_meta('_fps_reference_no');
      }
      $reference_no = !$reference_no ? __('Not submitted', 'woocommerce-fps') : $reference_no;
      $fps_reference_display = array(
        'label' => __('FPS reference number:', 'woocommerce-fps'),
        'value' => $reference_no
      );
      $total_rows = array_slice($total_rows, 0, $payment_method_pos+1, true) + array('payment_description' => $payment_description) + array('fps_reference_no' => $fps_reference_display) + array_slice($total_rows, $payment_method_pos+1, count($total_rows) - 1, true);
    }
    
    return $total_rows;
  }

  /**
   * @since      1.0.4
   * Add FPS reference number submit form to order detail page
   *
   * @param array $total_rows
   * @param WC_Order $order
   * @param mixed $tax_display
   * @return array
   */
  public function order_detail_reference_number_input($order) {
    $payment_method = $order->get_payment_method();
    $order_status = $order->get_status();
    if ($payment_method == $this->id) {
      $reference_no = $order->get_meta('_fps_reference_no');
      if (!$reference_no) {
        echo do_shortcode('[fps_reference_number_input order_id=' . $order->get_id() . ']');
      }
    }
  }
}