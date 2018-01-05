<?php

class ClaimApi extends Webservice {

	function validate() {
		global $message_cls, $wpdb;

		$this -> device = tep_get_value_require("device");
		$this -> year = tep_get_value_require("year", "Year", "require;");

		if ($message_cls -> is_empty_error()) {
			$this -> errorcode = SUCCESS_CODE;
		}
	}

	function run() {
		global $message_cls, $wpdb;

		$this -> validate();

		$makes = $wpdb -> get_col("SELECT `make_id` FROM " . TABLE_OILSERVICES . " WHERE `status`=1 AND CONCAT(',', `year`, ',') LIKE '%," . $this -> year . ",%'");
		$make_ids = array();
		foreach ($makes as $make_id) {
			if (in_array($make_id, $make_ids)) {

			} else {
				$make_ids[] = $make_id;
			}
		}

		$makes = $wpdb -> get_results("SELECT * FROM " . TABLE_MAKES . " WHERE `ID` IN (" . implode(",", $make_ids) . ") ORDER BY `name`");
		foreach ($makes as $make) {
			$this -> result[] = array(
				"id" => $make -> ID,
				"name" => $make -> name
			);
		}

		$this -> json_result();
	}

}
