<?php
namespace a2PluginBlueprint;

/**
 * Class Core
 */
class Core {
	/**
	 * @var string
	 */
	static $pluginDirectory;

	/**
	 * @var string
	 */
	static $pluginURL;

	/**
	 * @var string
	 */
	static $pluginVersion;

	/**
	 * @var string
	 */
	static $mainFile;

	/**
	 * @var string
	 */
	static $pluginName;

	/**
	 * @var null|\a2PluginBlueprint\Core
	 */
	private static $instance = null;

	/**
	 * Core constructor.
	 */
	private function __construct( $mainFile ) {
		self::$pluginName      = 'a2 Plugin Blueprint';
		self::$pluginDirectory = plugin_dir_path( __DIR__ );
		self::$pluginURL       = plugin_dir_url( __DIR__ );
		self::$mainFile        = $mainFile;
		self::$pluginVersion   = '0.1.0';
	}

	/**
	 * @param $mainFile
	 *
	 * @return null|\a2PluginBlueprint\Core
	 */
	static function getInstance( $mainFile )
	: ?Core {
		if ( self::$instance == null ) {
			self::$instance = new Core( $mainFile );
		}

		return self::$instance;
	}
}
