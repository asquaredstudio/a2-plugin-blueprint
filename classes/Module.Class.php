<?php
namespace a2PluginBlueprint;

/**
 * Class Module
 *
 * @package a2PluginBlueprint
 */
abstract class Module {
	/**
	 * @var string
	 */
	protected $customPostName;

	/**
	 * @var string
	 */
	protected $customTaxName;

	/**
	 * @param $customPostName
	 * @param $customTaxName
	 */
	function __construct( $customPostName, $customTaxName ) {
		$this->customPostName = $customPostName;
		$this->customTaxName  = $customTaxName;

		// Adds this module's content to the template loading rules
		new TemplateLoader( $this->customPostName, $this->customTaxName, 'templates' );

		add_action( 'init', [ $this, 'initPlugin' ] );
		add_action( 'acf/init', [ $this, 'initACF' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'initStylesScripts' ] );
		add_filter( 'facetwp_facets', [ $this, 'initFacet' ] );
	}

	/**
	 * Registers models and any additional plugin functionality
	 *
	 * @return void
	 */
	function initPlugin() {
	}

	/**
	 * Loads some scripts
	 *
	 * @return void
	 */
	function initStylesScripts() {
	}

	/**
	 * Performs any ACF init actions
	 *
	 * @return void
	 */
	function initACF() {
	}

	/**
	 * @param $facets
	 *
	 * @return mixed
	 */
	function initFacet( $facets ) {
		return $facets;
	}


}