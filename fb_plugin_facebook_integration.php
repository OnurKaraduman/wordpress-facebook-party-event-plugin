<?php
require_once('fb_plugin_required_lib.php');
global $wp_rewrite;
global $facebook_event_plugin_table;
global $postmeta_facebook_event_id;
global $facebook_event_plugin_db_version;
global $wpdb;
function startFacebookIntegration(){
	global $wpdb;
	global $facebook_event_plugin_table;
	if(isset($_POST['start_facebook_integartion']) && !empty($_POST['start_facebook_integartion']) && $_POST['start_facebook_integartion']=='Start'){
		$tokenResult = $wpdb->get_results("SELECT * FROM $facebook_event_plugin_table WHERE column_name='app_token'");
		$token = $tokenResult[0];
		
		$latResult = $wpdb->get_results("SELECT * FROM $facebook_event_plugin_table WHERE column_name='latitude'");
		$lat = $latResult[0];
		
		$lngResult = $wpdb->get_results("SELECT * from $facebook_event_plugin_table WHERE column_name='longitude'");
		$lng = $lngResult[0];
		
		$distanceResult = $wpdb->get_results("SELECT * from $facebook_event_plugin_table WHERE column_name='distance'");
		$distance = $distanceResult[0];
		integrate($token->column_value,$lat->column_value,$lng->column_value,$distance->column_value);
	}
}



function integrate($token, $lat, $lng, $distance){
	require_once('fb_facebook_event.php');
	$events = getFacebookEvent($token, $lat, $lng, $distance);
	if(!is_null($events)){
		foreach($events as $event){
			if(!existEvent($event['eventId'])){
				if(!empty($event['eventName'])){
					saveSchedule($event);
				}
			}
		}
	}
}

function saveSchedule($event){
	if(!isPartyEvent($event['eventName'],$event['eventDescription'])){
		return;
	}else{
		savePost($event);
	}
}

function savePost($event){
	global $postmeta_facebook_event_id;
	
	$post = array();
	$post['post_author']=1;
	$post['post_status']='publish';
	$post['post_title']=$event['eventName'];
	$post['post_content']=iconv('ISO-8859-1','UTF-8', $event['eventDescription']);
	$post['post_type']='schedule';
	$postId= wp_insert_post($post, true);
	
	
	$dateArray = dataParse($event['eventStarttime']);
	$scheduleDate = $dateArray['year'].'/'.$dateArray['month'].'/'.$dateArray['day'];
	$scheduleTimeStart = $dateArray['hour'].':'.$dateArray['minute'].':'.$dateArray['second'];
	$scheduleLocation = $event['venueLocation']->city;
	$eventId = $event['eventId'];
	update_post_meta($postId,'_schedule_date',$scheduleDate);
	update_post_meta($postId,'_schedule_time_start',$scheduleTimeStart);
	//update_post_meta($postId,'_schedule_time_end',$scheduleDate);
	update_post_meta($postId,'_schedule_location',$scheduleLocation);
	update_post_meta($postId,$postmeta_facebook_event_id,$eventId);
	
	$url = $event['eventCoverPicture'];
	if(!is_null($url) && !empty($url)){
		$attach_id = uploadImage($url, $postId);
		update_post_meta($postId,'_schedule_image',$attach_id);
	}
}
function uploadImage($url, $postId){
	global $wp_rewrite;
	$arrContextOptions=array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	);  
	$contents = file_get_contents($url, false, stream_context_create($arrContextOptions));
	$filename = filePath($url)['filename'].'.jpg';
	$uploaddir = wp_upload_dir();
	$uploadfile = $uploaddir['path'] . '/' . $filename;
	$savefile = fopen($uploadfile, 'w');
	fwrite($savefile, $contents);
	fclose($savefile);
	
	
	$wp_filetype = wp_check_filetype(basename($filename), null );

	$attachment = array(
		'guid'           => $uploaddir['url'] . '/' . $filename , 
		'post_mime_type' => $wp_filetype['type'],
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
		'post_content'   => '',
		'post_status'    => 'inherit'
	);
	$attach_id = wp_insert_attachment( $attachment, $uploadfile, $postId );
	echo "attach id ---".$attach_id;
	$imagenew = get_post( $attach_id );
	$fullsizepath = get_attached_file( $imagenew->ID );
	echo "fullsize path ---".$fullsizepath;
	$attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	return $attach_id;
}

function getImageDescriptionFromUrl($imageUrl){
	$imageDescArray = explode("/",$imageUrl);
	$imageDescFormat = end($imageDescArray);
	$nIndex =  strpos($imageDescFormat , '.');
	$imageDesc = substr($imageDescFormat, 0, $nIndex);
}
function dataParse($strDate){
	return date_parse_from_format('Y-m-d:H:i', $strDate);
}

function filePath($filePath)
{
	$fileParts = pathinfo($filePath);
	if(!isset($fileParts['filename']))
	{$fileParts['filename'] = substr($fileParts['basename'], 0, strrpos($fileParts['basename'], '.'));}
	return $fileParts;
}

function existEvent($eventId){
	global $postmeta_facebook_event_id;
	global $wpdb;
	$post_meta_table = $wpdb->prefix . 'postmeta';
	$eventResult = $wpdb->get_results("SELECT * from $post_meta_table WHERE meta_key='$postmeta_facebook_event_id' and meta_value='$eventId'");
	if($wpdb->num_rows >0){
		return true;
	}
	else{
		return false;
	}
}

function isPartyEvent($eventName, $eventDescription){
	$eventNameTmp = strtolower($eventName);
	$eventDescTmp = strtolower($eventDescription);
	if (strpos($eventNameTmp, 'party') !== false || strpos($eventNameTmp, 'parti') !== false) {
		return true;	
	}else{
		if (strpos($eventDescTmp, 'party') !== false || strpos($eventDescTmp, 'parti') !== false) {
			return true;
		}
	}
	return false;
}
?>