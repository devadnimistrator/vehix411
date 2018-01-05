<?php
class ClaimApi extends Webservice {
	function validate() {
		global $message_cls, $wpdb;
		
		$this -> errorcode = SUCCESS_CODE;
	}

	function run() {
		global $message_cls, $wpdb;
		$this->validate();
		$this -> result["promotion"] = PROMOTION;
		$this -> json_result();
	}
}
