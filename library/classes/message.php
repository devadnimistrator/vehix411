<?php
class Message {
	var $_message;
	var $_errors;

	function __construct() {
		$this -> _message = array();
		$this -> _errors = array();
	}

	function set_error($key, $message) {
		$this -> _errors[$key][] = $message;
	}

	function set_message($key, $message) {
		$this -> _message[$key][] = $message;
	}

	function show_error($key, $style = "error") {
		if (isset($this -> _errors[$key])) {
			$msg = $this -> _errors[$key];

			for ($i = 0; $i < count($msg); $i++) {
				echo '<span class="' . $style . '">' . $msg[$i] . '</span>';
			}
		}
	}

	function show_message($key, $style = "msg") {
		if (isset($this -> _message[$key])) {
			$msg = $this -> _message[$key];

			for ($i = 0; $i < count($msg); $i++) {
				echo '<span class="' . $style . '">' . $msg[$i] . '</span>';
			}
		}
	}

	function get_error($key) {
		if (isset($this -> _errors[$key])) {
			return $this -> _errors[$key][0];
		}
	}

	function get_message($key) {
		if (isset($this -> _message[$key])) {
			return $this -> _message[$key][0];
		}
	}

	function is_empty_error($key = '') {
		if ($key != '') {
			return !isset($this -> _errors[$key]);
		} elseif (count($this -> _errors) > 0) {
			return false;
		}

		return true;
	}

	function is_empty_msg($key = '') {
		if ($key != '') {
			return !isset($this -> _message[$key]);
		} elseif (count($this -> _message) > 0) {
			return false;
		}

		return true;
	}

	function get_all_message($html = false) {
		$result = "";
		while (list(, $msg) = each($this -> _errors)) {
			for ($i = 0; $i < count($msg); $i++) {
				if ($html) {
					$result .= tep_show_msg($msg[$i], "danger", false);
					$result .= "\n";
				} else {
					if ($i > 0) {
						$result .= "\n";
					}
					$result .= $msg[$i];
				}
			}
		}

		return $result;
	}

}
