<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->device = tep_get_value_require("device");
    $this->str = tep_get_value_require("string", "String", "require;");
    $this->make_id = tep_get_value_require("make_id", "MakeID", "require;");
    $this->year = tep_get_value_require("year", "Year", "require;");
	$this->model_id = tep_get_value_require("model_id", "MakeID", "require;");

    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;
    }
  }

  function run() {
    global $message_cls, $wpdb;
    $this->validate();
    if ($this->errorcode == SUCCESS_CODE) {
      $make_name = "";
      $model_name = "";
      $_keyword = "";	
	  $_keyword .= $this->year;	
	  
      if ($this->make_id != '' && $this->make_id != 0) {
        $make_name = $wpdb->get_var("SELECT `code` FROM " . TABLE_MAKES . " WHERE `ID`='" . $this->make_id . "'");
        if ($make_name) {
          $_keyword .= ($_keyword ? " " . $make_name : $make_name);
        }
      }
      if ($this->model_id != '' && $this->model_id != 0) {
        $model_name = $wpdb->get_var("SELECT `name` FROM " . TABLE_MODELS . " WHERE `ID`='" . $this->model_id . "'");
        if ($model_name) {
          $_keyword .= ($_keyword ? " " . $model_name : $model_name);
        }
      }
	  
	  $_keyword .= " ".$this->str ;		
	  
	  
	  $code_info = array(
              "year" => $this->year,
              "makes" => $make_name,
              "models" => $model_name,
              "shopping_keywords" => $_keyword,
              "shopping_result" => array(),
	  );
	  $code_info['shopping_result'] = get_shopping_search_result($code_info['shopping_keywords']);
	$this->result[] = $code_info;
	}
    $this->json_result();
  }

}
