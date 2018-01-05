<?php
$configurations = $wpdb->get_results("select * from " . TABLE_CONFIGURATIONS);
foreach($configurations as $config) {
	define($config->config_name, $config->config_value);
}
?>