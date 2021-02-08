<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 

//callback url at https://{{domain}}/wc-api/fpspaymentcallback/
add_action( 'woocommerce_api_fpspaymentcallback', 'vcanfps_callback_handler' );

function vcanfps_callback_handler() {
    $raw_post = file_get_contents( 'php://input' );
    $decoded  = json_decode( $raw_post );
    if(isset($decoded->refId) && isset($decoded->qrCodeId)){

        $order_id = vcanfps_extractOrderId($decoded->refId);
        $order = wc_get_order( $order_id );
        
        if(isset($order)){
            // Change order status
            $order->update_status('processing',vcanfps_getTransactionMessage($decoded));
            
            // Fill FPS reference no
            update_post_meta($order_id,'_fps_reference_no',$decoded->qrCodeId);
        }        
    }
        
    return http_response_code(200);
}

function vcanfps_getTransactionMessage($decoded){
    return "qrCodeId: ".$decoded->qrCodeId
            ."|amount: ".$decoded->amount
            ."|refId:".$decoded->refId
            ."|payer:".$decoded->payer
            ."|paymentCurrency:".$decoded->paymentCurrency
            ."|paymentAmount:".$decoded->paymentAmount
            ."|paymentRecieveDate:".$decoded->paymentRecieveDate;
}