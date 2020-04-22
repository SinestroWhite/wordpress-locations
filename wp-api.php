<?php
/**
 * @package WP-API
 * @version 0.0.1
 */
/*
Plugin Name: WP-API
Plugin URI: 
Description: 
Author: Georgi Yokov
Version: 0.0.1
Author URI: 
*/

function getLocations() {

	$lat1 = $_GET["lat1"] ? $_GET["lat1"] : -90;
	$lng1 = $_GET["lng1"] ? $_GET["lng1"] : -180;
	$lat2 = $_GET["lat2"] ? $_GET["lat2"] : 90;
	$lng2 = $_GET["lng2"] ? $_GET["lng2"] : 180; 
	$magMin = $_GET["magMin"] ? $_GET["magMin"] : 0;
	$magMax =  $_GET["magMax"] ? $_GET["magMax"] : 9; 
	$depthMin = $_GET["depthMin"] ? $_GET["depthMin"] : 0;
	$depthMax = $_GET["depthMax"] ? $_GET["depthMax"] : 1000; 
	$dateBegin = $_GET["dateBegin"] ? $_GET["dateBegin"] : 0;
	$dateEnd = $_GET["dateEnd"] ? $_GET["dateEnd"] : 99999999999999999999;

	$request = "SELECT * FROM markers WHERE ".$lat1." <= `lat` AND `lat` <= ".$lat2." AND ".$lng1." <= `lng` AND `lng` <= ".$lng2. " AND ".$magMin. " <= `magnitude` AND `magnitude` <=" .$magMax. " AND " .$depthMin. " <= `depth` AND `depth` <= ". $depthMax. " AND " .$dateBegin. " <= `time` AND `time` <= " .$dateEnd;

	$newdb = new wpdb('wordpressuser', 'password', 'wordpress', 'localhost');
	$rows = $newdb->get_results($request);

	return json_encode($rows);
}

add_action('rest_api_init', function () {
	register_rest_route( 'twentytwenty/v1', 'locations', array(
		'methods'  => 'GET',
		'callback' => 'getLocations'
	));
});
?>
