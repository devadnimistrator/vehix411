<?php

require ('../library/admin_application_top.php');

$api = tep_get_value_post("api");

$bsForm = new bs_FORM($api, "webservice.php", "get", false);
$bsForm->set_target("testApiResult");
$bsForm->add_element("api", BSFORM_TEXT, $api, "api");
$bsForm->add_element("key", BSFORM_TEXT, "", "key");

$bsForm->add_element("", BSFORM_HTML, "<hr/>");

echo $bsForm->generate();