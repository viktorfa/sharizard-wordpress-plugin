<?php

namespace SharizardWordpress\Core;

use SharizardWordpress\Common\Settings\Main as Common_Settings;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Activator::class ) ) {
	/**
	 * Fired during plugin activation
	 *
	 * This class defines all code necessary to run during the plugin's activation.
	 **/
	class Activator {

		/**
		 * Short Description.
		 *
		 * Long Description.
		 */
		public static function activate() {
			$settings = new Common_Settings();
			add_option($settings->get_prefixed_option_key( 'text_color' ), "#333");
			add_option($settings->get_prefixed_option_key( 'background_color' ), "#fff");
		}
	}
}
