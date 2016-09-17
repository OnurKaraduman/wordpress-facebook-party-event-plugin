<?php
global $wp_rewrite;
global $facebook_event_plugin_table;
global $postmeta_facebook_event_id;
global $facebook_event_plugin_db_version;
global $wpdb;
function insertPluginSpecification(){
	insertToken();
	insertLatitude();
	insertLongitude();
	insertDistance();
}

function insertToken(){
	global $wpdb;
	global $facebook_event_plugin_table;
	if(isset($_POST["txt_facebook_event_token"]) && !empty($_POST["txt_facebook_event_token"])){
		$app_token=$_POST['txt_facebook_event_token'];
		$query="INSERT INTO $facebook_event_plugin_table (column_name, column_value) VALUES ('app_token','$app_token')";
		$rest = $wpdb->query($query);
	}
}


function insertLatitude(){
	global $wpdb;
	global $facebook_event_plugin_table;
	if(isset($_POST["txt_facebook_event_latitude"]) && !empty($_POST["txt_facebook_event_latitude"])){
		$app_token=$_POST['txt_facebook_event_latitude'];
		$query="INSERT INTO $facebook_event_plugin_table (column_name, column_value) VALUES ('latitude','$app_token')";
		$rest = $wpdb->query($query);
	}
}
function insertLongitude(){
	global $wpdb;
	global $facebook_event_plugin_table;
	if(isset($_POST["txt_facebook_event_longitude"]) && !empty($_POST["txt_facebook_event_longitude"])){
		$app_token=$_POST['txt_facebook_event_longitude'];
		$query="INSERT INTO $facebook_event_plugin_table (column_name, column_value) VALUES ('longitude','$app_token')";
		$rest = $wpdb->query($query);
	}
}
function insertDistance(){
	global $wpdb;
	global $facebook_event_plugin_table;
	if(isset($_POST["txt_facebook_event_distance"]) && !empty($_POST["txt_facebook_event_distance"])){
		$app_token=$_POST['txt_facebook_event_distance'];
		$query="INSERT INTO $facebook_event_plugin_table (column_name, column_value) VALUES ('distance','$app_token')";
		$rest = $wpdb->query($query);
	}
}
?>