<?php
require ('library/admin_application_top.php');

$action = tep_get_value_get("action");

header('Content-Type: application/json');
if ($action == 'getmodals') {
	$make_id = tep_get_value_get("make_id");

	$temp = $wpdb -> get_results("SELECT * FROM " . TABLE_MODELS . " WHERE make_id=" . $make_id . " AND status=1 ORDER BY `name`");
	$modals = array();
	foreach ($temp as $modal) {
		$modals[] = array(
			"ID" => $modal -> ID,
			"name" => $modal -> name
		);
	}
	die(json_encode($modals));
}
