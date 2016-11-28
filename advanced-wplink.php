<?php
/*
Plugin Name:	Advanced WPLink
Plugin URI:		https://wordpress.org/plugins/advanced-wplink/
Description: 	This Plugin adds several enhancements to the WP-Link Modal inside the TinyMCE and gives you the possibility to disable the wp inline link tool.
Version: 		1.1.0
Author:			Nico Martin
Author URI: 	https://vir2al.ch/
Text Domain:	awl
*/

if(version_compare(PHP_VERSION, '5.3', '<')) {

	function awl_compatability_warning(){
		echo '<div class="error"><p>'.sprintf(
			__('“%1$s” requires PHP %2$s (or newer) to function properly. Your site is using PHP %3$s. Please upgrade. The plugin has been automatically deactivated.', 'awl'),
			__('Advanced WPLink','awl'),
			'5.3',
			PHP_VERSION,
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

	define('awl_version','1.0.1');
	define('awl_min_wp_version','4.5');
	define('awl_name',__('Advanced WPLink','awl'));

	define('awl_folder','advanced-wplink');
	define('awl_dir',plugins_url().'/'.awl_folder);
	define('awl_file',awl_folder.'/'.awl_folder.'.php');

	add_action('plugins_loaded', function(){
		load_plugin_textdomain('awl', false, dirname(plugin_basename(__FILE__)).'/languages/'); 
	});

	//Admin Page
	require_once 'class/Activate.php';

	//Admin Page
	require_once 'class/AdminPage.php';

	//Editor
	require_once 'class/Editor.php';

}