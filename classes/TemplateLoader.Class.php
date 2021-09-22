<?php
namespace a2PluginBlueprint;

/**
 * Class TemplateLoader
 *
 * @package a2PluginBlueprint
 */
class TemplateLoader {
	/**
	 * @var
	 */
	private $archiveFileName;

	/**
	 * @var
	 */
	private $singleFileName;

	/**
	 * @var
	 */
	private $customPost;

	/**
	 * @var
	 */
	private $customTax;

	/**
	 * @var string
	 */
	private $pluginTemplateDirectory;

	/**
	 * @var string
	 */
	private $childTemplateDirectory;

	/**
	 * @var string
	 */
	private $parentTemplateDirectory;

	/**
	 * TemplateLoader constructor.
	 */
	function __construct( $customPost, $customTax, $templateDirectory = '' ) {
		$this->customPost              = $customPost;
		$this->customTax               = $customTax;
		$this->archiveFileName         = 'archive-' . $this->customPost . '.php';
		$this->singleFileName          = 'single-' . $this->customPost . '.php';
		$this->pluginTemplateDirectory = trailingslashit( plugin_dir_path( __DIR__ ) . $templateDirectory );
		$this->childTemplateDirectory  = trailingslashit( get_stylesheet_directory() . $templateDirectory );
		$this->parentTemplateDirectory = trailingslashit( get_template_directory() . $templateDirectory );

		add_action( 'init', [ $this, 'startUp' ] );
	}

	/**
	 * WordPress startup stuff
	 */
	function startUp() {
		add_filter( 'template_include', [ $this, 'manageTemplate' ] );
	}

	/**
	 * @param $template
	 *
	 * @return string
	 */
	function manageTemplate( $template )
	: string {
		if ( is_tax( $this->customTax ) ) {
			$template = $this->getTemplateLoc( $this->archiveFileName );
		}
		if ( is_post_type_archive( $this->customPost ) ) {
			$template = $this->getTemplateLoc( $this->archiveFileName );
		}

		if ( is_singular( $this->customPost ) ) {
			$template = $this->getTemplateLoc( $this->singleFileName );
		}

		// Always return, even if we didn't change anything
		return $template;
	}

	/**
	 * @param $filename
	 *
	 * @return string
	 */
	function getTemplateLoc( $filename )
	: string {
		// look in child
		if ( file_exists( $this->childTemplateDirectory . $filename ) ) {
			return $this->childTemplateDirectory . $filename;
		}

		// Look in parent
		if ( file_exists( $this->parentTemplateDirectory . $filename ) ) {
			return $this->parentTemplateDirectory . $filename;
		}

		// file is in plugin directory
		return $this->pluginTemplateDirectory . $filename;
	}
}