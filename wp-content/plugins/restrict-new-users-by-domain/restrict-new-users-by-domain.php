<?php
/**
 * Plugin Name: Restrict New Users by Domain
 * Description: Restrict allowed email domains for new user registrations.
 * Plugin URI: https://exportsmedia.com/restrict-new-users-by-domain/
 * Author: Michael Markoski
 * Author URI: https://exportsmedia.com
 * Version: 1.0.2
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 *
 * Restrict New Users by Domain is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Restrict New Users by Domain is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Restrict New Users by Domain. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

defined( 'ABSPATH' ) or exit;

define( 'RNUD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, 'rnud_activate' );

function rnud_activate($network_wide) {

    if ( is_multisite() || $network_wide) {
    	deactivate_plugins( basename( __FILE__ ) );
    	wp_die('This plugin can\'t be activated on Multisite');
    }

}

class Restrict_New_Users_By_Domain {

	/**
	 * Initialization function
	 */
	function __construct() {

		require_once( RNUD_PLUGIN_DIR . 'class-admin.php' );
		require_once( RNUD_PLUGIN_DIR . 'class-email-filter.php' );

		// Initialize the admin class
		$Restrict_New_Users_By_Domain_Admin = new Restrict_New_Users_By_Domain_Admin;

		// Initialize the email filter class
		$Restrict_New_Users_By_Domain_Filter = new Restrict_New_Users_By_Domain_Filter;

	}
	
}

$Restrict_New_Users_By_Domain = new Restrict_New_Users_By_Domain;