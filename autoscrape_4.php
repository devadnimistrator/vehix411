<?php
require_once ("library/classes/simple_html_dom.php");
require_once ('config/database_tables.php');
require_once ("library/classes/wp_db.php");

$wpdb = new wpdb(DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE, DB_SERVER);

define("API_URL", "http://api.edmunds.com/api/vehicle/v2/");
define("API_KEY", "72frh6spcw8et386dkkceu76");

$makes = $wpdb -> get_results("SELECT * FROM " . TABLE_MAKES);
$make_count = count($makes);
$make_index = 0;
foreach ($makes as $make) {
	$make_index++;
	echo $make_index . " / " . $make_count . " : " . $make -> name . "\n";

	$model_count = $wpdb -> get_var("SELECT count(*) FROM " . TABLE_MODELS . " WHERE make_id=" . $make -> ID);
	if ($model_count > 0) {
		continue;
	}

	$opts = array('http' => array(
			'method' => "GET",
			'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36\r\n" . "Host: api.edmunds.com\r\n"
		));

	$context = stream_context_create($opts);

	$models = file_get_contents(API_URL . $make -> code . "/models?fmt=json&api_key=" . API_KEY, false, $context);
	if ($models) {
	} else {
		echo "\tEmpty : " . API_URL . $make -> code . "/models?fmt=json&api_key=" . API_KEY;
		exit ;
	}
	$models = json_decode($models);
	echo "\tModelCount = " . $models -> modelsCount . "\n";
	$model_index = 0;
	foreach ($models->models as $model) {
		$model_index++;
		$_model = array(
			"make_id" => $make -> ID,
			"name" => $model -> name,
			"code" => $model -> id,
			"status" => 1
		);
		if ($wpdb -> insert(TABLE_MODELS, $_model)) {

		} else {
			echo "\tError:\n";
			var_dump($_model);

			$wpdb -> query("DELETE FROM " . TABLE_MODELS . " WHERE make_id=" . $make -> ID);

			exit ;
			continue;
		}

		$new_model_id = $wpdb -> insert_id;

		echo "\t" . $model_index . " / " . $models -> modelsCount . "  : Inerted new model: #" . $new_model_id . " - " . $model -> name . "\n";

		foreach ($model->years as $year) {
			foreach ($year->styles as $style) {
				$_style = array(
					"ID" => $style -> id,
					"model_id" => $new_model_id,
					"year" => $year->year,
					"name" => $style -> name,
					"trim" => $style -> trim,
					"submodel" => json_encode($style -> submodel)
				);

				if ($wpdb -> insert(TABLE_STYLES, $_style)) {
					echo "\t\tInserted new style #" . $style -> id . "\n";
				} else {
					echo "\t\tFailed insert new style #" . $style -> id . "\n";
				}
				/*$engines = file_get_contents(API_URL . "styles/" . $style -> id . "/engines?fmt=json&api_key=" . API_KEY);
				 if ($engines) {

				 } else {
				 continue;
				 }
				 $engines = json_decode($engines);
				 foreach ($engines->engines as $engine) {
				 $_engine = array(
				 "model_id" => $new_model_id,
				 "year" => $year -> year,
				 "name" => $engine -> name,
				 "code" => $engine -> code,
				 "status" => 1
				 );

				 if ($wpdb -> get_var("SELECT count(*) FROM " . TABLE_ENGINES . " WHERE `code`='" . $engine -> code . "' AND model_id=" . $new_model_id) == 0) {
				 if ($wpdb -> insert(TABLE_ENGINES, $_engine)) {
				 echo "\tInerted new engine: " . $engine -> name . "\n";
				 } else {
				 continue;
				 }
				 }
				 }*/
			}
		}
	}
}
