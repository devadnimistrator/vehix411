<?php
class ClaimApi extends Webservice {
	function validate() {
		global $message_cls, $wpdb;
    
    $this -> device = tep_get_value_require("device");
		$this -> keyword = tep_get_value_require("keyword");
		
		$this -> errorcode = SUCCESS_CODE;
	}

	function run() {
		global $message_cls, $wpdb;
		$this -> validate();
		if ($this -> errorcode == SUCCESS_CODE) {
			$sql = "SELECT * FROM " . TABLE_MAKES . " WHERE status=1";
			if ($this->keyword) {
				$sql .= " AND LOWER(`name`) LIKE '%" . strtolower($this->keyword) . "%'";
			}
			$sql .= " ORDER BY `name` ASC";
			$makes = $wpdb -> get_results($sql);
			foreach ($makes as $make) {
				$this -> result[] = array(
					"id" => $make -> ID,
					"name" => $make -> name
				);
			}
		}
		$this -> json_result();
	}

}
