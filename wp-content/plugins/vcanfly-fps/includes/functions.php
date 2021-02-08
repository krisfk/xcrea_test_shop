<?php
if ( ! defined( 'ABSPATH' ) ) exit; 

function vcanfps_buildRefId($order){
    return $order->get_id().':#'.$order->get_order_number();
}

function vcanfps_extractOrderId($s){
    if(strpos($s,':'))
    {
        return explode(':',$s)[0];
    }
}

function vcanfps_extractOrderNumber($s){
    if(strpos($s,':'))
    {
        return explode(':',$s)[1];
    }
}