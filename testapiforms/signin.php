<?php

require ('../library/admin_application_top.php');

$api = tep_get_value_post("api");

$bsForm = new bs_FORM($api, "webservice.php", "get", false);
$bsForm->set_target("testApiResult");
$bsForm->add_element("api", BSFORM_TEXT, $api, "api");
$bsForm->add_element("device_type", BSFORM_SELECT, "", "device_type", true, array('test' => 'test', 'ios' => 'ios', 'android' => 'android'));
$bsForm->add_element("device", BSFORM_TEXT, "test", "device");
$bsForm->add_element("username", BSFORM_TEXT, "[username or email]", "username");
$bsForm->add_element("password", BSFORM_TEXT, "", "password");

$bsForm->add_element("", BSFORM_HTML, "<hr/>");

echo $bsForm->generate();
