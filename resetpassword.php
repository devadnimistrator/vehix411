<?php
require ('library/admin_application_top.php');

$action = (isset($_POST['action']) ? $_POST['action'] : '');

if (isset($_GET['key'])) {
  $user = $wpdb->get_row("select * from " . TABLE_USERS . " where forgot_pwd='" . $_GET['key'] . "'");
  if ($user) {
    
  } else {
    die("<h1>Invalid request for security reasons.</h1>");
  }
} else {
  die("<h1>Invalid request for security reasons.</h1>");
}

$success_msg = FALSE;

if (tep_not_null($action) && $action == "reset_pwd") {
  $email = tep_get_value_post("email", "Email Address", "require;");
  $new_password = tep_get_value_post("new_password", "New Password", "require;length[3,20]");
  $confirm_password = tep_get_value_post("confirm_password", "Confirm Password", "equals[new_password];");

  if ($message_cls->is_empty_error()) {
    $user = $wpdb->get_row("select * from " . TABLE_USERS . " where email='" . $email . "'");
    if ($user) {
      if ($wpdb->update(TABLE_USERS, array("password" => tep_encrypt_password($new_password), "forgot_pwd" => tep_encrypt_password(tep_generator_password(), false)), array("ID" => $user->ID)) !== false) {
        $success_msg = "You have successfully changed password. After next login, you can use the new password.";
      } else {
        $message_cls->set_error("update_process", "Failed reset password.");
      }
    } else {
      $message_cls->set_error("email", "Your email address is incorrectly.");
    }
  }
}
?><!DOCTYPE html><html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reset password - <?php echo SITE_TITLE ?></title>
    <meta name="description" content="Reset password of <?php echo SITE_TITLE ?>" />

    <!-- Bootstrap -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="assets/css/custom.css" rel="stylesheet">
  </head>

  <body style="background:#F7F7F7;">
    <div class="">
      <div id="wrapper">
        <div id="login" class=" form">
          <section class="login_content">
            <div class="col-md-12">
              <?php if ($success_msg): ?>
                <?php tep_show_msg($success_msg, 'success'); ?>
              <?php else: ?>
                <form method="post">
                  <input type="hidden" name="action" value="reset_pwd" />
                  <h1>Reset Password</h1>
                  <?php
                  if (!$message_cls->is_empty_error()) {
                    echo $message_cls->get_all_message(true);
                  }
                  ?>
                  <div>
                    <input type="email" name="email" class="form-control" placeholder="Your Email" required="" />
                  </div>
                  <div>
                    <input type="password" name="new_password" class="form-control" placeholder="New Password" required="" />
                  </div>
                  <div>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required="" />
                  </div>
                  <div class="text-center">
                    <button class="btn btn-default submit" >&nbsp;Reset&nbsp;</button>
                  </div>
                  <div class="clearfix"></div>
                </form>
              <?php endif; ?>
            </div>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>