<?php
$page_title = "Edit Model";
$page_slug = "makes";
$page_sub_slug = "makes-all";
require ('library/admin_application_top.php');

$model_id = (isset($_GET['ID']) ? $_GET['ID'] : '');
if ($model_id == '') {
	tep_redirect('makes-all.php');
}

$action = (isset($_POST['action']) ? $_POST['action'] : '');

$msg = "";

$success_msg = "";

$model_info = $wpdb -> get_row("SELECT * FROM " . TABLE_MODELS . " WHERE `ID`=" . $model_id);
$make_info = $wpdb -> get_row("SELECT * FROM " . TABLE_MAKES . " WHERE `ID`=" . $model_info -> make_id);

$name = $model_info -> name;
$code = $model_info -> code;

if (tep_not_null($action) && $action == "process") {
	$name = tep_get_value_post("name", "Name", "require;length[2];");
	//$code = tep_get_value_post("code", "Code", "require;length[2];");

	if ($message_cls -> is_empty_error()) {
		$_modelfo = array(
			"name" => $name,
			"code" => $code,
		);

		if ($wpdb -> update(TABLE_MODELS, $_modelfo, array("ID" => $model_id)) !== false) {
			$success_msg = "You have successfully updated model.";
		} else {
			$message_cls -> set_error("update_process", "Failed update model.");
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
				<h3><?php echo $page_title
				?>
				<a class="btn btn-round btn-info btn-sm" href="makes-edit.php?ID=<?php echo $make_info->ID; ?>"> <?php echo $make_info->name; ?></a></h3>
			</div>
		</div>
		<div class="clearfix"></div>

		<?php
		if ($success_msg != '') {
			tep_show_msg($success_msg);
		?>
		<script>
			$(function() {
				setTimeout(function() {
					location.href = 'makes-edit.php?ID=<?php echo $make_id; ?>';
				}, 1500);
			})
		</script>
		<?php
		}
		?>

		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Input Model Information: @<?php echo $model_id; ?></h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">

						<?php
						if (!$message_cls -> is_empty_error()) {
							echo $message_cls -> get_all_message(true);
						}

						$bsForm = new bs_FORM("editModel", "", "post", false);
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
