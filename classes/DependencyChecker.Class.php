<?php
namespace a2PluginBlueprint;

/**
 * Class DependencyChecker
 *
 * @package a2PluginBlueprint
 */
class DependencyChecker {
	/**
	 * @var $dependencies
	 */
	var $dependencies;

	/**
	 * @var array
	 */
	var $errorOutput;

	/**
	 * @var bool
	 */
	var $hasPassed = false;

	/**
	 * @var string
	 */
	var $mainFile;

	/**
	 * @var string
	 */
	var $pluginName;

	/**
	 * @param $dependencies
	 * @param $mainFile
	 * @param $pluginName
	 */
	function __construct( $dependencies, $mainFile, $pluginName ) {
		// Load the necessary library
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( is_array( $dependencies ) ) {
			$this->dependencies = $dependencies;
			$this->mainFile     = $mainFile;
			$this->pluginName   = $pluginName;

			// Cycle through the dependencies as check to see if anything is missing
			foreach ( $this->dependencies as $key => $value ) {
				if ( ! is_plugin_active( $key ) ) {
					$output[] = '<li><strong>- ' . $value . '</strong> is not active.' . '</li>';
				}
			}

			/**
			 * We haven't met the dependencies!
			 *
			 * Deactivate the plugin and return an error message
			 */
			if ( isset( $output ) ) {
				$this->errorOutput = $output;
				deactivate_plugins( $this->mainFile );
				add_action( 'admin_notices', function () {
					echo '<div class="error"><h3 class="wp-heading-inline">Cannot enable <strong>' . $this->pluginName . '!</strong> Several dependent plugins are required to be active:</h3><ul>' . implode( '', $this->errorOutput ) . '</ul></div>';
				} );


				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			}

			else {
				$this->hasPassed = true;
			}
		}
	}


}
