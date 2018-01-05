<?php
$page_title = "Add New Model";
$page_slug = "makes";
$page_sub_slug = "makes-all";
require ('library/admin_application_top.php');

$make_id = (isset($_GET['makeid']) ? $_GET['makeid'] : '');
if ($make_id == '') {
	tep_redirect('makes-all.php');
}

$action = (isset($_POST['action']) ? $_POST['action'] : '');

$msg = "";

$success_msg = "";

$name = "";
$code = "";

if (tep_not_null($action) && $action == "process") {
	$name = tep_get_value_post("name", "Name", "require;length[2];");
	//$code = tep_get_value_post("code", "Code", "require;length[2];");

	if ($message_cls -> is_empty_error()) {
		$modelinfo = array(
			"make_id" => $make_id,
			"name" => $name,
			"code" => $code,
			"status" => 1
		);

		if ($wpdb -> insert(TABLE_MODELS, $modelinfo) !== false) {
			$name = "";
			$code = "";

			$success_msg = "You have successfully added new model.";
		} else {
			$message_cls -> set_error("update_process", "Failed add new model.");
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
				<h3><?php echo $page_title ?>
				<a class="btn btn-round btn-info btn-sm" href="makes-edit.php?ID=<?php echo $make_id; ?>"> <?php echo $wpdb -> get_var("SELECT `name` FROM " . TABLE_MAKES . " WHERE `ID` = " . $make_id); ?></a></h3>
			</div>
		</div>
		<div class="clearfix"></div>

		<?php
		if ($success_msg != '') {
			tep_show_msg($success_msg);
		?>
		<script>
			if (!confirm("Continue to add new model?")) {
				location.href = 'makes-edit.php?ID=<?php echo $make_id; ?>';
			}
		</script>
		<?php
		}
		?>

		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Input Model Information</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">

						<?php
						if (!$message_cls -> is_empty_error()) {
							echo $message_cls -> get_all_message(true);
						}

						$bsForm = new bs_FORM("newModel", "", "post", false);
						$bsForm -> add_element("action", BSFORM_HIDDEN, "process");
						$bsForm -> add_element("name", BSFORM_TEXT, $name);
						//$bsForm -> add_element("code", BSFORM_TEXT, $code);

						echo $bsForm -> generate();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script></script>

<?php
require ('views/footer.php');
