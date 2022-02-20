<?php

namespace SharizardWordpress\Customizer;

use WP_Customize_Manager;
use SharizardWordpress\Common\Common;
use SharizardWordpress\Common\Settings\Choices;
use SharizardWordpress\Common\Settings\Customizer as Settings;
use SharizardWordpress\Customizer\Controls\SortableCheckboxes\Control;
use SharizardWordpress\PluginData as PluginData;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( Customizer::class ) ) {
	/**
	 * Setup the WordPress Customizer functionality.
	 *
	 * Reusable utility functions (e.g. get all public post types), master/canonical arrays of data, getters, and
	 * sanitize-type functions should be in Common.
	 * Option lists for Customizer controls should be in this class, and it is advisable to prefix such functions
	 * with `get_choices...()`.
	 */
	class Customizer {

		/**
		 * Get the Common instance.
		 *
		 * @var Common
		 */
		private $common;

		/**
		 * Get the Choices instance from Common.
		 *
		 * @var Choices
		 */
		private $choices;

		/**
		 * Get the Settings instance from Common.
		 *
		 * @var Settings
		 */
		private $settings;

		/**
		 * Initialize the class and set its properties.
		 */
		public function __construct() {
			$this->common   = new Common();
			$this->choices  = new Choices();
			$this->settings = new Settings();
		}

		/**
		 * Add plugin options to Customizer.
		 *
		 * @link https://developer.wordpress.org/themes/customize-api/
		 *
		 * @param WP_Customize_Manager $wp_customize
		 */
		public function customizer_options( WP_Customize_Manager $wp_customize ): void {
			/**
			 * Add edit shortcut links (pencil icon wherever output when viewing in Customizer Preview).
			 *
			 * @link https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#selective-refresh-fast-accurate-updates
			 */
			$wp_customize->selective_refresh->add_partial(
				$this->customizer_edit_shortcut_setting(),
				[
					'selector'            => '.' . $this->common->get_wrapper_class(),
					'container_inclusive' => true,
					'render_callback'     => function () {
						// purposefully not set because the setting is dynamic
						// will just refresh it all, which is what we want anyway
					},
				]
			);

			// Add our custom panel, within which all our sections should be added.
			$wp_customize->add_panel(
				$this->settings->customizer_panel_id(),
				[
					'title'       => PluginData::get_plugin_display_name(),
					'description' => esc_html__( 'Plugin options and settings', 'sharizard-wordpress' ) . $this->settings->get_link_to_customizer_panel(),
				]
			);

			// Add our Customizer Section(s) within our custom panel.
			$wp_customize->add_section(
				$this->get_section_id( 'example' ),
				[
					'title'       => esc_html__( 'Example Section', 'sharizard-wordpress' ),
					'description' => esc_html__( 'Example Section description.', 'sharizard-wordpress' ),
					'panel'       => $this->settings->customizer_panel_id(),
				]
			);

			// Add setting(s) to our section(s)
			$this->add_setting_social_networks( $wp_customize, 'example', 'social_networks' );
			$this->add_setting_post_types( $wp_customize, 'example', 'post_types' );
		}

		/**
		 * The Customizer setting that the edit shortcut (pencil icon) should take user to.
		 *
		 * @return string
		 */
		public function customizer_edit_shortcut_setting(): string {
			/**
			 * @todo: Example setting: Sortable checkbox list of social networks. Must choose a setting to go to, not a section or panel.
			 */
			$setting = PluginData::plugin_text_domain_underscores() . '[social_networks]';

			return apply_filters( PluginData::plugin_text_domain_underscores() . '_' . __FUNCTION__, $setting );
		}

		/**
		 * Get the full ID of a Customizer section, given its unique slug.
		 *
		 * This keeps our section names namespaced but easily accessible within code via a single string.
		 *
		 * @param string $slug
		 *
		 * @return string
		 */
		private function get_section_id( string $slug = '' ): string {
			$slug = sanitize_key( $slug );

			if ( empty( $slug ) ) {
				return '';
			} else {
				return PluginData::plugin_text_domain_underscores() . '_section_' . $slug;
			}
		}

		/**
		 * @todo: Example: Add setting for Social Networks. Notice this one has multiple sortable checkboxes.
		 *
		 * @param WP_Customize_Manager $wp_customize
		 * @param string               $section_slug The section this setting should be added to.
		 * @param string               $setting_slug This setting's unique slug.
		 */
		private function add_setting_social_networks( WP_Customize_Manager $wp_customize, string $section_slug, string $setting_slug ): void {
			$wp_customize->add_setting(
				$this->get_setting_id( $setting_slug ),
				[
					'type'              => 'option',
					'default'           => json_encode( $this->choices->get_choices_social_networks() ), // Select all by default
					'sanitize_callback' => [ $this->settings, 'sanitize_social_networks' ],
				]
			);

			$wp_customize->add_control(
				new Control(
					$wp_customize,
					PluginData::plugin_text_domain_underscores() . '_' . $setting_slug . '_control',
					[
						'label'       => esc_html__( 'Social Network(s)', 'sharizard-wordpress' ),
						'description' => esc_html__( 'Checked ones will output; unchecked ones will not. Drag and drop to set your preferred display order.', 'sharizard-wordpress' ),
						'section'     => $this->get_section_id( $section_slug ),
						'settings'    => $this->get_setting_id( $setting_slug ),
						'choices'     => $this->choices->get_choices_social_networks(),
					]
				)
			);
		}

		/**
		 * Get the full ID of a Customizer setting, given its unique slug.
		 *
		 * This keeps our setting names namespaced but easily accessible within code via a single string.
		 *
		 * @param string $slug
		 *
		 * @return string
		 */
		private function get_setting_id( string $slug = '' ): string {
			$slug = sanitize_key( $slug );

			if ( empty( $slug ) ) {
				return '';
			} else {
				return PluginData::plugin_text_domain_underscores() . '[' . $slug . ']';
			}
		}

		/**
		 * @todo: Example: Add setting for Post Types. Notice this one has multiple (but not sortable) Checkboxes, due to 'input_attrs'.
		 *
		 * @param WP_Customize_Manager $wp_customize
		 * @param string               $section_slug The section this setting should be added to.
		 * @param string               $setting_slug This setting's unique slug.
		 */
		private function add_setting_post_types( WP_Customize_Manager $wp_customize, string $section_slug, string $setting_slug ): void {
			$wp_customize->add_setting(
				$this->get_setting_id( $setting_slug ), [
					'type'              => 'option',
					'default'           => '',
					'sanitize_callback' => [ $this->settings, 'sanitize_post_types' ],
				]
			);

			$wp_customize->add_control(
				new Control(
					$wp_customize,
					PluginData::plugin_text_domain_underscores() . '_' . $setting_slug . '_control',
					[
						'label'       => esc_html__( 'Post Type(s)', 'sharizard-wordpress' ),
						'description' => esc_html__( 'Which Post Types should be enabled?', 'sharizard-wordpress' ),
						'section'     => $this->get_section_id( $section_slug ),
						'settings'    => $this->get_setting_id( $setting_slug ),
						'choices'     => $this->choices->get_choices_post_types(),
						'input_attrs' => [
							'data-disable_sortable' => 'true',
						],
					]
				)
			);
		}
	}
}
