<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->device_type = tep_get_value_require("device_type", "Device Type", "require;");
    $this->device = tep_get_value_require("device", "DeviceID", "require;");
    $this->username = tep_get_value_require("username", "Username", "require;length[3];");
    $this->email = tep_get_value_require("email", "Email", "require;email;");
    $this->password = tep_get_value_require("password", "Password", "require;length[3];");

    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;
      $checkuser = $wpdb->get_var("select count(*) from " . TABLE_USERS . " where username='" . $this->username . "'");
      if ($checkuser > 0) {
        $this->errorcode = ERRORCODE_INPUT_VALUES;
        $message_cls->set_error("username", "This username is already in use, please try another.");
      }
      $checkemail = $wpdb->get_var("select count(*) from " . TABLE_USERS . " where email='" . $this->email . "'");
      if ($checkemail > 0) {
        $this->errorcode = ERRORCODE_INPUT_VALUES;
        $message_cls->set_error("email", "This email address is already in use, please try another.");
      }
    }
  }

  function run() {
    global $message_cls, $wpdb, $email_config;
    $this->validate();
    if ($this->errorcode == SUCCESS_CODE) {
      $password = tep_encrypt_password($this->password);
      $loginkey = substr(tep_encrypt_password(tep_generator_password(), false), 0, 12);
      $userinfo = array(
          "username" => $this->username,
          "email" => $this->email,
          "password" => $password,
          "loginkey" => $loginkey,
          "signed" => date('Y-m-d H:i:s'),
          "last_ip" => $_SERVER['REMOTE_ADDR'],
          "last_device_type" => $this->device_type,
          "last_device" => $this->device,
          "status" => 1
      );

      $wpdb->insert(TABLE_USERS, $userinfo);

      if (false) {
        require_once DIR_WS_CLASSES . 'email.php';
        $ci_email = new CI_Email($email_config);
        $ci_email->from(SITE_TITLE . " <" . CONTACT_EMAIL . ">");
        $ci_email->to($this->email);

        $verify_link = HTTP_CATALOG_SERVER . "webservice.php?api=verify_email&key=" . $loginkey;
        $subject = "Welcome to " . SITE_TITLE;
        $message = '<h4>Hi ' . $this->username . ',</h4>';
        $message .= '<p>&nbsp;</p>';
        $message .= '<p>';
        $message .= 'Thanks for getting started with ' . SITE_TITLE . '. ';
        $message .= 'For support all service from here, you need to confirm your email. ';
        $message .= 'Click below to confirm your email address: <a href="' . $verify_link . '">' . $verify_link . '</a>';
        $message .= '</p>';
        $message .= '<p>&nbsp;</p>';
        $message .= '<p>If you have problems, please paste the above URL into your web browser.</p>';

        $ci_email->subject($subject);
        $ci_email->message($message);

        $ci_email->send();

        $this->success_message = $subject . "\n" . "Please verify email for login.";
      }
      
      $this->result[] = array(
          "userid" => $wpdb->insert_id,
          "username" => $userinfo['username'],
          "email" => $userinfo['email'],
          "loginkey" => $loginkey
      );
    }

    $this->json_result();
  }

}
