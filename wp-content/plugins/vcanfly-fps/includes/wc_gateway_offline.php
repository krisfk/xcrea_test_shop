<?php

function wc_vcanfly_add_to_gateways( $gateways ) {
	$gateways[] = 'WC_Gateway_Vcanfly';
	return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'wc_vcanfly_add_to_gateways' );

add_action( 'plugins_loaded', 'wc_vcanfly_gateway_init', 11 );

function wc_vcanfly_gateway_init() {

	class WC_Gateway_Vcanfly extends WC_Payment_Gateway {

		/**
		 * Constructor for the gateway.
		 */
		public function __construct() {
	  
			$this->id                 = 'vcanfly_gateway';
            $this->icon               =  plugin_dir_url( dirname( __DIR__ ) ) . 'vcanfly-fps/includes/fps-icon-small.png';
			$this->has_fields         = false;
			$this->method_title       = __( 'FPS Payment via Vcanfly', 'vcanfly-fps' );
			$this->method_description = __( 'Display FPS QR code on order comfirmation page. You need an Vcanfly account to use this gateway.', 'vcanfly-fps' );
		  
			// Load the settings.
			$this->init_form_fields();
			$this->init_settings();
		  
			// Define user set variables
			$this->title        = $this->get_option( 'title' );
			$this->description  = $this->get_option( 'description' );
			$this->instructions = $this->get_option( 'instructions', $this->description );
		  
			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
		  
			// Customer Emails
			add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
		}
	
	
		/**
		 * Initialize Gateway Settings Form Fields
		 */
		public function init_form_fields() {
	  
			$this->form_fields = apply_filters( 'wc_offline_form_fields', array(
		  
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'vcanfly-fps' ),
					'type'    => 'checkbox',
					'label'   => 'Enable',
					'default' => 'yes'
				),
				
				'title' => array(
					'title'       => __( 'Title', 'vcanfly-fps' ),
					'type'        => 'text',
					'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'vcanfly-fps' ),
					'default'     => 'FPS Payment via Vcanfly',
					'desc_tip'    => true,
				),
				
				'description' => array(
					'title'       => __( 'Description', 'digitavcanfly-fps' ),
					'type'        => 'textarea',
					'description' => __( 'Payment method description that the customer will see on your checkout.', 'vcanfly-fps' ),
					'default'     => 'Scan FPS payment code and pay with your mobile phone.',
					'desc_tip'    => true,
				),
				
				'instructions' => array(
					'title'       => __( 'Instructions', 'vcanfly-fps' ),
					'type'        => 'textarea',
					'description' => __( 'Instructions that will be added to the thank you page and emails.', 'vcanfly-fps' ),
					'default'     => 'Scan FPS payment code and pay with your mobile phone.',
					'desc_tip'    => true,
				),
			) );
		}
	
	
		/**
		 * Output for the order received page.
		 */
		public function thankyou_page() {
			if ( $this->instructions ) {
				echo wpautop( wptexturize( $this->instructions ) );
			}
		}
	
	
		/**
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
		 * Process the payment and return the result
		 *
		 * @param int $order_id
		 * @return array
		 */
		public function process_payment( $order_id ) {
	
			$order = wc_get_order( $order_id );
			
			// Mark as on-hold (we're awaiting the payment)
			$order->update_status( 'on-hold', __( 'Awaiting payment', 'vcanfly-fps' ) );
			
			// Reduce stock levels
			$order->reduce_order_stock();
			
			// Remove cart
			WC()->cart->empty_cart();
			
			// Return thankyou redirect
			return array(
				'result' 	=> 'success',
				'redirect'	=> $this->get_return_url( $order )
			);
		}
	
  } // end class
}