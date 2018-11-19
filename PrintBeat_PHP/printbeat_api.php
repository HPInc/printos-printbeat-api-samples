<?php
# © Copyright 2018 HP Development Company, L.P.
# SPDX-License-Identifier: MIT

#Access credentials
$baseUrl = 'https://printos.api.hp.com/printbeat'; #use for a production account
#$baseUrl = 'https://stage.printos.api.hp.com/printbeat'; #use for a staging account
$key = '';   #enter the PrintBeat key generated from your PrintOS account here
$secret = '';   #enter the PrintBeat secret generated from your PrintOS account here

#enter the list of press serial numbers from your account
$devices = array("50110187", "51000178");
#for API calls that require a date range enter the To and From dates below (notice no 'T' character in date stamp)
$fromDate = '2018-06-03 00:00:00';
$toDate = '2018-11-20 00:00:00';
#resolution is the interval at which you want to break down data.  Valid values are 'Day', 'Week' and 'Month'
$resolution = 'Week';
#unitSystem that you want values reported back in 'Imperial' or 'Metric'
$unitSystem = 'Metric';
#id is the ColorBeat identification number for pulling raw data
$id = 1;

#API Endpoint Calls (uncomment each line to make that API Endpoint call)
#--------------------------------------------------------------#

getHistoricKPI('OverallPerformance', $devices, $fromDate, $toDate, $resolution, $unitSystem);  //KPIs are 'OverallPerformance', 'PrintVolume'
#getRealTimeData($devices, $resolution, $unitSystem);  
#getDataByTimeFrame($devices, $fromDate, NULL);  
#getRawData($id);
#getOEEChart($devices, $fromDate, $toDate, $resolution);
#getOEEDrilldown($devices, $fromDate, $toDate, $resolution);


#PrintBeat APIs
#--------------------------------------------------------------#

/**
 * Gets historical data based on a defined KPI.
 * 
 * @param $kpi - KPI to query
 * @param $devices - array of device serial numbers to query
 * @param $fromDate - date to start query from 'YYYY-MM-DD hh:mm:ss' 
 * @param $toDate - date to end query at 'YYYY-MM-DD hh:mm:ss'
 * @param $resolution - time resolution to show data 'Day' 'Week' or 'Month'
 * @param $unitSystem - units to display information in 'Imperial' or 'Metric' 
 */
function getHistoricKPI($kpi, $devices, $fromDate, $toDate, $resolution, $unitSystem) {
	echo "Getting historical date based on KPI" . $kpi . "</br>";
	$queryString = '?';
	#add devices to query string
	if(!empty($devices)){
		$deviceQueryString = "";
		for($i = 0; $i < count($devices); $i++){
			if ($i > 0){
				$deviceQueryString = $deviceQueryString . '&'; 
			}
			$deviceQueryString = $deviceQueryString . 'devices=' . $devices[$i];
		}
		$queryString = $queryString . $deviceQueryString;
	}
	#add from/to dates to query string
	$queryString = $queryString . '&from=' . $fromDate;
	if ($toDate != null){
		$queryString = $queryString . '&to=' . $toDate;
	}
	#add resolution and unit system
	$queryString = $queryString . '&resolution=' . $resolution . '&unitSystem=' . $unitSystem;
	echo "Query string is: " . $queryString . "</br>";
	$response = getRequest('/externalApi/v1/Historic/' . $kpi, $queryString);
	printInfo($response);
}

/**
 * Gets real time data for your devices.
 *  
 * @param $devices - array of device serial numbers to query
 * @param $resolution - time resolution to show data 'Day' 'Week' or 'Month'
 * @param $unitSystem - units to display information in 'Imperial' or 'Metric' 
 */
function getrealTimeData($devices, $resolution, $unitSystem) {
	echo "Getting data from presses</br>";
	$queryString = '?';
	#add devices to query string
	if(!empty($devices)){
		$deviceQueryString = "";
		for($i = 0; $i < count($devices); $i++){
			if ($i > 0){
				$deviceQueryString = $deviceQueryString . '&'; 
			}
			$deviceQueryString = $deviceQueryString . 'devices=' . $devices[$i];
		}
		$queryString = $queryString . $deviceQueryString;
	}
	#add resolution and unit system
	$queryString = $queryString . '&resolution=' . $resolution . '&unitSystem=' . $unitSystem;
	echo "Query string is: " . $queryString . "</br>";
	$response = getRequest('/externalApi/v1/RealTimeData', $queryString);
	printInfo($response);
}

/**
 * Gets ColorBeat data for specific time frame
 * 
 * @param $devices - array of device serial numbers to query
 * @param $fromDate - date to start query from 'YYYY-MM-DD hh:mm:ss' 
 * @param $toDate - date to end query at 'YYYY-MM-DD hh:mm:ss'
 */
function getDataByTimeFrame($devices, $fromDate, $toDate) {
	echo "Getting ColorBeat data in a time frame</br>";
	$queryString = '?';
	#add devices to query string
	if(!empty($devices)){
		$deviceQueryString = "";
		for($i = 0; $i < count($devices); $i++){
			if ($i > 0){
				$deviceQueryString = $deviceQueryString . '&'; 
			}
			$deviceQueryString = $deviceQueryString . 'devices=' . $devices[$i];
		}
		$queryString = $queryString . $deviceQueryString;
	}
	#add from/to dates to query string
	$queryString = $queryString . '&from=' . $fromDate;
	if ($toDate != null){
		$queryString = $queryString . '&to=' . $toDate;
	}
	echo "Query string is: " . $queryString . "</br>";
	$response = getRequest('/externalApi/v1/colorbeat/dataByTimeFrame', $queryString);
	printInfo($response);
}

