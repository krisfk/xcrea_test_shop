<?php
/**
 * FPS transaction reference number display
 *
 * @since      1.0.0
 * @package    Woocommerce_Fps
 * @subpackage Woocommerce_Fps/public/partials
 */

global $pagenow;
$params['reference_no'] = isset($params['reference_no']) ? $params['reference_no']: '';

if ($pagenow == 'edit.php') { ?>
  <span class="wc-fps-reference-no">(FPS reference no.: <?php echo $params['reference_no']; ?>)</span>
<?php } elseif ($pagenow == 'post.php') { ?>
  <p class="form-field form-field-wide wc-fps-reference-no">FPS reference no.: <?php echo $params['reference_no']; ?></p>
<?php }
