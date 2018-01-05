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
		if ($this -> errorcode == SUCCESS_CODE) {
			$sql = "SELECT * FROM " . TABLE_MODELS . " WHERE status=1 AND make_id=" . $this -> make_id . " ORDER BY `name` ASC";
			$models = $wpdb -> get_results($sql);
			foreach ($models as $model) {
				$model_info = array(
					"id" => $model -> ID,
					"name" => $model -> name,
					"styles" => array()
				);
				
				$styles = $wpdb -> get_results("SELECT * FROM " . TABLE_STYLES . " WHERE `year` = '" . $this -> year . "' AND model_id=" . $model -> ID . " ORDER BY `name` ASC");
				foreach ($styles as $style) {
					$model_info['styles'][] = array(
						"id" => $style -> ID,
						"name" => $style -> name,
					);
				}
				
				$this->result[] = $model_info;
			}
		}
		$this -> json_result();
	}

}
