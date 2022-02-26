<?php

namespace SharizardWordpress\Frontend;
use SharizardWordpress\Common\Settings\Main as Common_Settings;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all actions and filters for the plugin
 */

if ( ! class_exists( MetaTags::class ) ) {
	/**
	 * Register all actions and filters for the plugin.
	 *
	 * Maintain a list of all hooks that are registered throughout the plugin, and register them with the WordPress API.
	 * Call the run function to execute the list of actions and filters.
	 */
	class MetaTags {

		/**
		 * Get the Settings instance from Common.
		 *
		 * @var Common_Settings
		 */
		private $settings;

		/**
		 * The array of actions registered with WordPress.
		 *
		 * @var      array $actions The actions registered with WordPress to fire when the plugin loads.
		 */
		protected $actions;

		/**
		 * The array of filters registered with WordPress.
		 *
		 * @var      array $filters The filters registered with WordPress to fire when the plugin loads.
		 */
		protected $filters;

		/**
		 * Initialize the collections used to maintain the actions and filters.
		 */
		public function __construct() {
			$this->actions = [
				["hook" => "wp_head", "component" => $this, "callback" => "add_meta_tags", "priority" => 10, "accepted_args" => 0]
			];
			$this->filters = [];
			$this->settings = new Common_Settings();
		}

		/**
		 * Add a new action to the collection to be registered with WordPress.
		 *
		 * @param string $hook          The name of the WordPress action that is being registered.
		 * @param object $component     A reference to the instance of the object on which the action is defined.
		 * @param string $callback      The name of the function definition on the $component.
		 * @param int    $priority      Optional. he priority at which the function should be fired. Default is 10.
		 * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
		 */
		public function add_action( string $hook, $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
			$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
		}

		/**
		 * A utility function that is used to register the actions and hooks into a single collection.
		 *
		 * @param array  $hooks         The collection of hooks that is being registered (that is, actions or filters).
		 * @param string $hook          The name of the WordPress filter that is being registered.
		 * @param object $component     A reference to the instance of the object on which the filter is defined.
		 * @param string $callback      The name of the function definition on the $component.
		 * @param int    $priority      The priority at which the function should be fired.
		 * @param int    $accepted_args The number of arguments that should be passed to the $callback.
		 *
		 * @return   array                                  The collection of actions and filters registered with WordPress.
		 */
		private function add( array $hooks, string $hook, $component, string $callback, int $priority, int $accepted_args ): array {
			$hooks[] = [
				'hook'          => $hook,
				'component'     => $component,
				'callback'      => $callback,
				'priority'      => $priority,
				'accepted_args' => $accepted_args,
			];

			return $hooks;
		}

		/**
		 * Add a new filter to the collection to be registered with WordPress.
		 *
		 * @param string $hook          The name of the WordPress filter that is being registered.
		 * @param object $component     A reference to the instance of the object on which the filter is defined.
		 * @param string $callback      The name of the function definition on the $component.
		 * @param int    $priority      Optional. he priority at which the function should be fired. Default is 10.
		 * @param int    $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
		 */
		public function add_filter( string $hook, $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
			$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
		}

		/**
		 * Register the filters and actions with WordPress.
		 */
		public function run(): void {
			foreach ( $this->filters as $hook ) {
				add_filter( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['accepted_args'] );
			}

			foreach ( $this->actions as $hook ) {
				add_action( $hook['hook'], [ $hook['component'], $hook['callback'] ], $hook['priority'], $hook['accepted_args'] );
			}
		}

		public function add_meta_tags() {
			$title = get_bloginfo("name");
			$excerpt = get_bloginfo("description");
			
			if (is_single()) {
				$post = get_post();
				$title = $post->post_title ? $post->post_title : $title;
				$excerpt = get_the_excerpt();
			}
			elseif (is_page()) {
				$page = get_post();
				$title = $page->page_title ? $page->page_title : $title;
			}

			if ($excerpt) {
				if (strlen($excerpt) > 256) {
					$excerpt = substr($excerpt, 0, 253) . "...";
				}
			}

			$text_color =  get_option($this->settings->get_prefixed_option_key( 'text_color' ));
			$background_color =  get_option($this->settings->get_prefixed_option_key( 'background_color' ));

			$title = urlencode(esc_html($title));
			$excerpt = urlencode(esc_html($excerpt));
			$text_color = urlencode(esc_html($text_color));
			$background_color = urlencode(esc_html($background_color));

			$sharizard_link = "https://link.sharizard.com/v1/create?backgroundColor=$background_color&color=$text_color&title=$title&subtitle=$excerpt"




			?>
				<meta 
					name="twitter:image:src" 
					content="<?php echo esc_html($sharizard_link)?>"
				/>
				<meta name="twitter:card" content="summary_large_image">
				<meta 
					property="og:image" 
					content="<?php echo esc_html($sharizard_link)?>"
				></meta>
				<meta property="og:image:height" content="630"/>
				<meta property="og:image:width" content="1200"/>
			<?php
		}
	}
}
