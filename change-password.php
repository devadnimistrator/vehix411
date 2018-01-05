<?php
$page_title = "Change Password";
$page_slug = "users";
$page_sub_slug = "change-password";
require ('library/admin_application_top.php');

$action = (isset($_POST['action']) ? $_POST['action'] : '');

$msg = "";

$user = $wpdb -> get_row("SELECT * FROM " . TABLE_ADMINS . " WHERE `ID`='" . $logined_user_id . "'");

$success_msg = "";
if (tep_not_null($action) && $action == "process") {
	$old_password = tep_get_value_post("old_password", "Old Password", "require;");
	$new_password = tep_get_value_post("new_password", "New Password", "require;length[6,20]");
	$re_password = tep_get_value_post("re_password", "Repeat Password", "equals[new_password];");

	if ($old_password) {
		if (tep_validate_password($old_password, $user -> password)) {

		} else {
			$message_cls -> set_error("old_password", "Incorrect old password.");
		}
	}

	if ($message_cls -> is_empty_error()) {
		if ($wpdb -> update(TABLE_ADMINS, array("password" => tep_encrypt_password($new_password)), array("ID" => $logined_user_id)) !== false) {
			$success_msg = "You have successfully changed password. After next login, you can use the new password.";
		} else {
			$message_cls -> set_error("update_process", "Failed change password.");
		}
	}
}

require ('views/header.php');
?>
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		
		<div class="page-title">
      <div class="title_left">
        <h3><?php echo $page_title?></h3>
      </div>
    </div>
		<div class="clearfix"></div>
		
		<?php
		if ($success_msg != '') {
			tep_show_msg($success_msg);
		}
		?>
		
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Set new password</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">

						<?php
						if (!$message_cls -> is_empty_error()) {
							echo $message_cls -> get_all_message(true);
						}
						
						$bsForm = new bs_FORM("changePWD", "", "post", false);
						$bsForm -> add_element("action", BSFORM_HIDDEN, "process");
						$bsForm -> add_element("old_password", BSFORM_PASSWORD, "", "Old Password", true);
						$bsForm -> add_element("new_password", BSFORM_PASSWORD, "", "New Password", true);
						$bsForm -> add_element("re_password", BSFORM_PASSWORD, "", "Repeat Password", true);

						echo $bsForm -> generate();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
require ('views/footer.php');
