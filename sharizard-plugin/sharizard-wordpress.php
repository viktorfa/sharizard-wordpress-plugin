<?php
/**
 * The plugin bootstrap file.
 *
 * https://github.com/cliffordp/sharizard-wordpress#plugin-structure
 * Introduction to the structure of this plugin's files:
 *
 * sharizard-wordpress/src/class-PluginData.php - hard-coded information about the plugin, such as plugin-slug and plugin_slug.
 * sharizard-wordpress/src/class-Bootstrap.php - gets the plugin going, including setting required/recommended plugin dependencies
 *
 * sharizard-wordpress/src/Frontend - public-facing functionality
 * sharizard-wordpress/src/Admin - admin-specific functionality
 * sharizard-wordpress/src/Common - functionality shared between the admin area and the public-facing parts
 *
 * sharizard-wordpress/src/Common/Utilities - generic functions for things like debugging, processing multidimensional arrays, handling datetimes, etc.
 * sharizard-wordpress/src/Core - plugin core to register hooks, load files etc
 * sharizard-wordpress/src/Customizer - WordPress Customizer functionality
 * sharizard-wordpress/src/Shortcodes - where to create and enable/disable new shortcodes
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           SharizardWordpress
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Social Media Preview
 * Plugin URI:        https://www.sharizard.com/
 * Description:       Simple social media preview images that just work.
 * Version:           1.0.0
 * Author:            Sharizard
 * Author URI:        https://www.sharizard.com/
 * License:           GPL version 3 or any later version
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       sharizard-wordpress
 * Domain Path:       /languages
 *
 ***
 *
 *     This plugin is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     any later version.
 *
 *     This plugin is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *     GNU General Public License for more details.
 *
 ***
 *
 *     This plugin was helped by Clifford Paulick's Plugin Boilerplate,
 *     available free at https://github.com/cliffordp/sharizard-wordpress
 *     You are invited to use it for your own WordPress projects.
 */

// Cannot `declare( strict_types=1 );` to avoid fatal if prior to PHP 7.0.0, since we did not yet verify the PHP version.

namespace SharizardWordpress;

// Abort if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Autoloading, via Composer.
 *
 * @link https://getcomposer.org/doc/01-basic-usage.md#autoloading
 */
require_once( 'vendor/autoload.php' );

// Define Constants

/**
 * Register Activation and Deactivation Hooks
 * This action is documented in src/Core/class-Activator.php
 */
register_activation_hook( __FILE__, [ __NAMESPACE__ . '\Core\Activator', 'activate' ] );

/**
 * The code that runs during plugin deactivation.
 * This action is documented src/Core/class-Deactivator.php
 */
register_deactivation_hook( __FILE__, [ __NAMESPACE__ . '\Core\Deactivator', 'deactivate' ] );

// Begin execution of the plugin.
( new Bootstrap() )->init();