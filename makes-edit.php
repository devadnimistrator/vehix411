<?php
$page_title = "Edit Make";
$page_slug = "makes";
$page_sub_slug = "makes-new";
require ('library/admin_application_top.php');

$makeid = (isset($_GET['ID']) ? $_GET['ID'] : '');
if ($makeid == '') {
	tep_redirect('makes-new.php');
}
$action = (isset($_POST['action']) ? $_POST['action'] : ((isset($_GET['action']) ? $_GET['action'] : '')));
if ($action == 'delete_model') {
	$modelid = (isset($_GET['ID']) ? $_GET['ID'] : '');
	$wpdb -> delete(TABLE_MODELS, array("ID" => $modelid));
	
	die('OK');
} elseif ($action == 'lock_model') {
	$modelid = (isset($_GET['ID']) ? $_GET['ID'] : '');
	$wpdb -> update(TABLE_MODELS, array("status" => 0), array("ID" => $modelid));

	die('OK');
} elseif ($action == 'unlock_model') {
	$modelid = (isset($_GET['ID']) ? $_GET['ID'] : '');
	$wpdb -> update(TABLE_MODELS, array("status" => 1), array("ID" => $modelid));

	die('OK');
}

$makeinfo = $wpdb -> get_row("SELECT * FROM " . TABLE_MAKES . " WHERE `ID` = " . $makeid);

$msg = "";

$success_msg = "";

$name = $makeinfo -> name;
$code = $makeinfo -> code;
$description = $makeinfo -> description;

if (tep_not_null($action) && $action == "process") {
	$name = tep_get_value_post("name", "Name", "require;length[3];");
	//$code = tep_get_value_post("code", "Code", "require;length[3];");
	$description = tep_get_value_post("description", "Description");

	if ($message_cls -> is_empty_error()) {
		$_makeinfo = array(
			"name" => $name,
			//"code" => $code,
			"description" => $description,
		);

		if ($wpdb -> update(TABLE_MAKES, $_makeinfo, array("ID" => $makeid)) !== false) {
			$success_msg = "You have successfully updated make.";
		} else {
			$message_cls -> set_error("update_process", "Failed update make.");
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
			?>
			<script>
				$(function() {
					setTimeout(function() {
						location.href = 'makes-all.php';
					}, 1500);
				})
			</script>
			<?php
			}
		?>
		
		<div class="row">
			<div class="col-md-4 col-sm-4 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Input Make Information</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						
						<?php
						if (!$message_cls -> is_empty_error()) {
							echo $message_cls -> get_all_message(true);
						}

						$bsForm = new bs_FORM("newMake", "", "post", false);
						$bsForm -> set_is_fileupload();
						$bsForm -> add_element("action", BSFORM_HIDDEN, "process");
						$bsForm -> add_element("name", BSFORM_TEXT, $name);
						//$bsForm -> add_element("code", BSFORM_TEXT, $code);
						$bsForm -> add_element("description", BSFORM_TEXTAREA, $description);

						echo $bsForm -> generate();
						?>
					</div>
				</div>
			</div>
			
			<div class="col-md-8 col-sm-8 col-xs-12">        
				<div class="x_panel">
					<div class="x_title">						
						<h2>Models</h2>
						<ul class="nav navbar-right panel_toolbox">
							<li><a class="collapse-link" title="Add new Model" href="models-new.php?makeid=<?php echo $makeid; ?>"><i class="fa fa-plus-circle"></i></a></li>
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
					</div>
					<div class="x_content">
            <table id="table-models" class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <!--th>Code</th-->
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
							<?php
							$models = $wpdb -> get_results("SELECT * FROM " . TABLE_MODELS . " WHERE make_id = " . $makeid);
							?>
							<tbody>
							<?php if (count($models) == 0) : ?>
								<tr>
									<td colspan="15">Empty models.</td>
								</tr>
							<?php else: ?>
              	<?php foreach ($models as $model) : ?>
                <tr id="model-<?php echo $model->ID ?>">
                  <td><?php echo $model->ID ?></td>
                  <td><?php echo $model->name ?></td>
                  <!--td><?php echo $model->code ?></td-->
                  <td class="status-text"><?php echo($model -> status == 1 ? 'Enabled' : 'Disabled'); ?>
                  <td>
                  	<a href='models-edit.php?ID=<?php echo $model->ID ?>' title="Edit"><i class="fa fa-edit"></i></a>
                  	&nbsp;|&nbsp;
                  	<a href='javascript:delete_model(<?php echo $model->ID ?>)' title="Delete"><i class="fa fa-remove"></i></a>
									 	&nbsp;|&nbsp;
									<?php if ($model->status == 1) : ?>
									 	<a href='javascript:lock_model(<?php echo $model->ID ?>)' title="Click for lock" class="model-status"><i class="fa fa-unlock"></i></a>
									<?php else: ?>
									 <a href='javascript:unlock_model(<?php echo $model->ID ?>)' title="Click for unlock" class="model-status"><i class="fa fa-lock"></i></a>
									<?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
			</div>
		</div>
	</div>
</div>

<?php include_once 'views/table.js.php' ?>
<script>
	var $tableModels;
	$(document).ready(function() {
		try {
			$tableModels = $('#table-models').DataTable({
				"order" : [[1, "asc"]]
			});
		} catch(e) {
		}
	});
	
	function delete_model(modelid) {
		if (confirm("Are you sure delete selected model?")) {
			$.get("makes-edit.php?action=delete_model&ID=" + modelid, function() {
				$tableModels.row($("#model-" + modelid)).remove().draw();
			})
		}
	}

	function lock_model(modelid) {
		if (confirm("Are you sure lock selected model?\nLocked model can't login to our dashboard.")) {
			$.get("makes-edit.php?action=lock_model&ID=" + modelid, function() {
				$statusObj = $("#model-" + modelid).find(".status-text");
				$statusObj.text("Disabled");
				
				$linkObj = $("#model-" + modelid).find("a.model-status");
				$linkObj.attr('href', 'javascript:unlock_model(' + modelid + ')');
				$linkObj.attr('title', 'Click for unlock');
				$linkObj.find('i.fa').removeClass('fa-unlock').addClass('fa-lock');

				$tableModels.draw();
			})
		}
	}

	function unlock_model(modelid) {
		if (confirm("Are you sure unlock selected model?")) {
			$.get("makes-edit.php?action=unlock_model&ID=" + modelid, function() {
				$statusObj = $("#model-" + modelid).find(".status-text");
				$statusObj.text("Enabled");
				
				$linkObj = $("#model-" + modelid).find("a.model-status");
				$linkObj.attr('href', 'javascript:lock_model(' + modelid + ')');
				$linkObj.attr('title', 'Click for lock');
				$linkObj.find('i.fa').removeClass('fa-lock').addClass('fa-unlock');

				$tableModels.draw();
			})
		}
	} 
</script>

<?php
require ('views/footer.php');
