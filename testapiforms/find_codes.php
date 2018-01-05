<?php
require ('../library/admin_application_top.php');

$api = tep_get_value_post("api");

$bsForm = new bs_FORM($api, "webservice.php", "get", false);
$bsForm->set_target("testApiResult");
$bsForm -> add_element("api", BSFORM_TEXT, $api, "api");
$bsForm -> add_element("code", BSFORM_TEXT, "", "code");
$bsForm -> add_element("make_id", BSFORM_TEXT, "", "make_id");
$bsForm -> add_element("year", BSFORM_TEXT, "", "year");
$bsForm -> add_element("mode_id", BSFORM_TEXT, "", "model_id", false);
$bsForm -> add_element("device", BSFORM_TEXT, "test", "device", false);

$bsForm -> add_element("", BSFORM_HTML, "<hr/>");

echo $bsForm -> generate();
