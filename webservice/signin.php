<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->device_type = tep_get_value_require("device_type", "Device Type", "require;");
    $this->device = tep_get_value_require("device", "DeviceID", "require;");
    $this->username = tep_get_value_require("username", "Username", "require;");
    $this->password = tep_get_value_require("password", "Password", "require;length[3];");

    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;
      $this->logined_user = $wpdb->get_row("select * from " . TABLE_USERS . " where username='" . $this->username . "'");
      if ($this->logined_user) {
        
      } else {
        $this->logined_user = $wpdb->get_row("select * from " . TABLE_USERS . " where email='" . $this->username . "'");
        if ($this->logined_user) {
          
        } else {
          $this->errorcode = ERRORCODE_USERNAME;
          $message_cls->set_error("username", "You are not registered.");
        }
      }

      if ($this->errorcode == SUCCESS_CODE) {
        $this->logined_user_id = $this->logined_user->ID;
        if (tep_validate_password($this->password, $this->logined_user->password)) {
          if ($this->logined_user->status == 0) {
            $this->errorcode = ERRORCODE_SECURITY;
            $message_cls->set_error("status", "Your account has bloked.");
          } elseif ($this->logined_user->status == 1) {
            $this->errorcode = ERRORCODE_SECURITY;
            $message_cls->set_error("status", "You need to verify email.");
          }
        } else {
          $this->errorcode = ERRORCODE_PASSWORD;
          $message_cls->set_error("password", "Your password is not incorrect.");
        }
      }
    }
  }

  function run() {
    global $message_cls, $wpdb, $email_config;
    $this->validate();
    if ($this->errorcode == SUCCESS_CODE) {
      $loginkey = substr(tep_encrypt_password(tep_generator_password(), false), 0, 12);
      $userinfo = array(
          "loginkey" => $loginkey,
          "last_logined" => date('Y-m-d H:i:s'),
          "last_ip" => $_SERVER['REMOTE_ADDR'],
          "last_device_type" => $this->device_type,
          "last_device" => $this->device
      );

      $wpdb->update(TABLE_USERS, $userinfo, array("ID" => $this->logined_user_id));

      $this->result[] = array(
          "userid" => $this->logined_user_id,
          "username" => $this->logined_user->username,
          "email" => $this->logined_user->email,
          "loginkey" => $loginkey
      );
    }

    $this->json_result();
  }

}
