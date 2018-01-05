<?php

class ClaimApi extends Webservice {

	function validate() {
		global $message_cls, $wpdb;

		$this -> device = tep_get_value_require("device");
		$this -> year = tep_get_value_require("year", "Year", "require;");
		$this -> make_id = tep_get_value_require("make_id", "Make ID", "require;");

		if ($message_cls -> is_empty_error()) {
			$this -> errorcode = SUCCESS_CODE;
		}
	}

	function run() {
		global $message_cls, $wpdb;

		$this -> validate();

		$models = $wpdb -> get_col("SELECT `model_id` FROM " . TABLE_OILSERVICES . " WHERE `status`=1 AND CONCAT(',', `year`, ',') LIKE '%," . $this -> year . ",%' AND make_id='" . $this -> make_id . "'");
		$model_ids = array();
		foreach ($models as $model_id) {
			$temp = explode(",", $model_id);

			foreach ($temp as $model_id) {
				if (in_array($model_id, $model_ids)) {

				} else {
					$model_ids[] = $model_id;
				}
			}
		}

		$models = $wpdb -> get_results("SELECT * FROM " . TABLE_MODELS . " WHERE `ID` IN (" . implode(",", $model_ids) . ") ORDER BY `name`");
		foreach ($models as $model) {
			$this -> result[] = array(
				"id" => $model -> ID,
				"name" => $model -> name
			);
		}

		$this -> json_result();
	}
}
