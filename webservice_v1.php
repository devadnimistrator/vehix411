<?php
include_once("webservice_v1/webservice.php");

$apikey = tep_get_value_require("api");

include_once("webservice_v1/{$apikey}.php");

$claimApi = new ClaimApi();
$claimApi->run();
