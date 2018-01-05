<?php
/*
 Urban Push
*/

// push notification for iphon
function urban_airship_push_notification_for_iphone($device_tokens, $alert, $push_dev_or_product = PUSH_DEV_OR_PRODUCT) {
	$contents = array();
	$contents['badge'] = "+1";
	$contents['alert'] = $alert;
	$contents['sound'] = "cow";
	$push = array("aps" => $contents, "device_tokens"=>$device_tokens);

	$json = json_encode($push);

	$iphone_key = PUSH_PRODUCT_IPHONE_APPKEY;
	$iphone_pushsecret = PUSH_PRODUCT_IPHONE_PUSHSECRET;
	if ($push_dev_or_product == 'DEV') {
		$iphone_key = PUSH_DEV_IPHONE_APPKEY;
		$iphone_pushsecret = PUSH_DEV_IPHONE_PUSHSECRET;
	}
	
	$session = curl_init(PUSH_NOTIFICATION_URL);
	curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($session, CURLOPT_USERPWD, $iphone_key.':'.$iphone_pushsecret);
	curl_setopt($session, CURLOPT_POST, TRUE);
	curl_setopt($session, CURLOPT_POSTFIELDS, $json);
	curl_setopt($session, CURLOPT_HEADER, FALSE);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
	$content = curl_exec($session);

	// Check if any error occured
	$response = curl_getinfo($session);
	if($response['http_code'] != 200) {
		return false;
	} else {
		return true;
	}

	curl_close($session);
}


// push notification for android
function urban_airship_push_notification_for_android($apids, $alert, $push_dev_or_product = PUSH_DEV_OR_PRODUCT) {
	$android = array();
	$android['alert'] = $alert;
	$push = array("android" => $android, "apids"=>$apids);

	$json = json_encode($push);

	$android_package = PUSH_PRODUCT_ANDROID_PACKAGE;
	$android_secret = PUSH_PRODUCT_ANDROID_CSDM_AUTHORIZATION_TOKEN;
	if ($push_dev_or_product == 'DEV') {
		$android_package = PUSH_DEV_ANDROID_PACKAGE;
		$android_secret = PUSH_DEV_ANDROID_CSDM_AUTHORIZATION_TOKEN;
	}

	$session = curl_init(PUSH_NOTIFICATION_URL);
	curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($session, CURLOPT_USERPWD, $android_package.':'.$android_secret);
	curl_setopt($session, CURLOPT_POST, TRUE);
	curl_setopt($session, CURLOPT_POSTFIELDS, $json);
	curl_setopt($session, CURLOPT_HEADER, FALSE);
	curl_setopt($session, CURLOPT_RETURNTRANSFER, TRUE);
	$content = curl_exec($session);

	// Check if any error occured
	$response = curl_getinfo($session);
	if($response['http_code'] != 200) {
		return false;
	} else {
		return true;
	}

	curl_close($session);
}
?>
