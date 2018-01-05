<?php

class ClaimApi extends Webservice {

	function validate() {
		global $message_cls, $wpdb;

		$this -> device = tep_get_value_require("device");
		$this -> year = tep_get_value_require("year", "Year", "require;");
		$this -> make_id = tep_get_value_require("make_id", "Make ID", "require;");
		$this -> model_id = tep_get_value_require("model_id", "Model ID", "require;");

		if ($message_cls -> is_empty_error()) {
			$this -> errorcode = SUCCESS_CODE;
		}
	}

	function run() {
		global $message_cls, $wpdb;

		$this -> validate();

		$sql = "SELECT * FROM " . TABLE_OILSERVICES . " WHERE `status`=1";
		$sql .= " AND CONCAT(',', `model_id`, ',') LIKE '%," . $this -> model_id . ",%'";
		$sql .= " AND CONCAT(',', `year`, ',') LIKE '%," . $this -> year . ",%'";
		$sql .= " AND make_id='" . $this -> make_id . "'";
		
		$oliservices = $wpdb->get_results($sql);
		foreach ($oliservices as $oliservice) {
			$this -> result[] = array(
				"id" => $oliservice -> ID,
				"name" => $oliservice -> header,
				"year" => $this->year,
				"make_id" => $this->make_id,
				"make_name" => $wpdb->get_var("SELECT `name` FROM " . TABLE_MAKES . " WHERE `ID`=" . $this->make_id),
				"model_id" => $this->model_id,
				"model_name" => $wpdb->get_var("SELECT `name` FROM " . TABLE_MODELS . " WHERE `ID`=" . $this->model_id)
			);
		}

		$this -> json_result();
	}
}
