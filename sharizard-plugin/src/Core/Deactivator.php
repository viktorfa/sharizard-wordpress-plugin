<?php

namespace SharizardWordpress\Core;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Deactivator::class ) ) {
	/**
	 * Fired during plugin deactivation
	 *
	 * This class defines all code necessary to run during the plugin's deactivation.
	 **/
	class Deactivator {

		/**
		 * Short Description.
		 *
		 * Long Description.
		 */
		public static function deactivate() {
		}
	}
}
