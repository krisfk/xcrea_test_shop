<?php
/**
 * Define helper functions
 * 
 * @since      1.0.0
 * @package    Woocommerce_Fps
 */

/**
 * @since      1.0.0
 * load html template into variable
 */
function wc_fps_load_template($_template_file, $params=array(), $echo=false) {
  if (!$echo) {
    ob_start();
    require_once( $_template_file );
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
  }
  require( $_template_file );
}