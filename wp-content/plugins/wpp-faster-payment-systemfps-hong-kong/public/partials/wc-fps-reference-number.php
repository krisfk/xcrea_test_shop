<?php
/**
 * FPS transaction reference number display
 *
 * @since      1.0.0
 * @package    Woocommerce_Fps
 * @subpackage Woocommerce_Fps/public/partials
 */
?>
<div id="fps_reference_number_submission" style="padding-top:16px;padding-bottom:16px;">
<form name="form_fps_reference_number" method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
<?php wp_nonce_field('update_fps_reference_number'); ?>
<div><?php _e('Submit your FPS reference number:', 'woocommerce-fps') ?><input name="fps_reference_number" type="text" value="" maxlength="30" style="width:250px;"></div>
<input type="submit" value="<?php _e('Submit','woocommerce-fps') ?>">
</form>
</div>