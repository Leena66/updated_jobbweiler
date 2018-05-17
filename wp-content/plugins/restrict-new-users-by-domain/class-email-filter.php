<?php

defined( 'ABSPATH' ) or exit;

class Restrict_New_Users_By_Domain_Filter {

	/**
	 * Initialization function
	 */
	function __construct() {

		add_action( 'register_post', array( $this, 'rnud_filter_email' ), 10, 3 );

	}

	/*
	 * Validates the email domain against the whitelist or blacklist
	 */
	function rnud_filter_email( $user_login, $user_email, $errors ) {

		$options = get_option('rnud_options');

		if(!empty($options['whitelist'])) {

			// Turn the whitelist into an array
			$whitelist = preg_split('/\r\n|[\r\n]/', $options['whitelist']);

			$whitelist = array_map('strtolower', $whitelist);

			// Split the email address at the @ symbol
		    $email_parts = explode( '@', $user_email );
		    
		    // Pop off everything after the @ symbol, force lowercase to ignore case
		    $domain = strtolower(trim(array_pop( $email_parts )));

		    if (!in_array($domain, $whitelist)) {

		        $this->rnud_domain_error($errors);

		    }

		} elseif(empty($options['whitelist']) && !empty($options['blacklist'])) {

			// Turn the blacklist into an array
			$blacklist = preg_split('/\r\n|[\r\n]/', $options['blacklist']);

			$blacklist = array_map('strtolower', $blacklist);

			// Split the email address at the @ symbol
		    $email_parts = explode( '@', $user_email );
		    
		    // Pop off everything after the @ symbol, force lowercase to ignore case
		    $domain = strtolower(trim(array_pop( $email_parts )));

		    if (in_array($domain, $blacklist)) {

		        $this->rnud_domain_error($errors);

		    }

		}

	}

	/*
	 * Return error message for restricted domain
	 */
	function rnud_domain_error($errors) {

		$options = get_option('rnud_options');

		if($options['error_message']) {

			$error_message = esc_attr($options['error_message']);

		} else {

			$error_message = 'This email domain is not allowed';

		}

		return $errors->add( 'bad_email_domain', '<strong>ERROR</strong>: ' . $error_message . '.' );

	}
	
}