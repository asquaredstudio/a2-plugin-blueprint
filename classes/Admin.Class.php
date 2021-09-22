<?php
namespace a2PluginBlueprint;

/**
 * Class Admin.
 *
 * Contains backend plugin actions such as
 * installation / upgrading / etc.
 *
 * https://core.trac.wordpress.org/ticket/14170#comment:68
 */
class Admin extends Core {
	function __construct() {
		add_action('admin_init', [$this, 'adminInit']);
	}

	function adminInit() {

	}

	private function installPlugin() {

	}

	private function upgradePlugin() {

	}

}