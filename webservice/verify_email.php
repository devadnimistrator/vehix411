<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->key = tep_get_value_require("key", "Verify Key", "require;");
    
    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;
    }
  }

  function run() {
    global $message_cls, $wpdb, $email_config;
    $this->validate();
    if ($this->errorcode == SUCCESS_CODE) {
      $user_id = $wpdb->get_var("select `id` from " . TABLE_USERS . " where loginkey='" . $this->key . "'");
      if ($user_id) {
        $wpdb->update(TABLE_USERS, array("loginkey" => tep_encrypt_password(tep_generator_password(), false), "status" => 2), array("id" => $user_id));

        die('Your account has verified.');
      } else {
        die('Invalid verify email.');
      }
    } else {
      die('Key is required.');
    }
  }

}
