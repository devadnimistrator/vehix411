<?php
require ('library/admin_application_top.php');

if (tep_session_is_registered(SESSION_USER_ID)) {
	tep_redirect("index.php");
}

$error = false;
$error_confirm = false;

$action = (isset($_POST['action']) ? $_POST['action'] : '');

$login_errormsg = "";

if (tep_not_null($action) && $action == "process") {
	$user_name = tep_db_prepare_input($HTTP_POST_VARS['username']);
	$user_password = tep_db_prepare_input($HTTP_POST_VARS['password']);

	if ($user_name == "") {
		$error = true;
		$login_errormsg = "Input Name.";
	} elseif ($user_password == "") {
		$error = true;
		$login_errormsg = "Input password.";
	} else {
		$sql = "select * from " . TABLE_ADMINS . " where `username`='" . $user_name . "'";

		$logined_user = $wpdb -> get_row($sql);
		if ($logined_user) {
			if (tep_validate_password($user_password, $logined_user -> password)) {
				${SESSION_USER_ID} = $logined_user -> ID;

				$wpdb -> update(TABLE_ADMINS, array(
					"last_logined" => tep_now_datetime(),
					"last_logined_ip" => $_SERVER['REMOTE_ADDR']
				), array('ID' => ${SESSION_USER_ID}));

				tep_session_register(SESSION_USER_ID);

				tep_redirect("index.php");
			} else {
				$error = true;
				$login_errormsg = "Name or Password is not validate.";
			}
		} else {
			$error = true;
			$login_errormsg = "Name or Password is not validate.";
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

		<title>Login - <?php echo SITE_TITLE ?></title>
		<meta name="description" content="Login of <?php echo SITE_TITLE?>" />

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
							<form action="login.php" method="post">
								<input type="hidden" name="action" value="process" />
								<h1>Login to <?php echo SITE_TITLE; ?></h1>
								<?php
								if ($login_errormsg) {
									tep_show_msg($login_errormsg, 'danger');
								}
								?>
								<div>
									<input type="text" name="username" class="form-control" placeholder="Username" required="" />
								</div>
								<div>
									<input type="password" name="password" class="form-control" placeholder="Password" required="" />
								</div>
								<div class="text-center">
									<button class="btn btn-default submit" >&nbsp;Log in&nbsp;</button>
								</div>
								<div class="clearfix"></div>
							</form>
						</div>
					</section>
				</div>
			</div>
		</div>
	</body>
</html>