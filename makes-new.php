<?php
$page_title = "Add New Make";
$page_slug = "makes";
$page_sub_slug = "makes-new";
require ('library/admin_application_top.php');

$action = (isset($_POST['action']) ? $_POST['action'] : '');

$msg = "";

$success_msg = "";

$name = "";
$code = "";
$description = "";

if (tep_not_null($action) && $action == "process") {
	$name = tep_get_value_post("name", "Name", "require;length[3];");
	//$code = tep_get_value_post("code", "Code", "require;length[3];");
	$description = tep_get_value_post("description", "Description");
	
	if ($message_cls -> is_empty_error()) {
		$makeinfo = array(
			"name" => $name,
			"code" => $code,
			"description" => $description,
			"status" => 1
		);
		
		if ($wpdb -> insert(TABLE_MAKES, $makeinfo) !== false) {
			$name = "";
			$code = "";

			$success_msg = "You have successfully added new make.";
		} else {
			$message_cls -> set_error("update_process", "Failed add new make.");
		}
	}
}

require ('views/header.php');
?>
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		
		<div class="page-name">
      <div class="name_left">
        <h3><?php echo $page_title?></h3>
      </div>
    </div>
		<div class="clearfix"></div>
		
		<?php 
		if ($success_msg != '') { 
			tep_show_msg($success_msg);
			?>
			<script>
				if (!confirm("Continue to add new make?")) {
					location.href = 'makes-all.php';
				}
			</script>
			<?php
			}
		?>
		
		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_name">
						<h2>Input Make Information</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						
						<?php
						if (!$message_cls -> is_empty_error()) {
							echo $message_cls -> get_all_message(true);
						}

						$bsForm = new bs_FORM("newMake", "", "post", false);
						$bsForm->set_is_fileupload();
						$bsForm -> add_element("action", BSFORM_HIDDEN, "process");
						$bsForm -> add_element("name", BSFORM_TEXT, $name);
						$bsForm -> add_element("description", BSFORM_TEXTAREA, $description);
						//$bsForm -> add_element("code", BSFORM_TEXT, $code);
						
						echo $bsForm -> generate();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
</script>

<?php
require ('views/footer.php');
