<?php
namespace a2PluginBlueprint;

/**
 * Class Debug
 *
 * @package a2PluginBlueprint
 */
class Debug {
	/**
	 * The email address we will forward mail to
	 *
	 * @var string
	 */
	static $emailAddress;

	/**
	 * Route all site email to the specified address
	 *
	 * @param $emailAddress
	 *
	 * @return void
	 */
	static function emailOverride($emailAddress) {

		self::$emailAddress = $emailAddress;

		add_filter('wp_mail',function($emailAddress){
			$args['to'] = self::$emailAddress;
			return $args;
		}, 10,1);
	}

	/**
	 * print_r to the php_errorlog.  This is a great way to inspect
	 * variables that live inside filters
	 *
	 * @param $array
	 *
	 * @return void
	 */
	static function log( $array ) {
		ob_start();
		print_r( $array );

		error_log( ob_get_clean(), 3, 'php_errorlog' );
	}
}