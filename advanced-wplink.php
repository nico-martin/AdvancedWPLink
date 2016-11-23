<?php
/*
Plugin Name:    Advanced WPLink
Plugin URI:     https://wordpress.org/plugins/advanced-wplink/
Description:    This Plugin adds several enhancements to the WP-Link Modal inside the TinyMCE and gives you the possibility to disable the wp inline link tool.
Version: 	1.1.0
Author:         Nico Martin
Author URI:     https://vir2al.ch/
Text Domain:  	awl
*/

namespace NM\AdvancedWPLink;

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

?>
