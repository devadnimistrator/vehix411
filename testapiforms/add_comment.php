<?php

require ('../library/admin_application_top.php');

$api = tep_get_value_post("api");

$bsForm = new bs_FORM($api, "webservice.php", "get", false);
$bsForm->set_target("testApiResult");
$bsForm->add_element("api", BSFORM_TEXT, $api, "api");
$bsForm->add_element("loginkey", BSFORM_TEXT, "", "loginkey");
$bsForm->add_element("code_id", BSFORM_TEXT, "", "code_id");
$bsForm->add_element("comment", BSFORM_TEXT, "", "comment");
$bsForm->add_element("rating", BSFORM_SELECT, "", "rating", true, array(5=>5, 4=>4, 3=>3, 2=>2, 1=>1));
$bsForm->add_element("device", BSFORM_TEXT, "test", "device", false);

$bsForm->add_element("", BSFORM_HTML, "<hr/>");

echo $bsForm->generate();
