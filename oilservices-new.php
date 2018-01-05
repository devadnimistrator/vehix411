<?php
$page_title = "Add Oil Services";
$page_slug = "oilservices";
$page_sub_slug = "oilservices-new";
require ('library/admin_application_top.php');

$action = (isset($_POST['action']) ? $_POST['action'] : '');

$msg = "";

$success_msg = "";

$header = "";
$description = "";
$ebay_link = "";
$ebay_keywords = "";
$youtube_link = "";
$youtube_keywords = "";

$year = array();
$make_id = 0;
$model_id = array();

if (tep_not_null($action) && $action == "process") {
	$header = tep_get_value_post("header", "Header", "require;length[3];");
	$description = tep_get_value_post("description", "Description", "require");
	
	$year = tep_get_value_post("year", "Year", "require");
	$make_id = tep_get_value_post("make_id", "Make", "require");
	$model_id = tep_get_value_post("model_id", "Model", "require");
	
	$ebay_link = tep_get_value_post("ebay_link", "Ebay Link");
	$ebay_keywords = tep_get_value_post("ebay_keywords", "Ebay Keyword");
  $youtube_link = tep_get_value_post("youtube_link", "Youtube Link");
	$youtube_keywords = tep_get_value_post("youtube_keywords", "Youtube Keyword");
	

	if ($message_cls -> is_empty_error()) {
		$oilserviceinfo = array(
			"header" => $header,
			"description" => $description,
			"year" => implode(",", $year),
			"make_id" => $make_id,
			"model_id" => implode(",", $model_id),
			"ebay_link" => $ebay_link,
			"ebay_keywords" => $ebay_keywords,
			"youtube_link" => $youtube_link,
			"youtube_keywords" => $youtube_keywords,
			"status" => 1
		);

		if ($wpdb -> insert(TABLE_OILSERVICES, $oilserviceinfo) !== false) {
			$header = "";
			$description = "";
			$ebay_link = "";
      $ebay_keywords = "";
      $youtube_link = "";
      $youtube_keywords = "";
      
			$new_oilservice_id = $wpdb->insert_id;
			foreach($year as $y) {
				$wpdb -> insert(TABLE_OILSERVICE_YEARS, array("oilservice_id" => $new_oilservice_id, "year" => $y));
			}
			
			foreach($model_id as $m) {
				$wpdb -> insert(TABLE_OILSERVICE_MODELS, array("oilservice_id" => $new_oilservice_id, "model_id" => $m));
			}
			
			$year = array();
			$make_id = 0;
			$model_id = array();

			$success_msg = "You have successfully added new oilservice.";
		} else {
			$message_cls -> set_error("update_process", "Failed add oilservices.");
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
				if (!confirm("Continue to add new oilservice?")) {
					location.href = 'oilservices-all.php';
				}
			</script>
			<?php
			}
		?>
		
		<?php
		$bsForm = new bs_FORM("newOilService", "", "post", false);
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
						<h2>Input Oil Service Information</h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
					<?php
					$bsForm -> add_element("action", BSFORM_HIDDEN, "process");
					$bsForm -> add_element("header", BSFORM_TEXT, $header);
					$bsForm -> add_element("description", BSFORM_TEXTAREA, $description);

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
					$make_ids = array(0 => "");
					foreach ($makes as $make) {
						$make_ids[$make -> ID] = $make -> name;
					}

					$model_ids = array();

					$model_ids = array("" => "");
					$bsForm -> add_element("year", BSFORM_CHECKBOX, $year, "Year", true, $years, true);
					$bsForm -> add_element("make_id", BSFORM_SELECT, $make_id, "Make", true, $make_ids);
					$bsForm -> add_element("model_id", BSFORM_CHECKBOX, $model_id, "Model", true, $model_ids, true);
					
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
		$("#em-make_id").change(function() {
			$.getJSON("ajax.php?action=getmodals&make_id=" + $(this).val(), function(modals) {
				$("#em-model_id").empty();
				$("#em-model_id-labels").html('');
				var htmlOptions = '';
				for (var i = 0; i < modals.length; i++) {
					htmlOptions += '<div class="checkbox-input-field">';
					htmlOptions += '<input type="checkbox" name="model_id[]" id="em-model-' + i + '" value="' + modals[i].ID + '" required="required" class="flat">';
					htmlOptions += '&nbsp;&nbsp;<label for="em-model-'+i+'">' + modals[i].name + '&nbsp;</label>';
					htmlOptions += '</div>';
				}
				$("#em-model_id").html(htmlOptions);
				
				$('#em-model_id input.flat').iCheck({
					checkboxClass : 'icheckbox_flat-green',
					radioClass : 'iradio_flat-green'
				});
				
				$("#em-model_id input[type=checkbox]").each(function() {
					$(this).on('ifChanged', function() {
						$div_groups = $(this).parent().parent().parent();
						$div_labels = $("#" + $div_groups.attr('id') + "-labels");
						
						var labels = [];
						
						$div_groups.find("input[type=checkbox]:checked").each(function() {
							labels.push($("label[for=" + $(this).attr('id') + "]").text().trim());
						});
						
						$div_labels.text(labels.join(", "));
					})
				})
			});
		})
	})
</script>

<?php
require ('views/footer.php');
