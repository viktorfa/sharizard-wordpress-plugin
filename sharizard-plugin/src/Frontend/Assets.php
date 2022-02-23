<?php

namespace SharizardWordpress\Frontend;

use SharizardWordpress\Common\Assets as Common_Assets;
use SharizardWordpress\PluginData as PluginData;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Assets::class ) ) {
	/**
	 * Enqueues the public-facing assets.
	 */
	class Assets {

		/**
		 * @var Common_Assets
		 */
		var $common_assets;

		public function __construct() {
			$this->common_assets = new Common_Assets();
		}

		/**
		 * Register and enqueue the stylesheets for the public-facing side of the site.
		 *
		 * Must register before we enqueue!
		 */
		public function enqueue_styles(): void {
		}

		/**
		 * Register and enqueue the scripts for the public-facing side of the site.
		 *
		 * Must register before we enqueue!
		 */
		public function enqueue_scripts(): void {
		}
	}
}
