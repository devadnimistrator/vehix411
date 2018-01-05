<?php

class ClaimApi extends Webservice {

  function validate() {
    global $message_cls, $wpdb;

    $this->key = tep_get_value_require("key", "Verify Key", "require;");
    $this->device = tep_get_value_require("device");

    if ($message_cls->is_empty_error()) {
      $this->errorcode = SUCCESS_CODE;

      $this->logined_user = $wpdb->get_row("select * from " . TABLE_USERS . " where forgot_pwd='" . $this->key . "'");
      if ($this->logined_user) {
        
      } else {
        $this->errorcode = ERRORCODE_SECURITY;
        $message_cls->set_error("key", "Failed reset password.");
      }
    }
  }

  function run() {
    global $message_cls, $wpdb, $email_config;
    $this->validate();
    if ($this->errorcode == SUCCESS_CODE) {
      $forgot_pwd = tep_encrypt_password(tep_generator_password(), false);
      $new_password = tep_generator_password(6);
      $userinfo = array(
          "forgot_pwd" => $forgot_pwd,
          "password" => tep_encrypt_password($new_password)
      );

      $wpdb->update(TABLE_USERS, $userinfo, array("id" => $this->logined_user->ID));

      require_once DIR_WS_CLASSES . 'email.php';
      $ci_email = new CI_Email($email_config);
      $ci_email->from(SITE_TITLE . " <" . CONTACT_EMAIL . ">");
      $ci_email->to($this->logined_user->email);

      $reset_link = HTTP_CATALOG_SERVER . "webservice.php?api=reset_pwd&key=" . $forgot_pwd;
      $subject = "Reset Password.";
      $message = '<h4>Hi ' . $this->logined_user->username . ',</h4>';
      $message .= '<p>&nbsp;</p>';
      $message .= '<p>';
      $message .= 'Your new password: ' . $new_password . '';
      $message .= '</p>';
      $message .= '<p>&nbsp;</p>';
      $message .= '<p>After current now, you need to use the this new password for login.</p>';

      $ci_email->subject($subject);
      $ci_email->message($message);

      $ci_email->send();

      $this->success_message = $subject . "\n" . "Please check your email.";
    }

    $this->json_result();
  }

}
