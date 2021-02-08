<?php
/**
 * Setting page for fps plugin
 */
 
if (!defined('ABSPATH')) exit;
load_plugin_textdomain( 'vcanfly-fps', false, dirname(plugin_basename(__FILE__ )).'/../language/' );


/**
 * Store all of the options in one array.
 */
function vcanfps_default_options() {
  return array(
	'fpsplugin_apiendpoint' => '',
	'fpsplugin_userid' => '',
	'fpsplugin_apikey' =>''
  );
}
 
function vcanfps_get_options() {
  return get_option('fpsplugin_options', vcanfps_default_options());
}
 
/**
 * Register option array and setup sanitize options
 */
function vcanfps_admin_init() {
  register_setting(
    'fpsplugin_options_group',
    'fpsplugin_options',
    array(
      'type' => 'array',
      'default' => vcanfps_default_options(),
      'sanitize_callback' => 'vcanfps_sanitize_options'
    )
  );
}
 
add_action('admin_init', 'vcanfps_admin_init');
 
/**
 * The function for sanitizing options entered by the user.
 */

function vcanfps_sanitize_options($input) {
  $output = vcanfps_get_options();
  $error = false;
 
  if(!isset($input['fpsplugin_apiendpoint'])||empty($input['fpsplugin_apiendpoint']))
  {
	add_settings_error('fpsplugin_options','fpsplugin_apiendpoint',__('API endpoint not valid.', 'vcanfly-fps'));
  }
  if(!isset($input['fpsplugin_userid'])||empty($input['fpsplugin_userid']))
  {
	add_settings_error('fpsplugin_options','fpsplugin_userid',__('User ID not valid.', 'vcanfly-fps'));
  }
	if(!isset($input['fpsplugin_apikey'])||empty($input['fpsplugin_apikey']))
  {
	add_settings_error('fpsplugin_options','fpsplugin_apikey',__('API Key not valid.', 'vcanfly-fps'));
  }
 
  // Only update the existing data in the absence of errors.
  if (!count(get_settings_errors('fpsplugin_options'))) {
    $output['fpsplugin_apiendpoint'] = $input['fpsplugin_apiendpoint'];
    $output['fpsplugin_userid'] = $input['fpsplugin_userid'];
	  $output['fpsplugin_apikey'] = $input['fpsplugin_apikey'];
  }
 
  return $output;
}
 
// ---------------------------------------------------------------------------
// Define the options page.
// ---------------------------------------------------------------------------
 
/**
 * This function emits the HTML for the options page.
 */
function vcanfps_options_page_html() {
  // Don't proceed if the user lacks permissions.
  if (!current_user_can('manage_options')) {
    return;
  }
 
  $options = vcanfps_get_options();
 
  // First show the error or update messages at the head of the page.
  settings_errors('fpsplugin_messages');
 
  ?>
  <div class="wrap">
    <h1><?= esc_html(get_admin_page_title()); ?></h1>
    <p>For further details, please contact <a href="mailto:vcanfly@vcanfintech.com">vcanfly@vcanfintech.com</a></p>
    <form action="options.php" method="post">
      <?php settings_fields('fpsplugin_options_group'); ?>
 
      <table class="form-table">
        <tr>
          <th scope="row">
            <label for="fpsplugin_options[fpsplugin_apiendpoint]">
              <?php esc_html_e('API Endpoint', 'vcanfly-fps'); ?>:
            </label>
          </th>
          <td>
            <input
              id="fpsplugin_options[fpsplugin_apiendpoint]"
              name="fpsplugin_options[fpsplugin_apiendpoint]"
              value="<?php echo esc_attr($options['fpsplugin_apiendpoint']); ?>"
            />
          </td>
        </tr>
		  
		  <tr>
          <th scope="row">
            <label for="fpsplugin_options[fpsplugin_apikey]">
              <?php esc_html_e('API Key', 'vcanfly-fps'); ?>:
            </label>
          </th>
          <td>
            <input
              id="fpsplugin_options[fpsplugin_apikey]"
              name="fpsplugin_options[fpsplugin_apikey]"
              value="<?php echo esc_attr($options['fpsplugin_apikey']); ?>"
            />
          </td>
        </tr>
 
        <tr>
          <th scope="row">
            <label for="fpsplugin_options[fpsplugin_userid]">
              <?php esc_html_e('API User ID', 'vcanfly-fps'); ?>:
            </label>
          </th>
          <td>
            <input
              id="fpsplugin_options[fpsplugin_userid]"
              name="fpsplugin_options[fpsplugin_userid]"
              value="<?php echo esc_attr($options['fpsplugin_userid']); ?>"
            />
            <p class="description">
              <?php esc_html_e('32 Digits (xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx)', 'vcanfly-fps'); ?>
            </p>
          </td>
        </tr>
      </table>
      <?php echo 'Frontend options (Title, Description, etc) please update <a href="' . esc_url( admin_url( '/admin.php?page=wc-settings&tab=checkout&section=vcanfly_gateway' ) ) . '">' . __( 'HERE', 'vcanfly-fps' ) . '</a>' ?>

      <?php submit_button(__('Save Settings', 'vcanfly-fps')); ?>

    </form>
  </div>
  <?php
}
 
// ---------------------------------------------------------------------------
// Add the options page link to the admin menu.
// ---------------------------------------------------------------------------
 
/**
 * Add a link to the settings page into the settings submenu.
 */
function vcanfps_options_page() {
  add_options_page(
    __('FPS Plugin Options', 'vcanfly-fps'),
    __('FPS Plugin', 'vcanfly-fps'),
    'manage_options',
    'vcanfps',
    'vcanfps_options_page_html'
  );
}
 
add_action('admin_menu', 'vcanfps_options_page');
