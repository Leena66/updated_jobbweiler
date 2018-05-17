<?php

defined( 'ABSPATH' ) or exit;

class Restrict_New_Users_By_Domain_Admin {

	/**
	 * Initialization function
	 */
	function __construct() {

		add_action('admin_menu', array( $this, 'rnud_register_admin_page' ));

		add_action('admin_init', array( $this, 'rnud_init' ));

	}

	/*
	 * Register the admin settings page
	 */
	function rnud_register_admin_page() {

		add_submenu_page(
        'options-general.php',
        'Restrict New Users by Domain',
        'Restrict New Users by Domain',
        'manage_options',
        'rnud-settings',
        array( $this, 'rnud_admin_page_callback' ));

	}

	/*
	 * Register our settings. Add the settings section, and settings fields
	 */
	function rnud_init(){

		register_setting('rnud_options', 'rnud_options', array( $this, 'rnud_options_validate' ));

		add_settings_section('whitelist_section', 'Whitelist Settings', array( $this, 'rnud_whitelist_section' ), __FILE__);

		add_settings_field('whitelist', 'Whitelist Domains', array( $this, 'rnud_setting_whitelist_textarea' ), __FILE__, 'whitelist_section');

		add_settings_section('blacklist_section', 'Blacklist Settings', array( $this, 'rnud_blacklist_section' ), __FILE__);

		add_settings_field('blacklist', 'Blacklist Domains', array( $this, 'rnud_setting_blacklist_textarea' ), __FILE__, 'blacklist_section');

		add_settings_field('error_message', 'Custom Error Message', array( $this, 'rnud_setting_error_message_input' ), __FILE__, 'blacklist_section');

	}

	/*
	 * Output for whitelist section in form
	 */
	function  rnud_whitelist_section() {

		echo '<p>If you <strong>WHITELIST</strong> a domain, then only email addresses <strong>CONTAINING</strong> that domain will be allowed.</p><strong>Enter one (1) domain per line.</strong>';

		$options = get_option('rnud_options');

		if($options['whitelist']) {

			echo '<p><strong>NOTICE: Whitelist is in use. Blacklist will be ignored.</strong></p>';

		}

	}

	/*
	 * Output for blacklist section in form
	 */
	function  rnud_blacklist_section() {

		echo '<p>If you <strong>BLACKLIST</strong> a domain, then all email addresses <strong>NOT CONTAINING</strong>  that domain will be allowed.</p><strong>Enter one (1) domain per line.</strong>';

		$options = get_option('rnud_options');

		if(!$options['whitelist'] && $options['blacklist']) {

			echo '<p><strong>NOTICE: Blacklist is in use. Whitelist will be ignored.</strong></p>';

		}

	}

	/*
	 * Output for whitelist textarea in form
	 */
	function rnud_setting_whitelist_textarea() {

		$options = get_option('rnud_options');

		echo "<textarea id='plugin_textarea_string' name='rnud_options[whitelist]' rows='7' cols='50' type='textarea'>" . esc_textarea($options['whitelist']) . "</textarea>";

	}

	/*
	 * Output for blacklist textarea in form
	 */
	function rnud_setting_blacklist_textarea() {

		$options = get_option('rnud_options');

		echo "<textarea id='plugin_textarea_string' name='rnud_options[blacklist]' rows='7' cols='50' type='textarea'>" . esc_textarea($options['blacklist']) . "</textarea>";

	}

	/*
	 * Output for error message input in form
	 */
	function rnud_setting_error_message_input() {

		$options = get_option('rnud_options');

		echo "<input id='plugin_text_string' name='rnud_options[error_message]' size='40' type='text' value='" . esc_attr($options['error_message']) . "' />";

	}

	/*
	 * Output the content for the admin settings page
	 */
	function rnud_admin_page_callback() {

	?>

		<div class="wrap">

			<h2>Restrict New Users by Domain Settings</h2>

			<h3>Here you can whitelist <strong>OR</strong> blacklist domains. Only the whitelist will be used if you have domains in both lists.</h3>

			<p>Enter the domain names you would like to filter.</p>

			<form action="options.php" method="post">

				<?php if ( function_exists('wp_nonce_field') ) 
						wp_nonce_field('plugin-name-action_' . "yep"); ?>

				<?php settings_fields('rnud_options'); ?>

				<?php do_settings_sections(__FILE__); ?>

				<p class="submit">

					<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />

				</p>

			</form>

		</div>

	<?php

	}

	/*
	 * Sanitize user data
	 */
	function rnud_options_validate($input) {

		// sanitize and validate data
		$input['whitelist'] =  $this->rnud_filter_domain_urls($input['whitelist']);
		$input['blacklist'] =  $this->rnud_filter_domain_urls($input['blacklist']);
		$input['error_message'] =  esc_attr($input['error_message']);

		return $input; // return validated input

	}

	function rnud_filter_domain_urls($input) {

		if($input) {

			$domains = '';
			$domain_list = preg_split('/\r\n|[\r\n]/', wp_filter_nohtml_kses($input));

			foreach($domain_list as $domain) {

				// remove slashes and commas
				$domain = preg_replace('/[ ,]+/', '', $domain);

				// If scheme not included, prepend it
				if (!preg_match('#^http(s)?://#', $domain)) {

				    $domain = 'http://' . $domain;

				}

				$urlParts = parse_url($domain);

				if($urlParts['host']) {

					// remove www and add back to output
					$domains .= preg_replace('/^www\./', '', $urlParts['host']) . chr(13) . chr(10);

				}

			}

			return $domains;

		}

	}
	
}