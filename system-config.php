<?php
$page_title = "System Configuration";
$page_slug = "system";
require ('library/admin_application_top.php');

$action = (isset($_POST['action']) ? $_POST['action'] : '');

$msg = "";

if (tep_not_null($action) && $action == "process") {
	$site_title = tep_get_value_post("site_title");
	$site_url = tep_get_value_post("site_url");
	$contact_street = tep_get_value_post("contact_street");
	$contact_phone = tep_get_value_post("contact_phone");
	$contact_email = tep_get_value_post("contact_email");
	$promotion = tep_get_value_post("promotion");
	
	$wpdb->update(TABLE_CONFIGURATIONS, array("config_value" => $site_title), array("config_name" => 'SITE_TITLE'));
	$wpdb->update(TABLE_CONFIGURATIONS, array("config_value" => $site_url), array("config_name" => 'SITE_URL'));
	$wpdb->update(TABLE_CONFIGURATIONS, array("config_value" => $contact_street), array("config_name" => 'CONTACT_STREET'));
	$wpdb->update(TABLE_CONFIGURATIONS, array("config_value" => $contact_phone), array("config_name" => 'CONTACT_PHONE'));
	$wpdb->update(TABLE_CONFIGURATIONS, array("config_value" => $contact_email), array("config_name" => 'CONTACT_EMAIL'));
	$wpdb->update(TABLE_CONFIGURATIONS, array("config_value" => $promotion), array("config_name" => 'PROMOTION'));
	
	tep_redirect("system-config.php");
}

require ('views/header.php');
?>
<!-- page content -->
<div class="right_col" role="main">
	<div class="">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_title">
						<h2><?php echo $page_title?></h2>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">

						<form class="form-horizontal form-label-left validateform" novalidate method="post">
							<input type="hidden" name="action" value="process" />
							<p>
								Please configuration for system.
							</p>
							<div class="item form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="site_title">Site Title <span class="required">*</span> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input id="site_title" name="site_title" value="<?php echo SITE_TITLE ?>" class="form-control col-md-7 col-xs-12" required="required" type="text">
								</div>
							</div>
							<div class="item form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="site_url">Site URL <span class="required">*</span> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input id="site_url" name="site_url" value="<?php echo SITE_URL ?>" class="form-control col-md-7 col-xs-12" required="required" type="text">
								</div>
							</div>
							<div class="item form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact_street">Contact Street <span class="required">*</span> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input id="contact_street" name="contact_street" value="<?php echo CONTACT_STREET ?>" required="required" class="form-control col-md-7 col-xs-12">
								</div>
							</div>
							<div class="item form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact_phone">Contact Phone <span class="required">*</span> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input id="contact_phone" name="contact_phone" value="<?php echo CONTACT_PHONE ?>" required="required" class="form-control col-md-7 col-xs-12">
								</div>
							</div>
							<div class="item form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact_email">Contact Email <span class="required">*</span> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<input type="email" id="contact_email" name="contact_email" value="<?php echo CONTACT_EMAIL ?>" required="required" class="form-control col-md-7 col-xs-12">
								</div>
							</div>
							<div class="ln_solid"></div>
							<div class="item form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="contact_email">Promotion <span class="required">*</span> </label>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<textarea class="form-control col-md-7 col-xs-12" id="promotion" name="promotion" rows="8"><?php echo PROMOTION; ?></textarea>
								</div>
							</div>
							<div class="ln_solid"></div>
							<div class="form-group">
								<div class="col-md-6 col-md-offset-3">
									<button type="reset" class="btn btn-primary">
										Cancel
									</button>
									<button id="send" type="submit" class="btn btn-success">
										Submit
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- validator -->
<script src="assets/plugins/validator.min.js"></script>

<!-- validator -->
<script>
	// initialize the validator function
	validator.message.date = 'not a real date';

	// validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
	$('form').on('blur', 'input[required], input.optional, select.required', validator.checkField).on('change', 'select.required', validator.checkField).on('keypress', 'input[required][pattern]', validator.keypress);

	$('.multi.required').on('keyup blur', 'input', function() {
		validator.checkField.apply($(this).siblings().last()[0]);
	});

	$('form').submit(function(e) {
		e.preventDefault();
		var submit = true;

		// evaluate the form using generic validaing
		if (!validator.checkAll($(this))) {
			submit = false;
		}

		if (submit)
			this.submit();

		return false;
	}); 
</script>
<!-- /validator -->

<?php
require ('views/footer.php');