<?php
namespace a2PluginBlueprint;

/**
 * a2PluginBlueprint
 *
 * @author            (a)squaredstudio
 * @package           a2PluginBlueprint
 * @copyright         2021 (a)squaredstudio
 * @license           GPL-2.0-or-later
 * @wordpress-plugin
 * Plugin Name:       a2 Plugin Blueprint
 * Plugin URI:        https://github.com/asquaredstudio/a2PluginBlueprint
 * Description:       WordPress plugin template
 * Version:           0.1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            (a)squaredstudio
 * Author URI:        https://asquaredstudio.com
 * Text Domain:       plugin-slug
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://github.com/asquaredstudio/a2PluginBlueprint
 */

/**
 * Super quick autoloader
 *
 * @param $class
 */

spl_autoload_register( function ( $class ) {
	$dir  = plugin_dir_path( __FILE__ ) . 'classes/';
	$file = str_replace( '\\', '/', $class ) . '.Class.php';
	$file = str_replace( __NAMESPACE__ . '/', '', $file);
	$path = $dir . $file;

	if ( file_exists( $path ) ) {
		require_once $path;
	}
} );

// Init the global plugin core
Core::getInstance( plugin_basename( __FILE__ ) );

// Launch the plugin
$a2PluginBlueprint = new Plugin;
