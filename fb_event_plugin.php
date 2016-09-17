<?php
/*
Plugin Name: Facebook Event Plugin
Plugin URI: 
Description: Facebook Nearest Event Entegration For EventBox Theme
Version: 0.1
Author: Onur Karaduman
Author URI: onurkaraduman.com
*/
require_once('fb_plugin_required_lib.php');

if(preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])){
	die('you are not allowed to call this page direcectly');
}
/*
Plugin
*/
global $wp_rewrite;
global $facebook_event_plugin_table;
global $postmeta_facebook_event_id;
global $facebook_event_plugin_db_version;
global $wpdb;
$facebook_event_plugin_db_version='1.0';
$facebook_event_plugin_table=$wpdb->prefix . 'facebook_event';
$postmeta_facebook_event_id = '_facebook_event_id';

/*
 postCostruct
*/
register_activation_hook(__FILE__,'facebook_event_plugin_install');
function facebook_event_plugin_install(){
	global $facebook_event_plugin_table;
	global $facebook_event_plugin_db_version;
	global $wpdb;
	$facebook_event_plugin_db_version='1.0';
	$facebook_event_plugin_table=$wpdb->prefix . 'facebook_event';
	if($wpdb->get_var("show tables like '$facebook_event_plugin_table'") != $facebook_event_plugin_table){
		$sql = "CREATE TABLE ". $facebook_event_plugin_table." (".
		"id int NOT NULL AUTO_INCREMENT,".
		"column_name varchar(40) NOT NULL,".
		"column_value varchar(50) NOT NULL,".
		"PRIMARY KEY (id)".
		")";
		require_once( ABSPATH. 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		add_option("facebook_event_plugin_db_version",$facebook_event_plugin_db_version);
	}
}

function facebook_plugin_management(){
	add_menu_page('Facebook Event Plugin','FacebookPlugin','manage_options','facebook_plugin','facebook_plugin_function');
}

function facebook_plugin_function(){
	include 'fb_plugin_custom_admin_page.php';
}

function init(){
	require_once('fb_plugin_db_operation.php');
	require_once('fb_plugin_facebook_integration.php');
	insertPluginSpecification();
	startFacebookIntegration();
	add_action('admin_menu','facebook_plugin_management');
}
init();
?>
