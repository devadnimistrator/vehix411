<?php
$page_title = "Add Codes";
$page_slug = "codes";
$page_sub_slug = "codes-new";
require ('library/admin_application_top.php');

$action = (isset($_POST['action']) ? $_POST['action'] : '');

$msg = "";

$success_msg = "";

$code = "";
$description = "";
$possible_causes = "";
$year = array();
$make_id = array();
$model_id = 0;
$ebay_link = "";
$ebay_keywords = "";
$youtube_link = "";
$youtube_keywords = "";

if (tep_not_null($action) && $action == "process") {
	$code = tep_get_value_post("code", "Code", "require;");
	$description = tep_get_value_post("description", "Description");
	$possible_causes = tep_get_value_post("possible_causes", "Posivle Corsee");
	$year = tep_get_value_post("year", "Year");
	$make_id = tep_get_value_post("make_id", "Make");
	//$model_id = tep_get_value_post("model_id", "Model");
	$ebay_link = tep_get_value_post("ebay_link", "Ebay Link");
	$ebay_keywords = tep_get_value_post("ebay_keywords", "Ebay Keyword");
  $youtube_link = tep_get_value_post("youtube_link", "Youtube Link");
	$youtube_keywords = tep_get_value_post("youtube_keywords", "Youtube Keyword");

	if ($message_cls -> is_empty_error()) {
		$codeinfo = array(
			"code" => strtolower($code),
			"description" => $description,
			"possible_causes" => $possible_causes,
			"year" => implode(",", $year),
			"make_id" => implode(",", $make_id),
			"model_id" => 0,
			"ebay_link" => $ebay_link,
			"ebay_keywords" => $ebay_keywords,
			"youtube_link" => $youtube_link,
			"youtube_keywords" => $youtube_keywords,
			"status" => 1
		);

		if ($wpdb -> insert(TABLE_PRODUCTS, $codeinfo) !== false) {
			$code = "";
			$description = "";
			$possible_causes = "";
			$year = array();
			$make_id = array();
			$model_id = 0;
			$ebay_link = "";
      $ebay_keywords = "";
      $youtube_link = "";
      $youtube_keywords = "";

			$success_msg = "You have successfully added new code.";
		} else {
			$message_cls -> set_error("update_process", "Failed add codes.");
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
				if (!confirm("Continue to add new code?")) {
					location.href = 'codes-all.php';
				}
			</script>
			<?php
			}
		?>
		
		<?php
		$bsForm = new bs_FORM("newCode", "", "post", false);
		$bsForm -> form_start(TRUE);
		?>
		
		<?php if (!$message_cls -> is_empty_error()) : ?>
		<div class="row">
			<div class="col-md-12">
				<div class="x_panel">
					<div class="x_content">
						<?php echo $message_cls -> get_all_message(true); ?>
					</div>
				</div>
			</div>
		</div>	
		<?php endif; ?>
		
		<div class="row">
			<div class="col-md-7 col-sm-6 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Input Code Information</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
					<?php
					$bsForm -> add_element("action", BSFORM_HIDDEN, "process");
					$bsForm -> add_element("code", BSFORM_TEXT, $code);
					$bsForm -> add_element("description", BSFORM_TEXTAREA, $description);
					$bsForm -> add_element("possible_causes", BSFORM_TEXTAREA, $possible_causes, "Possible causes", false);

					$bsForm -> form_elements(TRUE);

					$bsForm -> form_buttons(TRUE);
					?>
					</div>
				</div>
			</div>
			
			<div class="col-md-5 col-sm-6 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2>Properties</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
					<?php
					$years = array();
					for ($y = 1996; $y <= date('Y'); $y++) {
						$years[$y] = $y;
					}

					$makes = $wpdb -> get_results("SELECT * FROM " . TABLE_MAKES . " WHERE 1 = 1 ORDER BY `name`");
					$make_ids = array();
					foreach ($makes as $make) {
						$make_ids[$make -> ID] = $make -> name;
					}

					$model_ids = array("" => "");
					$bsForm -> add_element("year", BSFORM_CHECKBOX, $year, "Year", false, $years, true);
					$bsForm -> add_element("make_id", BSFORM_CHECKBOX, $make_id, "Make", false, $make_ids, true);
					//$bsForm -> add_element("model_id", BSFORM_SELECT, $model_id, "Model", false, $model_ids);
					
					$bsForm -> form_elements(TRUE);
					?>
					</div>
				</div>
				
				<div class="x_panel">
					<div class="x_title">
						<h2>Ebay Infomation</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<label for="em-ebay_link">Link :</label>
						<input type="url" id="em-ebay_link" class="form-control" name="ebay_link" value="<?php echo $ebay_link; ?>" placeholder="http://www.ebay.com/itm/..." />
						
						<div class="control-group" style="padding-top: 10px;">
              <label>Keywords :</label>
              <input id="em-ebay_keywords" name="ebay_keywords" type="text" class="tags form-control" value="<?php echo $ebay_keywords; ?>" />
              <div id="suggestions-container" style="position: relative; float: left; width: 100%; margin: 10px;"></div>
            </div>
					</div>
				</div>
				<div class="x_panel">
					<div class="x_title">
						<h2>Youtube Infomation</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						<label for="em-youtube_link">Link :</label>
						<input type="url" id="em-youtube_link" class="form-control" name="youtube_link" value="<?php echo $youtube_link; ?>" placeholder="http://www.youtube.com/123456" />
						
						<div class="control-group" style="padding-top: 10px;">
              <label for="em-youtube_keywords">Keywords :</label>
              <input id="em-youtube_keywords" name="youtube_keywords" type="text" class="tags form-control" value="<?php echo $youtube_keywords; ?>" />
              <div id="suggestions-container" style="position: relative; float: left; width: 100%; margin: 10px;"></div>
            </div>
					</div>
				</div>
			</div>
		</div>
		
		<?php
		$bsForm -> form_end(TRUE);
		?>
	</div>
</div>

<!-- jQuery Tags Input -->
<script src="assets/plugins/jquery.tagsinput/src/jquery.tagsinput.js"></script>
<script>
	function onAddTag(tag) {
		alert("Added a tag: " + tag);
	}

	function onRemoveTag(tag) {
		alert("Removed a tag: " + tag);
	}

	function onChangeTag(input, tag) {
		alert("Changed a tag: " + tag);
	}


	$(document).ready(function() {
		$('#em-ebay_keywords').tagsInput({
			width : 'auto',
			tagClass: function(item) {
		    return (item.length > 10 ? 'big' : 'small');
		  }
		});
    
    $('#em-youtube_keywords').tagsInput({
			width : 'auto',
			tagClass: function(item) {
		    return (item.length > 10 ? 'big' : 'small');
		  }
		});
	});
</script>
<!-- /jQuery Tags Input -->

<script>
	$(function() {
		/*$("#em-make_id").change(function() {
			$.getJSON("ajax.php?action=getmodals&make_id=" + $(this).val(), function(modals) {
				console.log(modals);
				var htmlOptions = '<option value=""></option>';
				for (var i = 0; i < modals.length; i++) {
					htmlOptions += '<option value="' + modals[i].ID + '">' + modals[i].name + '</option>';
				}
				console.log(htmlOptions);
				$("#em-model_id").html(htmlOptions);
			});
		})*/
	})
</script>

<?php
require ('views/footer.php');
