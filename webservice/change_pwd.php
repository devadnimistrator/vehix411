<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->logined_key = tep_get_value_require("loginkey", "Security Key", "require;");
    $this->password = tep_get_value_require("password", "Password", "require;length[3];");
    $this->device = tep_get_value_require("device", "DeviceID", "require;");

    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;
    }
  }

  function run() {
    global $message_cls, $wpdb;
    $this->validate();
    if ($this->errorcode == SUCCESS_CODE && $this->validate_login()) {

      $password = tep_encrypt_password($this->password);
      $userinfo = array(
          "password" => $password
      );

      $wpdb->update(TABLE_USERS, $userinfo, array("id" => $this->logined_user_id));
    }

    $this->json_result();
  }

}
