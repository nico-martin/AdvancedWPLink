<?php
/*
Plugin Name: Advanced WPLink
Plugin URI: https://wordpress.org/plugins/advanced-wplink/
Description: This Plugin adds several enhancements to the WP-Link Modal inside the TinyMCE and gives you the possibility to disable the wp inline link tool.
Version: 1.2.0-dev
Author: Nico Martin
Author URI: https://vir2al.ch/
Text Domain: awl
*/

if(version_compare(PHP_VERSION, '5.3', '<')) {

	function awl_compatability_warning(){
		echo '<div class="error"><p>'.sprintf(
			__('“%1$s” requires PHP %2$s (or newer) to function properly. Your site is using PHP %3$s. Please upgrade. The plugin has been automatically deactivated.', 'awl'),
			'Advanced WPLink',
			'5.3',
			PHP_VERSION
		).'</p></div>';

		if (isset($_GET['activate'])) {
			unset($_GET['activate']);
		}
	}
	add_action('admin_notices', 'awl_compatability_warning');

	function awl_deactivate_self(){
		deactivate_plugins(plugin_basename(__FILE__));
	}
	add_action('admin_init', 'awl_deactivate_self');
	return;

}else{

	$awl_settings = array(
		'name'		=> 'Advanced WPLink',
		'textdomain'=> 'awl',

		'wp_version'=> '4.5',
		'version'	=> '0',

		'dirname'	=> dirname(plugin_basename(__FILE__)),
		'dir'		=> plugins_url(plugin_basename(__DIR__)),
		'plugin'	=> plugin_basename(__FILE__)
	);

	/**
	 * Get Plugin Version
	 */

	if (!function_exists('get_plugins')) {
        require_once ABSPATH.'wp-admin/includes/plugin.php';
    }

    $plugin_folder = get_plugins('/'.plugin_basename(dirname(__FILE__)));
    $plugin_file = basename((__FILE__));
    $awl_settings['version'] = $plugin_folder[$plugin_file]['Version'];

    /**
     * load textdomain
     */

	add_action('plugins_loaded', function(){

		global $awl_settings;
		load_plugin_textdomain($awl_settings['textdomain'], false, $awl_settings['dirname'].'/languages/'); 
	});

	/**
	 * Load classes
	 */

	//Activate
	require_once 'class/Activate.php';

	//Admin Page
	require_once 'class/AdminPage.php';

	//Editor
	require_once 'class/Editor.php';

}