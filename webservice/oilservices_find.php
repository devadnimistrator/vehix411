<?php

class ClaimApi extends Webservice {

	function validate() {
		global $message_cls, $wpdb;

		$this -> device = tep_get_value_require("device");
		$this -> keyword = tep_get_value_require("keyword", "Keyword", "require;");

		if ($message_cls -> is_empty_error()) {
			$this -> errorcode = SUCCESS_CODE;
		}
	}

	function run() {
		global $message_cls, $wpdb;
		$this -> validate();
		if ($this -> errorcode == SUCCESS_CODE) {
			$keyword = strtolower($this -> keyword);

			$sql = "SELECT DISTINCT o.`ID` as `id`, `header` as `name`";
			$sql .= ", ma.`ID` as make_id, ma.`name` as make_name";
			$sql .= ", mo.`ID` as model_id, mo.`name` as model_name";
			$sql .= ", y.`year` as `year`";
			$sql .= " FROM " . TABLE_OILSERVICES . " o";
			$sql .= " JOIN " . TABLE_MAKES . " ma ON o.make_id=ma.`ID`";
			$sql .= " JOIN " . TABLE_OILSERVICE_YEARS . " y ON o.`ID`=y.`oilservice_id`";
			$sql .= " JOIN " . TABLE_OILSERVICE_MODELS . " om ON o.`ID`=om.`oilservice_id`";
			$sql .= " JOIN " . TABLE_MODELS . " mo ON mo.`ID`=om.`model_id`";
			$sql .= " WHERE o.`status`=1";
			$sql .= " AND (1=0";
			$sql .= " OR LOWER(o.`header`) LIKE '%" . $keyword . "%'";
			$sql .= " OR LOWER(ma.`name`) LIKE '%" . $keyword . "%'";
			$sql .= " OR y.`year` LIKE '%" . $keyword . "%'";
			$sql .= " OR LOWER(mo.`name`) LIKE '%" . $keyword . "%'";
			$sql .= ")";
			$sql .= " ORDER BY model_name, make_name, `year` DESC, `name`";
			$sql .= " LIMIT 50";

			$this -> result = $wpdb -> get_results($sql);
		}
		$this -> json_result();
	}

}
