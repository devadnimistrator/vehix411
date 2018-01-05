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
			$this -> result[] = $this -> get_youtube_search_result("https://www.youtube.com/results?q=" . urlencode($this->keyword));
		}
		$this -> json_result();
	}

}
