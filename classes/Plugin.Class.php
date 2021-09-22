<?php
namespace a2PluginBlueprint;

/**
 * Class Plugin
 *
 * @package a2PluginBlueprint
 */
class Plugin extends Core {
	/**
	 * @var \a2PluginBlueprint\StoreLocator
	 */
	var $stores;

	/**
	 * @var \a2PluginBlueprint\Generator
	 */
	var $generator;

	/**
	 * @var \a2PluginBlueprint\Admin
	 */
	var $admin;

	/**
	 * Plugin constructor.
	 */
	function __construct() {
		// Check plugin dependencies
		$dependencies['advanced-custom-fields-pro/acf.php']      = 'ACF Pro 5+';
		$dependencies['acf-extended/acf-extended.php']           = 'ACF Extended';

		/**
		 * Uncomment these to enable dependency checks for the store locator module
		 */
//		$dependencies['facetwp/index.php']                       = 'FacetWP';
//		$dependencies['facetwp-map-facet/facetwp-map-facet.php'] = 'FacetWP - Map Facet';

		$checker = new DependencyChecker( $dependencies, self::$mainFile, self::$pluginName );

		// Load modules if dependencies have checked out
		if ( $checker->hasPassed ) {
			/**
			 * The admin module handles boring stuff like
			 * installation, upgrading and deactivation hooks
			 *
			 * For the most part these are routines that are helpful
			 * for making a plugin as portable as possible
			 */
			$this->admin = new Admin();

			/**
			 * Uncomment the following line to reroute all site emails
			 */
//			Debug::emailOverride('chris@asquaredstudio.com');

			/**
			 * Uncomment to load to demo store module
			 */
//			$this->stores = new StoreLocator();

			/**
			 * Comment to hide the generator module
			 */
			$this->generator = new Generator();

		}
	}
}