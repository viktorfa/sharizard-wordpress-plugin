<?php

declare( strict_types=1 );

namespace SharizardWordpress\Admin\Settings;

use SharizardWordpress\Common\Settings\Choices;
use SharizardWordpress\Common\Settings\Customizer;
use SharizardWordpress\PluginData as PluginData;
use SharizardWordpress\Common\Common as Common;
use SharizardWordpress\Common\Settings\Main as Common_Settings;
use WP_Screen;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Main::class ) ) {
	/**
	 * The admin-specific settings.
	 */
	class Main {

		/**
		 * The Common instance.
		 *
		 * @var Common
		 */
		public $common;

		/**
		 * Get the Settings instance from Common.
		 *
		 * @var Common_Settings
		 */
		private $settings;

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->common = new Common();
			$this->settings = new Common_Settings();
		}

		/**
		 * Add Settings link within Plugins List page.
		 *
		 * @param array $links
		 *
		 * @return array
		 */
		public function customize_action_links( array $links ): array {
			$link_to_settings_page = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $this->settings->get_main_settings_page_url() ),
				$this->settings->get_settings_word()
			);

			$custom_action_links = [
				$link_to_settings_page,
			];

			return array_merge( $custom_action_links, $links );
		}

		/**
		 * Add the Settings page to the wp-admin menu.
		 */
		public function add_plugin_admin_menu(): void {
			$hook_suffix = add_options_page(
				PluginData::get_plugin_display_name(),
				PluginData::get_plugin_display_name(),
				$this->common->required_capability(),
				$this->settings->get_settings_page_slug(),
				[ $this, 'settings_page' ]
			);

			add_settings_section(
				"default", // ID
				'Settings', // Title
				array( $this, 'print_section_info' ), // Callback
				$this->settings->get_settings_page_slug() // Page
			);  
	
			add_settings_field(
				'text_color', 
				'Text color', 
				array( $this, 'text_color_callback' ), 
				$this->settings->get_settings_page_slug(), // Page
				"default"
			);  
			add_settings_field(
				'background_color', 
				'Background color', 
				array( $this, 'background_color_callback' ), 
				$this->settings->get_settings_page_slug(), // Page
				"default"
			); 

			// Empty if insufficient permissions. We don't want our data put to page source in that case (but the action would not fire successfully anyway).
			if ( ! empty( $hook_suffix ) ) {
				add_action( "admin_print_scripts-{$hook_suffix}", [ $this, 'enqueue_settings_page_assets' ] );
			}
		}

		public function register_settings() {
			register_setting(
				$this->settings->get_option_prefix(),
				$this->settings->get_prefixed_option_key( 'text_color' ),
				/*[
					'type'              => 'string',
					'default'           => "#333",
					'sanitize_callback' => 'rest_sanitize_string',
					'show_in_rest'      => true,
				]*/
			);
			register_setting(
				$this->settings->get_option_prefix(),
				$this->settings->get_prefixed_option_key( 'background_color' ),
				/*[
					'type'              => 'string',
					'default'           => "#fff",
					'sanitize_callback' => 'rest_sanitize_string',
					'show_in_rest'      => true,
				]*/
			);
		}

		public function render_settings_form() {
			?>
				<div class="wrap">
					<h1>Simple Social Media Preview</h1>
					<form method="POST" action="options.php">
						<?php
							// This prints out all hidden setting fields
							settings_fields( $this->settings->get_option_prefix(), );
							do_settings_sections( $this->settings->get_settings_page_slug() );
							submit_button();
						?>
					</form>
				</div>
			<?php
		}

		/** 
		 * Print the Section text
		 */
		public function print_section_info()
		{
			print 'Any CSS color is valid';
		}

		public function text_color_callback()
		{
			printf(
				'<input type="text" id="%s" name="%s" value="%s" />',
				$this->settings->get_prefixed_option_key( 'text_color' ),
				$this->settings->get_prefixed_option_key( 'text_color' ),
				esc_attr( get_option($this->settings->get_prefixed_option_key( 'text_color' )))
			);
		}

		public function background_color_callback()
		{
			printf(
				'<input type="text" id="%s" name="%s" value="%s" />',
				$this->settings->get_prefixed_option_key( 'background_color' ),
				$this->settings->get_prefixed_option_key( 'background_color' ),
				esc_attr( get_option($this->settings->get_prefixed_option_key( 'background_color' )))
			);
		}

		/**
		 * Register the JavaScript for the admin Settings Page area.
		 */
		public function enqueue_settings_page_assets() {
		}

		/**
		 * Get the settings page ID, which is added as a body.class and is the $hook_suffix passed to 'admin_enqueue_scripts'.
		 *
		 * If you add your page as a submenu to anything other than "Settings", such as to be a top-level menu or
		 * submenu of a Custom Post Type, you'll need to edit the hard-coded part of this function.
		 *
		 * @see \get_plugin_page_hookname()
		 *
		 * @return string
		 */
		public function get_settings_page_id(): string {
			return 'settings_page_' . $this->settings->get_settings_page_slug();
		}

		/**
		 * Detect if we are on our Settings Page.
		 *
		 * @return bool
		 */
		public function is_our_settings_page(): bool {
			$current_screen = get_current_screen();

			if (
				$current_screen instanceof WP_Screen
				&& $this->get_settings_page_id() === $current_screen->base
			) {
				return true;
			}

			return false;
		}

		/**
		 * Outputs HTML for the plugin's Settings page.
		 */
		public function settings_page(): void {
			if ( ! current_user_can( $this->common->required_capability() ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'sharizard-wordpress' ) );
			}

			printf(
				'<div class="wrap" id="%s">%s</div>',
				PluginData::plugin_text_domain(), $this->render_settings_form()
			);
		}

	}
}
