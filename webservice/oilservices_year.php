<?php

class ClaimApi extends Webservice {

	function validate() {
		global $message_cls, $wpdb;

		$this -> device = tep_get_value_require("device");
		
		if ($message_cls -> is_empty_error()) {
			$this -> errorcode = SUCCESS_CODE;
		}
	}

	function run() {
		global $message_cls, $wpdb;

		$this->validate();

		$years = $wpdb->get_col("SELECT `year` FROM " . TABLE_OILSERVICES . " WHERE `status`=1");
		foreach ($years as $year) {
			$temp = explode(",", $year);
			
			foreach ($temp as $year) {
				if (in_array($year, $this->result)) {
					
				} else {
					$this->result[] = $year;
				}
			}
		}
		rsort($this->result);

		$this -> json_result();
	}

}
