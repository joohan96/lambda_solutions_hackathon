<?php

require_once ('connectSalesforces.php');

define("ZDAPIKEY", "drv5UZaRc7A8WcXwWErssQ6GIEgxIwLO8xiPPYe6");  
define("ZDUSER", "mariya.pak@lambdasolutions.net");  
define("ZDURL", "https://lambdasolutionsdev.zendesk.com/api/v2");

function curlWrap($url, $json, $action) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_URL, ZDURL.$url);
	curl_setopt($ch, CURLOPT_USERPWD, ZDUSER."/token:".ZDAPIKEY);
	switch($action){
		case "POST":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "GET":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			break;
		case "PUT":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "DELETE":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;
		default:
			break;
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch);
	curl_close($ch);
	$decoded = json_decode($output);
	return $decoded;
}

	//$data = curlWrap("/organizations.json", null, "GET");  


$counter = 100;
foreach ($data as $info) {
	$organization_fields = array('account_owner' => $info->account_owner, 'website' => $info->website,  'package_type' => "null", 'address' => $info->billing_address, 'phone_number' => $info->phone);
	$name = "Organization".(string)$counter;
	$organization_json = array('organization' => array('name' => $name, 'organization_fields' => $organization_fields));
	$json = json_encode($organization_json);
	//echo($json);
	$result = curlWrap("/organizations.json", $json, "POST");  
	var_dump($result);
	$counter++;
}

?>