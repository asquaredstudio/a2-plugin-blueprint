<?php
namespace a2PluginBlueprint;

/**
 * Generates a customized zip file based on this
 * codebase.  Useful for quickly kick-starting a
 * new plugin.
 */
class Generator extends Core {
	/**
	 * Name of the options page we are targeting
	 *
	 * @var string
	 */
	private $optionsPageName = 'a2_blueprint_generator';

	/**
	 * This array makes it super easy to loop through any
	 * directory and file structure in a simple foreach loop
	 *
	 * @var array
	 */
	private $flatFileList;


	/**
	 * Generator constructor.
	 */
	function __construct() {
		add_action( 'init', [ $this, 'generatorInit' ] );
		add_action( 'admin_init', [ $this, 'adminInit' ] );
	}

	/**
	 * Init module
	 *
	 * @return void
	 */
	function generatorInit() {
		acf_add_options_page( [
			'page_title'      => 'blueprint generator',
			'menu_slug'       => $this->optionsPageName,
			'menu_title'      => 'blueprint generator',
			'capability'      => 'edit_posts',
			'position'        => '',
			'parent_slug'     => '',
			'icon_url'        => 'dashicons-plugins-checked',
			'redirect'        => true,
			'post_id'         => 'options',
			'autoload'        => false,
			'update_button'   => 'Update',
			'updated_message' => 'Options Updated',
		] );
	}

	/**
	 * Attaches an action to JUST the page we are targeting
	 *
	 * @return void
	 */
	function adminInit() {
		// Find the appropriate hook name for our options page
		$option_page_slug = get_plugin_page_hookname( $this->optionsPageName, 'admin.php' );
		add_action( "load-{$option_page_slug}", [ $this, 'generatorPageActions' ], 11 );

		// Enqueue necessary scripts
		add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueStylesScripts' ] );
		add_action( 'wp_ajax_generate_zip_archive', [ $this, 'generateZipArchive' ] );
	}

	/**
	 * Generates a ZIP archive based on the prefilled form fields
	 *
	 * @return void
	 */
	function generateZipArchive() {
		$files = $this->dirToArray( self::$pluginDirectory );
		$this->buildFlatFile( $files );

		//check and create base directory if needed
		$upload_dir = wp_upload_dir();
		$base_dir   = $upload_dir['basedir'] . '/bp-generator/';
		$base_url   = $upload_dir['baseurl'] . '/bp-generator/';
		$time       = time();
		$zipname    = $base_dir . $_POST['plugin_file_slug'] . '-' . $time . '.zip';
		$zipurl     = $base_url . $_POST['plugin_file_slug'] . '-' . $time . '.zip';
		if ( ! is_dir( $base_dir ) ) {
			mkdir( $base_dir, 0755 );
		}

		$search = [
			'a2 Plugin Blueprint',
			'a2PluginBlueprint',
			'a2-plugin-blueprint',
			'0.1.0',
			'https://github.com/asquaredstudio/a2PluginBlueprint',
			'WordPress plugin template',
			'(a)squaredstudio',
			'https://asquaredstudio.com'
		];

		$replace = [
			$_POST['plugin_label'],
			$_POST['plugin_namespace'],
			$_POST['plugin_file_slug'],
			$_POST['plugin_version'],
			$_POST['plugin_url'],
			$_POST['plugin_description'],
			$_POST['plugin_author'],
			$_POST['plugin_author_url'],
		];

		// Create a new zip archive
		$zip = new \ZipArchive();
		$zip->open( $zipname, \ZipArchive::CREATE );


		// Loop through all the results and create the new zip
		foreach ( $this->flatFileList as $node ) {
			switch ( $node['type'] ) {
				case 'directory':
					$dirname = str_replace( self::$pluginDirectory, '', $node['value'] );
					$zip->addEmptyDir( $dirname );
					break;
				case 'file':
					$contents = str_replace( $search, $replace, file_get_contents( $node['value'] ) );
					$file     = str_replace( self::$pluginDirectory, '', $node['value'] );
					$file     = str_replace( $search, $replace, $file );
					$zip->addFromString( $file, $contents );
					break;
			}
		}

		$zip->close();
		$result['url']  = $zipurl;
		$result['type'] = 'success';
		echo json_encode( $result );
		die();
	}

	/**
	 * Recursive function that converts a scandir
	 * array to a slightly more usable format
	 *
	 * @param $dir
	 *
	 * @return array
	 */
	function dirToArray( $dir ) {
		$result = [];

		$cdir = scandir( $dir );
		foreach ( $cdir as $key => $value ) {
			if ( ! in_array( $value, [ ".", ".." ] ) ) {
				if ( is_dir( $dir . DIRECTORY_SEPARATOR . $value ) ) {
					$result[ $value ] = $this->dirToArray( $dir . DIRECTORY_SEPARATOR . $value );
				}
				else {
					$result[] = $value;
				}
			}
		}

		return $result;
	}

	/**
	 * Converts the output of dirToArray into a
	 * super easy to use array
	 *
	 * @param  array   $array
	 * @param  string  $tree
	 *
	 * @return void
	 */
	function buildFlatFile( array $array, string $tree = '' ) {
		foreach ( $array as $key => $value ) {
			if ( is_array( $value ) ) {
				if ( ! empty( $tree ) ) {
					$destinationTree = $tree . "/" . $key;

					$this->flatFileList[] = [
						'type'  => 'directory',
						'value' => self::$pluginDirectory . $destinationTree
					];
				}

				else {
					$this->flatFileList[] = [
						'type'  => 'directory',
						'value' => self::$pluginDirectory . $key
					];

					$destinationTree = $key;
				}

				$this->buildFlatFile( $value, $destinationTree );
			}

			else {
				if ( ! empty( $tree ) ) {
					$this->flatFileList[] = [
						'type'  => 'file',
						'value' => self::$pluginDirectory . $tree . "/" . $value
					];
				}

				else {
					$this->flatFileList[] = [
						'type'  => 'file',
						'value' => self::$pluginDirectory . $value
					];
				}
			}
		}
	}

	/**
	 * Admin dependencies
	 *
	 * @return void
	 */
	function adminEnqueueStylesScripts() {
		wp_enqueue_script( 'generator', self::$pluginURL . '/assets/js/generator.js', [ 'jquery' ], self::$pluginVersion, true );
		wp_localize_script( 'generator', 'ajax_object', [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ] );
	}

	/**
	 * More attaching actions
	 *
	 * @return void
	 */
	function generatorPageActions() {
		add_action( 'acf/input/admin_head', [ $this, 'removeGeneratorMetaBox' ], 11 );

		// also change it to 1 column, so you don't have empty sidebar
		add_screen_option( 'layout_columns', [ 'max' => 1, 'default' => 1 ] );
	}

	/**
	 * Finally removes the meta box
	 *
	 * @return void
	 */
	function removeGeneratorMetaBox() {
		remove_meta_box( 'submitdiv', 'acf_options_page', 'side' );
	}
}