/**
 * Gets raw data out of PrintBeat ColorBeat based on a provided measurement ID
 */
function getRawData($id) {
	echo "Getting ColorBeat raw data</br>";
	$queryString = '?id=' . $id;
	$response = getRequest('/externalApi/v1/colorbeat/rawdata', $queryString);
	printInfo($response);
}

/**
 * Gets ColorBeat data for specific time frame
 * 
 * @param $devices - array of device serial numbers to query
 * @param $fromDate - date to start query from 'YYYY-MM-DD hh:mm:ss' 
 * @param $toDate - date to end query at 'YYYY-MM-DD hh:mm:ss'
 * @param $resolution - time resolution to show data 'Day' 'Week' or 'Month'
 */
function getOEEChart($devices, $fromDate, $toDate, $resolution) {
	echo "Getting OEE reports per press for a time range and resolution</br>";
	$queryString = '?';
	#add devices to query string
	if(!empty($devices)){
		$deviceQueryString = "";
		for($i = 0; $i < count($devices); $i++){
			if ($i > 0){
				$deviceQueryString = $deviceQueryString . '&'; 
			}
			$deviceQueryString = $deviceQueryString . 'devices=' . $devices[$i];
		}
		$queryString = $queryString . $deviceQueryString;
	}
	#add from/to dates to query string
	$queryString = $queryString . '&from=' . $fromDate;
	if ($toDate != null){
		$queryString = $queryString . '&to=' . $toDate;
	}
	#add resolution
	$queryString = $queryString . '&resolution=' . $resolution;
	echo "Query string is: " . $queryString . "</br>";
	$response = getRequest('/externalApi/v1/oee/chart', $queryString);
	printInfo($response);
}


/**
 * Gets ColorBeat report for each of the KPIs availability, performance and quality
 * 
 * @param $devices - array of device serial numbers to query
 * @param $fromDate - date to start query from 'YYYY-MM-DD hh:mm:ss' 
 * @param $toDate - date to end query at 'YYYY-MM-DD hh:mm:ss'
 * @param $resolution - time resolution to show data 'Day' 'Week' or 'Month'
 */
function getOEEDrilldown($devices, $fromDate, $toDate, $resolution) {
	echo "Getting OEE detailed reports for each KPI's availablility, performance and quality</br>";
	$queryString = '?';
	#add devices to query string
	if(!empty($devices)){
		$deviceQueryString = "";
		for($i = 0; $i < count($devices); $i++){
			if ($i > 0){
				$deviceQueryString = $deviceQueryString . '&'; 
			}
			$deviceQueryString = $deviceQueryString . 'devices=' . $devices[$i];
		}
		$queryString = $queryString . $deviceQueryString;
	}
	#add from/to dates to query string
	$queryString = $queryString . '&from=' . $fromDate;
	if ($toDate != null){
		$queryString = $queryString . '&to=' . $toDate;
	}
	#add resolution
	$queryString = $queryString . '&resolution=' . $resolution;
	echo "Query string is: " . $queryString . "</br>";
	$response = getRequest('/externalApi/v1/oee/drilldown', $queryString);
	printInfo($response);
}

#Helper functions
#--------------------------------------------------------------#

/**
 * Creates the HMAC header to authenticate the API calls.
 *
 * @param $method - type of http method (GET, POST, PUT)
 * @param $path - api path
 * @param $timestamp - time in utc format 
 */
function createHmacAuth($method, $path, $timestamp) {
	global $key, $secret;
	$str = $method . ' ' . $path . $timestamp;
	$hash = hash_hmac('sha1', $str, $secret);
	return $key . ':' . $hash;
}

/**
 * Prints the responses in a "pretty" format, majority of the responses are in JSON format.
 *
 * @param $response - http response of the requests
 */
function printInfo($response) {
	// Check for errors
	if($response === FALSE){
		echo $response . "</br>";
		die($response);
	}

	$responseData = json_decode($response, TRUE);
	echo "<pre>"; print_r($responseData); echo "</pre>";
}


#GET, POST, and PUT
#--------------------------------------------------------------#

/**
 * HTTP GET request 
 *
 * @global $baseUrl - base url/path for the apis
 * @param $path - api path
 *
 * Note: $baseUrl . $path will be the full url to call a certain api.
 */
function getRequest($path, $queryString) {
	global $baseUrl;
	
	$t = microtime(true);
	$micro = sprintf("%03d",($t - floor($t)) * 1000);
	$time = gmdate('Y-m-d\TH:i:s.', $t).$micro.'Z';
	$auth = createHmacAuth('GET', $path, $time);

	$options = array(
		'http' => array(
			'header'=>  "Content-Type: application/json\r\n" .
						"x-hp-hmac-date: " . $time . "\r\n" .
						"x-hp-hmac-authentication: " . $auth . "\r\n",
			'method'  => 'GET',
			#'ignore_errors' => true,  //ignore errors allows you to capture response message on failed API calls
			#'proxy' => 'web-proxy:8080', // If you are operating behind a proxy, uncomment and specify the proxy address and port.
			#'request_fulluri' => true,
		),
	); 

	$context = stream_context_create($options);
	return file_get_contents($baseUrl . $path . $queryString, false, $context);
}

?>